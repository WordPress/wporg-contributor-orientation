<?php
/**
 * Plugin Name: Contritbutor Orientation Block
 * Description: Orient new contributors.
 *
 * @package wporg
 */

namespace WordPressdotorg\Blocks\ContributorOrientation;

// Actions & filters.
add_action( 'init', __NAMESPACE__ . '\block_init' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 */
function block_init() {
	$block = register_block_type(
		__DIR__ . '/build',
		array(
			'render_callback' => __NAMESPACE__ . '\render',
		)
	);
	$data = sprintf(
		'const WPorgContribSetup = %s;',
		json_encode( get_pages() )
	);
	foreach ( $block->view_script_handles as $handle ) {
		wp_add_inline_script( $handle, $data, 'before' );
	}
}

/**
 * Render the block content.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the block markup.
 */
function render( $attributes, $content, $block ) {
	$wrapper_attributes = get_block_wrapper_attributes();

	return sprintf(
		'<div %1$s>%2$s</div>',
		$wrapper_attributes,
		$content
	);
}
