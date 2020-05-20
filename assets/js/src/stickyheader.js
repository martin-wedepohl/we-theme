/**
 * Sticky Header script.
 *
 * Passed sticky_header_data object from PHP
 *    - sticky_mobile (string) - If we should stick the mobile headers as well
 */
document.addEventListener( 'DOMContentLoaded', function() {
	const header = document.getElementById( 'masthead' );
	header.classList.add( 'sticky-header' );
	if ( 'true' === sticky_header_data.sticky_mobile ) {
		header.classList.add( 'stick-mobile' );
	}
} );
