(function( $ ) {
	'use strict';

	// Fix help buttons.
	$( 'img.nelio-content-help' ).click( function( ev ) {
		ev.preventDefault();
		$(this).closest( 'tr' ).find( '.setting-help' ).toggle();
	});

	// Tab management.
	var $tabs = $( '.nav-tab' );
	$tabs.removeClass( '.nav-tab-active' );

	var $tabContents = $( '.tab-content' );
	$tabContents.hide();

	// Get the current tab.
	var $currentTab;
	var currentTabName = '';

	var matches = window.location.href.match( /\btab=([^&]+)/ );
	if ( matches && matches.length > 1 ) {
		currentTabName = matches[1];
		$currentTab = $( '#' + currentTabName );
		if ( $currentTab && 0 === $currentTab.length ) {
			currentTabName = '';
		}//end if
	}//end if

	if ( '' === currentTabName ) {
		$currentTab = $tabs.eq( 0 );
		currentTabName = $currentTab.attr( 'id' );
	}//end if

	// Select the current tab.
	$currentTab.addClass( 'nav-tab-active' );
	$( '#' + currentTabName + '-tab-content' ).show();

	$tabs.click( function( ev ) {

		ev.preventDefault();
		var id = $( this ).attr( 'id' );

		var url = window.location.href;

		if ( /\btab=/.test( url ) ) {
			url = url.replace( /\btab=[^&]+/, 'tab=' + id );
		} else if ( url.indexOf( '?' ) > 0 ) {
			url += '&tab=' + id;
		} else {
			url = '?tab=' + id;
		}//end if

		window.location.href = url;

	});

})( jQuery );
