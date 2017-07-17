<?php
/**
 * This file contains a helper class for retrieving a URL's meta data.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class contains several methods for retrieving a URL's meta data such
 * as, for example, its author or publication date.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Link_Meta {

	/**
	 * This method returns meta data about the given URL.
	 *
	 * The returned object includes the following values:
	 *
	 *  * author
	 *  * date (the publication date)
	 *  * email (the author's email)
	 *  * excerpt
	 *  * image (the featured image)
	 *  * permalink
	 *  * title
	 *  * twitter (the author's twitter username)
	 *
	 * @param string $url The URL for which we want to retrieve meta data.
	 *
	 * @return array a list of metadata.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_url_meta_data( $url ) {

		$result = array(
			'responseCode' => '',
			'author'       => '',
			'date'         => '',
			'email'        => '',
			'excerpt'      => '',
			'image'        => '',
			'permalink'    => '',
			'title'        => '',
			'twitter'      => '',
		);

		// If the URL is empty, return.
		if ( empty( $url ) ) {
			return $result;
		}//end if

		// Let's obtain the contents of the URL.
		$aux = $this->get_page_content( $url );

		// If we couldn't load the page content, return.
		if ( empty( $aux ) ) {
			return $result;
		}//end if

		$result['responseCode'] = $aux['responseCode'];
		$page = $aux['content'];
		$page = preg_replace( '/\n/', '', $page );

		// If the response code is an error, return.
		if ( in_array( $result['responseCode'], array( 403, 404, 500 ), true ) ) {
			return $result;
		}//end if

		// If we couldn't load the page content, return.
		if ( empty( $page ) ) {
			return $result;
		}//end if

		$meta_tags = array();
		$meta_tags = $this->extract_meta_data_from_url( $page );
		$meta_tags['nelio-content:url'] = $url;

		// Let's populate the results object.
		// Author.
		$keys = array( 'author', 'nelio-content:author' );
		$result['author'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Date.
		$keys = array( 'article:published_time' );
		$result['date'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Email.
		$keys = array();
		$result['email'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Excerpt.
		$keys = array( 'og:description', 'description', 'twitter:description' );
		$result['excerpt'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Image.
		$keys = array( 'og:image' );
		$result['image'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Permalink.
		$keys = array( 'og:url', 'nelio-content:url' );
		$result['permalink'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Title.
		$keys = array( 'og:title', 'nelio-content:title', 'twitter:title' );
		$result['title'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Twitter.
		$keys = array( 'twitter:creator' );
		$result['twitter'] = nc_get_first_option( $meta_tags, $keys, '' );

		// Finally, if we weren't able to get the post's author, but we did find
		// her twitter, let's see if we can get her name by looking at her twitter
		// account.
		if ( empty( $result['author'] ) && ! empty( $result['twitter'] ) ) {
			$result['author'] = $this->get_author_from_twitter( $result['twitter'] );
		}//end if

		// Strip all HTML tags.
		foreach ( $result as $key => $value ) {
			$result[ $key ] = html_entity_decode( wp_strip_all_tags( $value ) );
		}//end if

		return $result;

	}//end get_url_meta_data()

	/**
	 * This helper function retrieves the contents of a given URL.
	 *
	 * @param string $url A page URL.
	 *
	 * @return array|boolean An array with two elements: the `body` of the given
	 *            URL (that is, the page's content) and a `responseCode` (which
	 *            can be used by the calling function to know whether accessing
	 *            the given URL was successful or not). Alternatively, the
	 *            function can also return `false`, when things went absolutely
	 *            wrong.
	 *
	 * @since 1.0.0
	 */
	private function get_page_content( $url ) {

		$result = array(
			'responseCode' => '',
			'content'      => '',
		);

		$response = @wp_remote_get( $url ); // @codingStandardsIgnoreLine

		// If we couldn't open the page, let's return an empty result object.
		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		// If the response code is an error, return it.
		$result['responseCode'] = $response['response']['code'];
		if ( in_array( $result['responseCode'], array( 403, 404, 500 ), true ) ) {
			return $result;
		}//end if

		$page = $response['body'];

		// Fix the page encoding (if necessary).
		if ( isset( $response['headers']['content-type'] ) ) {

			$content_type = $response['headers']['content-type'];

			if ( preg_match( '/charset=([a-zA-Z0-9-]+)/i', $content_type, $matches ) ) {

				$charset = $matches[1];
				if ( stripos( $charset, 'utf' ) !== 0 ) {
					$page = mb_convert_encoding( $page, 'UTF-8', $charset );
				}//end if

			}//end if

		}//end if

		$result['content'] = $page;
		return $result;

	}//end get_page_content()

	/**
	 * This helper function extracts all meta fields included in the given page.
	 *
	 * @param string $page A string that, presumably, corresponds to the contents of a web page.
	 *
	 * @return array A list of all the meta tags found in the web page, plus some
	 *               additional "custom" metas extracted by Nelio.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function extract_meta_data_from_url( $page ) {

		// First, we add the URL of the request.
		$meta_tags = array();

		// Then, we obtain the title tag.
		if ( preg_match( '/<title>(.*)<\/title>/i', $page, $matches ) ) {
			$meta_tags['nelio-content:title'] = strip_tags( $matches[1] );
		}//end if

		// Next, we try to discover who the author is.
		$meta_tags['nelio-content:author'] = $this->get_author( $page );

		// Finally, we look for all meta tags. First, property/name and content.
		if ( preg_match_all( '/<meta\s+(property|name)="([^"]*)"\s+content="([^"]*)"[^>]*>/i', $page, $matches ) ) {

			$count = count( $matches[0] );
			for ( $i = 0; $i < $count; ++$i ) {
				$key = strtolower( $matches[2][ $i ] );
				$meta_tags[ $key ] = $matches[3][ $i ];
			}//end for

		}//end if

		// Then, content and property/name.
		if ( preg_match_all( '/<meta\s+content="([^"]*)"\s+(property|name)="([^"]*)"[^>]*>/i', $page, $matches ) ) {

			$count = count( $matches[0] );
			for ( $i = 0; $i < $count; ++$i ) {
				$key = strtolower( $matches[3][ $i ] );
				$meta_tags[ $key ] = $matches[1][ $i ];
			}//end for

		}//end if

		return $meta_tags;

	}//end extract_meta_data_from_url()

	/**
	 * A helper function that tries to extract the page's author by looking at
	 * some well-known HTML tags (such as vcards, schemas, and so on).
	 *
	 * @param string $page A string that, presumably, corresponds to the contents of a web page.
	 *
	 * @return string The page's author.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 * @SuppressWarnings( PHPMD.NPathComplexity )
	 */
	private function get_author( $page ) {

		// First of all, we look for the `vcard author` name.
		if ( preg_match( '/(\bvcard\b[^"]+\bauthor\b|\bauthor\b[^"]+\bvcard\b).{0,200}\bfn\b(.{30,200})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[2], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// Then, we try to look for a schema.org or data-vocabulary.org author name.
		if ( preg_match( '/https?:\/\/(data-vocabulary|schema).org\/Person.{0,200}\bname\b(.{3,200})/i', $page, $matches ) ) {

			$match = $matches[2];
			if ( preg_match( '/>([^<]{3,40})</i', $match, $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if

			if ( preg_match( '/content="([^"]{3,40})"/', $match, $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if

		}//end if

		// Next, we try to discover the author using WordPress' default class name.
		if ( preg_match( '/\bauthor-name\b(.{3,200})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[1], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// Next, we look for the "attributionNameClick" property.
		if ( preg_match( '/\battributionNameClick\b(.{3,150})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[1], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// Finally, we try to discover the author looking at a "rel author" link.
		if ( preg_match( '/<a.{0,200}rel=.author.(.{3,200})/i', $page, $matches ) ) {
			if ( preg_match( '/>([^<]{3,40})</i', $matches[1], $matches ) ) {
				$author = trim( $matches[1] );
				if ( ! empty( $author ) ) {
					return $author;
				}//end if
			}//end if
		}//end if

		// If everything failed, let's return the empty string.
		return '';

	}//end get_author()

	/**
	 * This helper function retrieves the "display name" behind a Twitter's username.
	 *
	 * @param string $username A Twitter's username.
	 *
	 * @return string The "display name" behind the given Twittwer's username.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 * @SuppressWarnings( PHPMD.NPathComplexity )
	 */
	private function get_author_from_twitter( $username ) {

		// Result variable.
		$author = '';

		// Get $username's twitter profile page.
		$username = str_replace( '@', '', $username );
		$aux = $this->get_page_content( 'https://twitter.com/' . $username );

		// If we couldn't load the page content, return.
		if ( empty( $aux ) ) {
			return $author;
		}//end if

		// If the response code is an error, return.
		if ( in_array( $aux['responseCode'], array( 403, 404, 500 ), true ) ) {
			return $author;
		}//end if

		// If we were able to load the page, let's loook for the author's name in
		// there.
		$page = $aux['content'];

		if ( preg_match( '/data-screen-name="' . $username . '".+data-name="([^"]+)"/i', $page, $matches ) ) {
			$author = trim( $matches[1] );
		}//end if

		if ( empty( $author ) ) {
			if ( preg_match( '/<title>(.*)<\/title>/i', $page, $matches ) ) {
				$author = trim( strip_tags( $matches[1] ) );
			}//end if
		}//end if

		return $author;

	}//end get_author_from_twitter()

}//end class

