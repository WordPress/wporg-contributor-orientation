/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { CheckboxControl, Notice } from '@wordpress/components';
import { useInstanceId } from '@wordpress/compose';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import Buttons from './buttons';

const allTeams = Object.keys( WPorgContribBlock.teams );

export default function QuestionStep( {
	step,
	headline,
	questions,
	teamList,
	value,
	onChange,
	onNext,
	onPrevious,
} ) {
	const id = useInstanceId( QuestionStep );
	const [ selected, setSelected ] = useState( value );
	const [ notice, setNotice ] = useState( '' );
	const prefix = `q-${ step }-${ id }`;
	if ( ! teamList ) {
		teamList = allTeams;
	}

	return (
		<fieldset>
			<legend className="screen-reader-text">{ headline }</legend>
			<p className="wporg-contributor-orientation--title" aria-hidden>
				{ headline }
			</p>
			{ questions.map( ( { teams, label }, i ) => {
				if ( ! teamList.some( ( team ) => teams.includes( team ) ) ) {
					return null;
				}
				return (
					<CheckboxControl
						key={ `${ prefix }-${ i }` }
						label={ label }
						checked={ selected.includes( `q${ step }:` + teams.join( ',' ) ) }
						onChange={ ( isChecked ) => {
							const newValue = `q${ step }:` + teams.join( ',' );
							if ( isChecked && ! selected.includes( newValue ) ) {
								setSelected( [ ...selected, newValue ] );
							}
							if ( ! isChecked && selected.includes( newValue ) ) {
								setSelected( selected.filter( ( item ) => item !== newValue ) );
							}
						} }
					/>
				);
			} ) }
			{ !! notice && (
				<Notice status="warning" onRemove={ () => setNotice( '' ) }>
					{ notice }
				</Notice>
			) }
			<Buttons
				step={ step }
				onPrevious={ () => {
					setNotice( '' );
					onChange( selected );
					onPrevious();
				} }
				onNext={ () => {
					setNotice( '' );
					// Prevent moving forward if nothing selected on this step.
					const responses = selected.filter( ( item ) => item.startsWith( `q${ step }:` ) );
					if ( responses.length ) {
						onChange( selected );
						onNext();
					} else {
						setNotice( __( 'You must select at least one option', 'wporg' ) );
					}
				} }
			/>
		</fieldset>
	);
}
