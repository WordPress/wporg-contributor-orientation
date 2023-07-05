/* global WPorgContribBlock */
/**
 * WordPress dependencies
 */
import { useInstanceId } from '@wordpress/compose';
import { useState } from '@wordpress/element';

const allTeams = Object.keys( WPorgContribBlock.teams );

export default function QuestionStep( {
	step,
	headline,
	questions,
	teamList,
	value,
	onChange,
	nextPage,
	prevPage,
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
					<p key={ `${ prefix }-${ i }` }>
						<input
							id={ `${ prefix }-${ i }` }
							type="checkbox"
							checked={ selected.includes( `q${ step }:` + teams.join( ',' ) ) }
							onChange={ ( event ) => {
								const newValue = `q${ step }:` + teams.join( ',' );
								if ( event.target.checked && ! selected.includes( newValue ) ) {
									setSelected( [ ...selected, newValue ] );
								}
								if ( ! event.target.checked && selected.includes( newValue ) ) {
									setSelected( selected.filter( ( item ) => item !== newValue ) );
								}
							} }
						/>
						<label htmlFor={ `${ prefix }-${ i }` }>{ label }</label>
						<span style={ { display: 'none' } }>{ teams.join( ', ' ) }</span>
					</p>
				);
			} ) }
			<div className="wporg-contributor-orientation--actions">
				{ step > 1 && (
					<button
						onClick={ () => {
							onChange( selected );
							prevPage();
						} }
					>
						Prev
					</button>
				) }
				<button
					onClick={ () => {
						onChange( selected );
						nextPage();
					} }
				>
					Next
				</button>
			</div>
		</fieldset>
	);
}
