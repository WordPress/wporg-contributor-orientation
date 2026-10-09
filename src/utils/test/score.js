/**
 * Internal dependencies
 */
import { rankTeams } from '../score';

const teams = {
	support: { name: 'Support', tags: { support: 5, community: 4 } },
	community: { name: 'Community', tags: { community: 5, events: 5 } },
	core: { name: 'Core', tags: { code: 5, php: 5 } },
	plugins: { name: 'Plugins' },
};

describe( 'rankTeams', () => {
	it( 'multiplies each weight by the number of answers carrying the tag', () => {
		const ranked = rankTeams( [ 'community', 'community', 'support' ], teams );
		expect( ranked.map( ( { slug, score } ) => [ slug, score ] ) ).toEqual( [
			[ 'support', 13 ],
			[ 'community', 10 ],
		] );
	} );

	it( 'leaves out teams that score nothing, including teams with no weights', () => {
		const ranked = rankTeams( [ 'code' ], teams );
		expect( ranked.map( ( { slug } ) => slug ) ).toEqual( [ 'core' ] );
	} );

	it( 'keeps the team order for equal scores', () => {
		const ranked = rankTeams( [ 'support', 'events' ], teams );
		expect( ranked.map( ( { slug, score } ) => [ slug, score ] ) ).toEqual( [
			[ 'support', 5 ],
			[ 'community', 5 ],
		] );
	} );

	it( 'returns the team data alongside the slug and score', () => {
		const [ top ] = rankTeams( [ 'php' ], teams );
		expect( top ).toEqual( {
			name: 'Core',
			tags: { code: 5, php: 5 },
			slug: 'core',
			score: 5,
		} );
	} );

	it( 'returns nothing when no answers were chosen', () => {
		expect( rankTeams( [], teams ) ).toEqual( [] );
	} );
} );
