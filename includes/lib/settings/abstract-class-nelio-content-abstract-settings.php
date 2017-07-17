<?php
/**
 * This file contains the class for managing any plugin's settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

require_once( NELIO_CONTENT_INCLUDES_DIR . '/lib/settings/class-nelio-content-settings-autoloader.php' );

/**
 * This class processes an array of settings and makes them available to WordPress.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
abstract class Nelio_Content_Abstract_Settings {

	/**
	 * The name that identifies Nelio Content's Settings
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $name;

	/**
	 * An array of settings that have been requested and where not found in the associated get_option entry.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $default_values;

	/**
	 * An array with the tabs
	 *
	 * Each item in this array looks like this:
	 *
	 * `
	 * array (
	 *    'name'   => a String that identifies the setting.
	 *    'label'  => the UI label of the tab.
	 *    'fields' => an array with all the fields contained in the tab.
	 * )
	 * `
	 *
	 * or this:
	 *
	 * `
	 * array (
	 *    'name'    => a String that identifies the setting.
	 *    'label'   => the UI label of the tab.
	 *    'partial' => the UI partial of this tab.
	 * )
	 * `
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $tabs;

	/**
	 * The name of the tab we're about to print.
	 *
	 * This is an aux var for enclosing all fields within a tab.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $current_tab_name = false;

	/**
	 * The name of the tab that's currently visible.
	 *
	 * This variable depends on the value of `$_GET['tab']`.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $opened_tab_name = false;

	/**
	 * Whether these settings are ready or not.
	 *
	 * If it's not, getting a value will throw an exception.
	 *
	 * @since  1.1.10
	 * @access private
	 * @var    boolean
	 */
	private $ready = false;

	/**
	 * Initialize the class, set its properties, and add the proper hooks.
	 *
	 * @param string $name The name of this options group.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function __construct( $name ) {

		$this->default_values = array();
		$this->tabs = array();
		$this->name = $name;

		add_action( 'plugins_loaded', array( $this, 'set_tabs' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'mark_as_ready' ), 1 );


	}//end __construct()

	/**
	 * This function has to be implemented by the subclass and specifies which tabs
	 * are defined in the settings page.
	 *
	 * See `do_set_tabs`.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public abstract function set_tabs();

	/**
	 * This function sets the real tabs.
	 *
	 * @param array $tabs An array with the available tabs and the fields within each tab.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function do_set_tabs( $tabs ) {

		$this->tabs = $tabs;

		foreach ( $this->tabs as $key => $tab ) {

			if ( ! isset( $this->tabs[ $key ]['fields'] ) ) {

				$this->tabs[ $key ]['fields'] = array();

			}//end if

			if ( count( $this->tabs[ $key ]['fields'] ) > 0 ) {

				/**
				 * Filters the sections and fields of the given tab.
				 *
				 * @param array $fields The fields (and sections) of the given tab in the settings screen.
				 *
				 * @since 1.0.0
				 */
				$this->tabs[ $key ]['fields'] = apply_filters( 'nelio_content_' . $tab['name']. '_settings',
					$tab['fields']
				);

			}//end if

		}//end foreach

		// Let's see which tab has to be enabled.
		$this->opened_tab_name = $this->tabs[0]['name'];
		if ( isset( $_GET['tab'] ) ) { // Input var okay.
			foreach ( $this->tabs as $tab ) {
				if ( $_GET['tab'] === $tab['name'] ) { // Input var okay.
					$this->opened_tab_name = $tab['name'];
				}//end if
			}//end foreach
		}//end if

	}//end do_set_tabs()

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()

	/**
	 * Register the main hooks related to the settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function define_admin_hooks() {

		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

	}//end define_admin_hooks()

	/**
	 * Returns the value of the given setting.
	 *
	 * @param string $name  The name of the parameter whose value we want to obtain.
	 * @param object $value Optional. Default value if the setting is not found and
	 *                      the setting didn't define a default value already.
	 *                      Default: `false`.
	 *
	 * @return object The concrete value of the specified parameter.
	 *                If the setting has never been saved and it registered no
	 *                default value (during the construction of `Nelio_Content_Settings`),
	 *                then the parameter `$value` will be returned instead.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get( $name, $value = false ) {

		if ( ! $this->ready ) {
			throw new Exception( _x( 'Nelio Content settings should be used after plugins_loaded.', 'error', 'nelio-content' ) );
		}//end if

		$settings = get_option( $this->get_name(), array() );
		if ( isset( $settings[ $name ] ) ) {
			return $settings[ $name ];
		}

		$this->maybe_set_default_value( $name );
		if ( isset( $this->default_values[ $name ] ) ) {
			return $this->default_values[ $name ];
		} else {
			return $value;
		}

	}//end get()

	/**
	 * Looks for the default value of $name (if any) and saves it in the default values array.
	 *
	 * @param string $name The name of the field whose default value we want to obtain.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function maybe_set_default_value( $name ) {

		$field = false;

		foreach ( $this->tabs as $tab ) {
			foreach ( $tab['fields'] as $f ) {
				switch ( $f['type'] ) {
					case 'section':
						continue;
					case 'custom':
						if ( $f['name'] === $name ) {
							$field = $f;
						}
						break;
					case 'checkboxes':
						foreach ( $f['options'] as $option ) {
							if ( $option['name'] === $name ) {
								$field = $f;
							}
						}
						break;
					default:
						if ( $f['name'] === $name ) {
							$field = $f;
						}
				}
			}
		}

		if ( $field && isset( $field['default'] ) ) {
			$this->default_values[ $name ] = $field['default'];
		}

	}//end maybe_set_default_value()

	/**
	 * Registers all settings in WordPress using the Settings API.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register() {

		foreach ( $this->tabs as $tab ) {
			$this->register_tab( $tab );
		}

	}//end register()

	/**
	 * Returns the "name" of the settings script (as used in `wp_register_script`).
	 *
	 * @return string the "name" of the settings script (as used in `wp_register_script`).
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_generic_script_name() {

		return $this->name . '-abstract-settings-js';

	}//end get_generic_script_name()

	/**
	 * Enqueues all required scripts.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_scripts() {

		wp_register_script(
			$this->get_generic_script_name(),
			NELIO_CONTENT_INCLUDES_URL . '/lib/settings/assets/js/settings.js',
			array(),
			Nelio_Content()->get_version(),
			true
		);

	}//end register_scripts()

	/**
	 * Registers the given tab in the Settings page.
	 *
	 * @param array $tab A list with all fields.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @SuppressWarnings( PHPMD.ExcessiveMethodLength )
	 */
	private function register_tab( $tab ) {

		// Create a default section (which will also be used for enclosing all
		// fields within the current tab).
		$section = 'nelio-content-' . $tab['name'] . '-opening-section';
		add_settings_section(
			$section,
			'',
			array( $this, 'open_tab_content' ),
			$this->get_settings_page_name()
		);

		if ( isset( $tab['partial'] ) ) {
			$section = 'nelio-content-' . $tab['name'] . '-tab-content';
			add_settings_section(
				$section,
				'',
				array( $this, 'print_tab_content' ),
				$this->get_settings_page_name()
			);
		}//end if

		foreach ( $tab['fields'] as $field ) {

			$defaults = array(
				'desc' => '',
				'more' => '',
			);
			$field = wp_parse_args( $field, $defaults );

			switch ( $field['type'] ) {

				case 'section':
					$section = $field['name'];
					add_settings_section(
						$field['name'],
						$field['label'],
						'',
						$this->get_settings_page_name()
					);
					break;

				case 'textarea':
					$field = wp_parse_args( $field, array( 'placeholder' => '' ) );

					$setting = new Nelio_Content_Text_Area_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['placeholder']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'email':
				case 'number':
				case 'password':
				case 'text':
					$field = wp_parse_args( $field, array( 'placeholder' => '' ) );

					$setting = new Nelio_Content_Input_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['type'],
						$field['placeholder']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'checkbox':
					$setting = new Nelio_Content_Checkbox_Setting(
						$field['name'],
						$field['desc'],
						$field['more']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'checkboxes':
					$setting = new Nelio_Content_Checkbox_Set_Setting( $field['options'] );

					foreach ( $field['options'] as $cb ) {
						$tuple = array(
								'name'  => $cb['name'],
								'value' => $value,
							);
						$setting->set_value( $tuple );
					}

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'range':
					$setting = new Nelio_Content_Range_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['args']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'radio':
					$setting = new Nelio_Content_Radio_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['options']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'select':
					$setting = new Nelio_Content_Select_Setting(
						$field['name'],
						$field['desc'],
						$field['more'],
						$field['options']
					);

					$value = $this->get( $field['name'] );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				case 'custom':
					$setting = $field['instance'];

					$value = $this->get( $setting->get_name() );
					$setting->set_value( $value );

					$setting->register(
						$field['label'],
						$this->get_settings_page_name(),
						$section,
						$this->get_option_group(),
						$this->get_name()
					);
					break;

				default:
					trigger_error( "Undefined Nelio_Content_Setting field type `${field['type']}'" );

			}
		}

		// Close tab.
		$section = 'nelio-content-' . $tab['name'] . '-closing-section';
		add_settings_section(
			$section,
			'',
			array( $this, 'close_tab_content' ),
			$this->get_settings_page_name()
		);

	}//end register_tab()

	/**
	 * Opens a DIV tag for enclosing the contents of a tab.
	 *
	 * If the tab we're opening is the first one, we also print the actual tabs.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.UnusedLocalVariable )
	 */
	public function open_tab_content() {

		// Print the actual tabs (if there's more than one tab).
		if ( count( $this->tabs ) > 1 && ! $this->current_tab_name ) {
			$tabs = $this->tabs;
			$opened_tab = $this->opened_tab_name;
			include NELIO_CONTENT_INCLUDES_DIR . '/lib/settings/partials/nelio-content-tabs.php';
			$this->current_tab_name = $this->tabs[0]['name'];
		} else {
			$previous_name = $this->current_tab_name;
			$this->current_tab_name = false;
			$num_of_tabs = count( $this->tabs );
			for ( $i = 0; $i < $num_of_tabs - 1 && ! $this->current_tab_name; ++$i ) {
				if ( $this->tabs[ $i ]['name'] === $previous_name ) {
					$current_tab = $this->tabs[ $i + 1 ];
					$this->current_tab_name = $current_tab['name'];
				}
			}
		}

		// And now group all the fields under.
		if ( $this->current_tab_name === $this->opened_tab_name ) {
			echo '<div id="' . esc_attr( $this->current_tab_name ) . '-tab-content" class="tab-content">';
		} else {
			echo '<div id="' . esc_attr( $this->current_tab_name ) . '-tab-content" class="tab-content" style="display:none;">';
		}

	}//end open_tab_content()

	/**
	 * Prints the contents of a tab that uses the `partial` option.
	 *
	 * @param array $args the ID, title, and callback info of this section.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function print_tab_content( $args ) {

		$name = $args['id'];
		$name = preg_replace( '/^nelio-content-/', '', $name );
		$name = preg_replace( '/-tab-content$/', '', $name );

		foreach ( $this->tabs as $tab ) {

			if ( $tab['name'] === $name && isset( $tab['partial'] ) ) {
				include $tab['partial'];
			}//end if

		}//end foreach

	}//end print_tab_content()

	/**
	 * Closes a tab div.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function close_tab_content() {

		echo '</div>';

	}//end close_tab_content()

	/**
	 * Get the name of the option group.
	 *
	 * @return string the name of the settings.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_name() {
		return $this->name . '_settings';
	}//end get_name()

	/**
	 * Get the name of the option group.
	 *
	 * @return string the name of the option group.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_option_group() {
		return $this->name . '_group';
	}//end get_option_group()

	/**
	 * Get the name of the option group.
	 *
	 * @return string the name of the option group.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function get_settings_page_name() {
		return $this->name . '-settings-page';
	}//end get_settings_page_name()

	/**
	 * This function marks the settings as ready, which means that we can
	 * retrieve all the options (as well as their default values).
	 *
	 * @since  1.1.10
	 * @access public
	 */
	public function mark_as_ready() {
		$this->ready = true;
	}//end mark_as_ready()

}//end class
