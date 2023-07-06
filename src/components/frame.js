/**
 * WordPress dependencies
 */
import { useEffect, useRef, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import DoneStep from './done-step';
import QuestionStep from './question-step';

const allPages = WPorgContribBlock.pages;
const allTeams = Object.keys( WPorgContribBlock.teams );
const steps = WPorgContribBlock.pages.reduce( ( acc, current ) => {
	acc.push( current.stepTitle );
	return acc;
}, [] );

/**
 * Find the intersection of multiple arrays.
 *
 * @param {Array[]} data A 2D array, list of arrays to match.
 * @return {Array} A single array of the values shared across the input.
 */
function intersect( data = [] ) {
	return data.reduce( ( a, b ) => a.filter( ( item ) => b.includes( item ) ), allTeams );
}

export default function Frame() {
	const [ i, setPage ] = useState( 0 );
	const [ selected, setSelected ] = useState( [] );
	const [ teams, setTeams ] = useState( allTeams );
	const pageRef = useRef( null );
	const onPrevious = () => {
		if ( pageRef.current ) {
			pageRef.current.tabIndex = -1;
			pageRef.current.focus();
		}
		setPage( i - 1 );
	};
	const onNext = () => {
		if ( pageRef.current ) {
			pageRef.current.tabIndex = -1;
			pageRef.current.focus();
		}
		setPage( i + 1 );
	};

	// When the selected value is updated, use that step-by-step to get a list of teams.
	useEffect( () => {
		const teamList = [];
		// Update the team list up to the current step.
		for ( let index = 0; index <= i; index++ ) {
			const list = selected.filter( ( result ) => result.startsWith( `q${ index }` ) );
			if ( list.length ) {
				const _teams = [ ...new Set( list.join( ',' ).replace( /q\d:/g, '' ).split( ',' ) ) ];
				teamList.push( _teams );
			}
		}
		setTeams( intersect( teamList ) );
	}, [ selected, i ] );

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
						<strong style={ { display: 'block' } }>{ `Step ${ key + 1 }: ` }</strong>
						{ step }
					</li>
				) ) }
			</ul>
			<div className="wporg-contributor-orientation--page" ref={ pageRef }>
				{ ! isLastPage ? (
					<QuestionStep
						{ ...page }
						teamList={ teams }
						value={ selected }
						onChange={ ( value ) => {
							setSelected( value );
						} }
						onPrevious={ onPrevious }
						onNext={ onNext }
					/>
				) : (
					<DoneStep { ...page } value={ teams } onPrevious={ onPrevious } />
				) }
			</div>
		</div>
	);
}
