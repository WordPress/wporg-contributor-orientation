/* global WPorgContribBlock */
/**
 * WordPress dependencies
 */
import { CheckboxControl } from '@wordpress/components';
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
			<Buttons
				step={ step }
				onPrevious={ () => {
					onChange( selected );
					onPrevious();
				} }
				onNext={ () => {
					onChange( selected );
					onNext();
				} }
			/>
		</fieldset>
	);
}
