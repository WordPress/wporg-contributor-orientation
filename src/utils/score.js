/**
 * Rank teams against the tags of every chosen answer.
 *
 * A tag counts once for each chosen answer that carries it, and each count is
 * multiplied by the team's weight for that tag. Teams that score nothing are
 * left out, which keeps teams without weights out of the results. Teams with
 * equal scores keep their order from the team list.
 *
 * @param {string[]} selectedTags Tags from every chosen answer, duplicates kept.
 * @param {Object}   teams        Teams keyed by slug, each with a `tags` map of `{ tag: weight }`.
 * @return {Object[]} Teams with a score, best first, each with `slug` and `score` added.
 */
export function rankTeams( selectedTags, teams ) {
	const counts = {};
	selectedTags.forEach( ( tag ) => {
		counts[ tag ] = ( counts[ tag ] || 0 ) + 1;
	} );

	return Object.keys( teams )
		.map( ( slug ) => {
			const weights = teams[ slug ].tags || {};
			const score = Object.keys( counts ).reduce(
				( sum, tag ) => sum + ( weights[ tag ] || 0 ) * counts[ tag ],
				0
			);
			return { ...teams[ slug ], slug, score };
		} )
		.filter( ( team ) => team.score > 0 )
		.sort( ( a, b ) => b.score - a.score );
}
