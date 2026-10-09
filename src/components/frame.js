/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { useRef, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import QuestionStep from './question-step';
import Results from './results';
import { rankTeams } from '../utils/score';

const allPages = WPorgContribBlock.pages;
const steps = allPages.map( ( page ) => page.stepTitle );

// The best match plus four more teams.
const RESULT_COUNT = 5;

/**
 * Collect the tags of every chosen answer.
 *
 * @param {Object} answers Chosen answer indexes, keyed by page index.
 * @return {string[]} Tags, repeated once for each chosen answer that carries them.
 */
function getSelectedTags( answers ) {
	return allPages.flatMap( ( page, index ) =>
		( answers[ index ] || [] ).flatMap( ( answer ) => page.questions[ answer ].tags )
	);
}

export default function Frame() {
	const [ i, setPage ] = useState( 0 );
	// Chosen answer indexes, keyed by page index, e.g. `{ 0: [ 1, 4 ], 2: [ 0 ] }`.
	const [ answers, setAnswers ] = useState( {} );
	const pageRef = useRef( null );
	const goTo = ( index ) => {
		if ( pageRef.current ) {
			pageRef.current.tabIndex = -1;
			pageRef.current.focus();
		}
		setPage( index );
	};

	const page = i < allPages.length ? allPages[ i ] : false;
	if ( ! page ) {
		return null;
	}

	const isLastPage = i >= allPages.length - 1;

	return (
		<div className="wporg-contributor-orientation">
			<ul className="wporg-contributor-orientation--steps">
				{ steps.map( ( step, key ) => (
					<li key={ key } aria-current={ key === i ? 'step' : null }>
						<strong>
							{ sprintf(
								/* translators: %s step number */
								__( 'Step %s:', 'wporg' ),
								key + 1
							) }
						</strong>
						{ step }
					</li>
				) ) }
			</ul>
			<div className="wporg-contributor-orientation--page" ref={ pageRef }>
				{ ! isLastPage ? (
					<QuestionStep
						key={ i }
						{ ...page }
						value={ answers[ i ] || [] }
						onChange={ ( value ) => {
							setAnswers( { ...answers, [ i ]: value } );
						} }
						onPrevious={ () => goTo( i - 1 ) }
						onNext={ () => goTo( i + 1 ) }
					/>
				) : (
					<Results
						{ ...page }
						teams={ rankTeams( getSelectedTags( answers ), WPorgContribBlock.teams ).slice(
							0,
							RESULT_COUNT
						) }
						onPrevious={ () => goTo( i - 1 ) }
						onRestart={ () => {
							setAnswers( {} );
							goTo( 0 );
						} }
					/>
				) }
			</div>
		</div>
	);
}
