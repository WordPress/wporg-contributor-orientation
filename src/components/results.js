/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Internal dependencies
 */
import Buttons from './buttons';

function Team( { team, isTop = false } ) {
	const Heading = isTop ? 'h3' : 'h4';
	return (
		<div className={ `wporg-contributor-orientation--team${ isTop ? ' is-top' : '' }` }>
			{ isTop && (
				<p className="wporg-contributor-orientation--label">{ __( 'Your best match', 'wporg' ) }</p>
			) }
			<Heading className="wporg-contributor-orientation--team-name">
				<a href={ team.url }>
					<img alt="" src={ `data:image/svg+xml;utf8,${ team.icon }` } />
					{ decodeEntities( team.name ) }
				</a>
			</Heading>
			<p className="wporg-contributor-orientation--team-desc">{ decodeEntities( team.description ) }</p>
		</div>
	);
}

export default function Results( { headline, teams, onPrevious, onRestart } ) {
	const [ top, ...others ] = teams;
	return (
		<>
			<h2 className="wporg-contributor-orientation--title">{ decodeEntities( headline ) }</h2>
			{ !! top && <Team team={ top } isTop /> }
			{ others.length > 0 && (
				<>
					<h3 className="wporg-contributor-orientation--subtitle">
						{ __( 'More teams you might enjoy', 'wporg' ) }
					</h3>
					<div className="wporg-contributor-orientation--others">
						{ others.map( ( team ) => (
							<Team key={ team.slug } team={ team } />
						) ) }
					</div>
				</>
			) }
			<Buttons step={ -1 } onPrevious={ onPrevious } onRestart={ onRestart } />
		</>
	);
}
