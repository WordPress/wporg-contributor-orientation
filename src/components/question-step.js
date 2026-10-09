/**
 * WordPress dependencies
 */
import { __, _n, sprintf } from '@wordpress/i18n';
import { CheckboxControl, Notice, RadioControl } from '@wordpress/components';
import { decodeEntities } from '@wordpress/html-entities';
import { useInstanceId } from '@wordpress/compose';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import Buttons from './buttons';

// How many answers a checkbox question accepts.
const MAX_CHOICES = 3;

export default function QuestionStep( { step, headline, type, questions, value, onChange, onNext, onPrevious } ) {
	const id = useInstanceId( QuestionStep );
	const [ notice, setNotice ] = useState( '' );
	const prefix = `q-${ step }-${ id }`;
	const isRadio = 'radio' === type;
	const isFull = ! isRadio && value.length >= MAX_CHOICES;
	const title = decodeEntities( headline );
	const hint = isRadio
		? __( 'Choose one answer.', 'wporg' )
		: sprintf(
				/* translators: %d: the most answers someone can choose. */
				_n( 'Choose up to %d answer.', 'Choose up to %d answers.', MAX_CHOICES, 'wporg' ),
				MAX_CHOICES
		  );

	const legend = `${ title } ${ hint }`;
	// A radio group brings its own fieldset, so it carries the question itself.
	const Group = isRadio ? 'div' : 'fieldset';

	return (
		<Group>
			{ ! isRadio && <legend className="screen-reader-text">{ legend }</legend> }
			<p className="wporg-contributor-orientation--title" aria-hidden>
				{ title }
			</p>
			<p className="wporg-contributor-orientation--hint" aria-hidden>
				{ hint }
			</p>
			{ isRadio ? (
				<RadioControl
					label={ legend }
					hideLabelFromVision
					selected={ value.length ? String( value[ 0 ] ) : '' }
					options={ questions.map( ( { label }, index ) => ( {
						label: decodeEntities( label ),
						value: String( index ),
					} ) ) }
					onChange={ ( selected ) => {
						setNotice( '' );
						onChange( [ Number( selected ) ] );
					} }
				/>
			) : (
				questions.map( ( { label }, index ) => {
					const isChecked = value.includes( index );
					const isDisabled = isFull && ! isChecked;
					return (
						<CheckboxControl
							key={ `${ prefix }-${ index }` }
							className={ isDisabled ? 'is-disabled' : undefined }
							label={ decodeEntities( label ) }
							checked={ isChecked }
							disabled={ isDisabled }
							onChange={ ( checked ) => {
								setNotice( '' );
								onChange(
									checked ? [ ...value, index ] : value.filter( ( item ) => item !== index )
								);
							} }
						/>
					);
				} )
			) }
			{ !! notice && (
				<Notice status="warning" onRemove={ () => setNotice( '' ) }>
					{ notice }
				</Notice>
			) }
			<Buttons
				step={ step }
				onPrevious={ () => {
					setNotice( '' );
					onPrevious();
				} }
				onNext={ () => {
					setNotice( '' );
					// Prevent moving forward if nothing selected on this step.
					if ( value.length ) {
						onNext();
					} else {
						setNotice( __( 'You must select at least one option', 'wporg' ) );
					}
				} }
			/>
		</Group>
	);
}
