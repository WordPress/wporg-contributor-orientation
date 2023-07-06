/* global WPorgContribBlock */
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button, ButtonGroup } from '@wordpress/components';

const allPages = WPorgContribBlock.pages;

export default function Buttons( { step, onNext, onPrevious } ) {
	const total = allPages.length;
	if ( step < 0 ) {
		step = allPages.length;
	}
	return (
		<ButtonGroup className="wporg-contributor-orientation--actions">
			{ step > 1 && (
				<Button onClick={ onPrevious } variant="secondary">
					{ __( 'Previous', 'wporg' ) }
				</Button>
			) }
			{ step < total && (
				<Button onClick={ onNext } variant="primary">
					{ __( 'Next', 'wporg' ) }
				</Button>
			) }
		</ButtonGroup>
	);
}
