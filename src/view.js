/**
 * WordPress dependencies
 */
import { createRoot } from '@wordpress/element';

/**
 * Internal dependencies
 */
import Frame from './components/frame';

function init() {
	const container = document.querySelector( '.wp-block-wporg-contributor-orientation' );
	createRoot( container ).render( <Frame /> );
}

window.addEventListener( 'load', init );
