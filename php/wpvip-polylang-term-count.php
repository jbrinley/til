<?php

/**
 * In a WP VIP hosting environment, term counting is deferred.
 * This breaks Polylang's associations between terms and posts,
 * so that translations cannot be related to each other if the
 * site has more than two languages.
 *
 * This solution forces the term count to flush when a new post
 * is associated with a translation term.
 *
 * @param int    $object_id  Object ID.
 * @param array  $terms      An array of object terms.
 * @param array  $tt_ids     An array of term taxonomy IDs.
 * @param string $taxonomy   Taxonomy slug.
 *
 * @return void
 */
add_action( 'set_object_terms', function(  $object_id, $terms, $tt_ids, $taxonomy ) {
	if ( $taxonomy === 'post_translations' ) {
		wp_update_term_count( $tt_ids, $taxonomy, true );
	}
}, 10, 4 );
