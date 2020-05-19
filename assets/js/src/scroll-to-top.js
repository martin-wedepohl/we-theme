/**
 * Javascript to show/hide the scroll to top button.
 */
'use strict';

const backbutton = document.getElementById( 'back-to-top' );
const bottom = css_objects.bottom;
const right = css_objects.right;
const styles = document.body.style;

styles.setProperty( '--bottom-loc', bottom );
styles.setProperty( '--right-loc', right );

/**
 * Set function to be called when the window scrolls.
 */
window.onscroll = function() {
	scrollFunction();
};

/**
 * Check to see if the document has been scrolled by 20 pixels or not and if so display the button.
 *
 * Requires:
 *    document.body.scrollTop            - Safari
 *    document.documentElement.scrollTop - Chrome, Firefox, ID, Opera
 */
function scrollFunction() {
	const scroll = css_objects.scroll;

	if ( document.body.scrollTop > scroll || document.documentElement.scrollTop > scroll ) {
		backbutton.style.display = 'block';
	} else {
		backbutton.style.display = 'none';
	}
}
