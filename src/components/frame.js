/* global WPorgContribBlock */
/**
 * WordPress dependencies
 */
import { useEffect, useState } from '@wordpress/element';

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

function intersect( data = [] ) {
	return data.reduce( ( a, b ) => a.filter( ( item ) => b.includes( item ) ), allTeams );
}

export default function Frame() {
	const [ i, setPage ] = useState( 0 );
	const [ selected, setSelected ] = useState( [] );
	const [ teams, setTeams ] = useState( allTeams );
	const prevPage = () => setPage( i - 1 );
	const nextPage = () => setPage( i + 1 );
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
					<li key={ key } className={ key === i ? 'is-current' : '' }>
						<strong style={ { display: 'block' } }>{ `Step ${ key + 1 }: ` }</strong>
						{ step }
					</li>
				) ) }
			</ul>
			<div className="wporg-contributor-orientation--page">
				{ ! isLastPage ? (
					<QuestionStep
						{ ...page }
						teamList={ teams }
						value={ selected }
						onChange={ ( value ) => {
							setSelected( value );
						} }
						prevPage={ prevPage }
						nextPage={ nextPage }
					/>
				) : (
					<DoneStep { ...page } value={ teams } />
				) }
			</div>
		</div>
	);
}
