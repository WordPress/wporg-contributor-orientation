<?php
/**
 * Plugin Name: Contritbutor Orientation Block
 * Description: Orient new contributors.
 *
 * @package wporg
 */

namespace WordPressdotorg\Blocks\ContributorOrientation;

use WP_Block_Type_Registry;

// Actions & filters.
add_action( 'init', __NAMESPACE__ . '\block_init' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\inject_style_dep', 20 );

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
		'const WPorgContribBlock = %s;',
		json_encode(
			array(
				'pages' => get_pages(),
				'teams' => get_teams(),
			)
		)
	);
	foreach ( $block->view_script_handles as $handle ) {
		wp_add_inline_script( $handle, $data, 'before' );
	}
}

/**
 * Add the `wp-components` dependency to the style.
 */
function inject_style_dep() {
	global $wp_styles;
	$block = WP_Block_Type_Registry::get_instance()->get_registered( 'wporg/contributor-orientation' );

	foreach ( $block->style_handles as $handle ) {
		$style = $wp_styles->query( $handle, 'registered' );

		if ( ! $style ) {
			return false;
		}

		if ( ! in_array( 'wp-components', $style->deps ) ) {
			$style->deps[] = 'wp-components';
		}
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

/**
 * Get the pages for the orientation tool.
 *
 * @return array Nested array of pages with headings, questions.
 */
function get_pages() {
	return array(
		array(
			'step'      => 1,
			'stepTitle' => esc_html__( 'Your WordPress', 'wporg' ),
			'headline'  => esc_html__( 'How do you currently use WordPress?', 'wporg' ),
			'questions' => array(
				array(
					'label' => esc_html__( 'I work with code, such as creating or customizing WordPress plugins or themes', 'wporg' ),
					'teams' => array(
						'accessibility',
						'cli',
						'community',
						'core',
						'core-performance',
						'documentation',
						'hosting',
						'mobile',
						'support',
						'themes',
						'test',
						'training',
					),
				),
				array(
					'label' => esc_html__( 'I design WordPress websites, layouts, graphics, interfaces, or experiences.', 'wporg' ),
					'teams' => array(
						'accessibility',
						'design',
						'marketing',
						'mobile',
						'openverse',
						'photos',
						'themes',
						'tv',
						'test',
					),
				),
				array(
					'label' => esc_html__( 'I build, customize, manage, or work with WordPress for myself or clients', 'wporg' ),
					'teams' => array(
						'accessibility',
						'documentation',
						'hosting',
						'marketing',
						'mobile',
						'polyglots',
						'support',
						'sustainability',
						'themes',
						'training',
						'tv',
					),
				),
				array(
					'label' => esc_html__( 'I manage or publish content using WordPress', 'wporg' ),
					'teams' => array(
						'accessibility',
						'community',
						'documentation',
						'openverse',
						'photos',
						'polyglots',
						'support',
						'sustainability',
						'tv',
					),
				),
			),
		),
		array(
			'step'      => 2,
			'stepTitle' => esc_html__( 'Your Interests', 'wporg' ),
			'headline'  => esc_html__( 'Select any of the following areas that spark your interest:', 'wporg' ),
			'questions' => array(
				array(
					'label' => esc_html__( 'Developing websites, backend systems, programming-based solutions', 'wporg' ),
					'teams' => array(
						'accessibility',
						'documentation',
						'hosting',
						'marketing',
						'mobile',
						'polyglots',
						'support',
						'sustainability',
						'themes',
						'training',
						'tv',

					),
				),
				array(
					'label' => esc_html__( 'Making complex information digestible and easy to follow, ensuring information is understandable and accessible', 'wporg' ),
					'teams' => array(
						'accessibility',
						'core',
						'design',
						'documentation',
						'marketing',
						'polyglots',
						'training',
					),
				),
				array(
					'label' => esc_html__( 'Creating content, including blogs, photographs, or videos', 'wporg' ),
					'teams' => array(
						'accessibility',
						'documentation',
						'marketing',
						'mobile',
						'openverse',
						'photos',
						'polyglots',
						'support',
						'training',
						'tv',
					),
				),
				array(
					'label' => esc_html__( 'Promoting WordPress and open source, working across teams', 'wporg' ),
					'teams' => array(
						'community',
						'hosting',
						'marketing',
						'mobile',
						'openverse',
						'sustainability',
					),
				),
				array(
					'label' => esc_html__( 'Writing tests, supporting codebases', 'wporg' ),
					'teams' => array(
						'accessibility',
						'cli',
						'core',
						'core-performance',
						'documentation',
						'hosting',
						'mobile',
						'test',
					),
				),
				array(
					'label' => esc_html__( 'Assisting others in learning, mentoring', 'wporg' ),
					'teams' => array(
						'accessibility',
						'community',
						'support',
						'sustainability',
						'training',
					),
				),
				array(
					'label' => esc_html__( 'Creating graphics, designing patterns or layouts, editing videos', 'wporg' ),
					'teams' => array(
						'design',
						'marketing',
						'mobile',
						'openverse',
						'photos',
						'training',
						'tv',

					),
				),
				array(
					'label' => esc_html__( 'Developing, designing, or using mobile apps', 'wporg' ),
					'teams' => array(
						'accessibility',
						'core-performance',
						'design',
						'documentation',
						'marketing',
						'mobile',
						'polyglots',
						'support',
						'test',
					),
				),
				array(
					'label' => esc_html__( 'Finding bugs, conducting tests, reporting issues, providing patches', 'wporg' ),
					'teams' => array(
						'accessibility',
						'cli',
						'core',
						'core-performance',
						'documentation',
						'hosting',
						'mobile',
						'polyglots',
						'support',
						'test',
					),
				),
				array(
					'label' => esc_html__( 'Organizing events, working with people', 'wporg' ),
					'teams' => array(
						'community',
						'marketing',
						'support',
						'sustainability',
					),
				),
				array(
					'label' => esc_html__( 'Translating content into your language', 'wporg' ),
					'teams' => array(
						'accessibility',
						'core',
						'documentation',
						'mobile',
						'polyglots',
					),
				),
				array(
					'label' => esc_html__( 'Advocating for accessibility, upholding web standards', 'wporg' ),
					'teams' => array(
						'accessibility',
						'documentation',
						'community',
						'test',
						'sustainability',
					),
				),
			),
		),
		array(
			'step'      => 3,
			'stepTitle' => esc_html__( 'Your Skills', 'wporg' ),
			'headline'  => esc_html__( 'Are you skilled in any of the following areas?', 'wporg' ),
			'questions' => array(
				array(
					'label' => esc_html__( 'Software development, bug fixing, developer documentation', 'wporg' ),
					'teams' => array(
						'accessibility',
						'cli',
						'core',
						'core-performance',
						'documentation',
						'hosting',
						'mobile',
						'test',
						'themes',
					),
				),
				array(
					'label' => esc_html__( 'User support and training, issue tracking and resolution', 'wporg' ),
					'teams' => array(
						'accessibility',
						'documentation',
						'mobile',
						'support',
						'test',
						'themes',
						'training',
					),
				),
				array(
					'label' => esc_html__( 'Event and group organization', 'wporg' ),
					'teams' => array(
						'community',
						'sustainability',
						'tv',
					),
				),
				array(
					'label' => esc_html__( 'Translation writing, bi- or multi-lingual proficiency', 'wporg' ),
					'teams' => array(
						'documentation',
						'mobile',
						'polyglots',
						'tv',
					),
				),
				array(
					'label' => esc_html__( 'Training and instruction, curriculum development, educational materials development', 'wporg' ),
					'teams' => array(
						'accessibility',
						'documentation',
						'mobile',
						'polyglots',
						'support',
						'sustainability',
						'training',
						'tv',
					),
				),
				array(
					'label' => esc_html__( 'Market research, strategic planning, branding, communications', 'wporg' ),
					'teams' => array(
						'community',
						'documentation',
						'marketing',
						'mobile',
						'sustainability',
					),
				),
				array(
					'label' => esc_html__( 'Photography, video production and editing', 'wporg' ),
					'teams' => array(
						'marketing',
						'openverse',
						'photos',
						'training',
						'tv',
					),
				),
				array(
					'label' => esc_html__( 'WordPress theme creation and customization', 'wporg' ),
					'teams' => array(
						'accessibility',
						'core',
						'core-performance',
						'design',
						'mobile',
						'test',
						'themes',
					),
				),
				array(
					'label' => esc_html__( 'WordPress plugin creation and customization', 'wporg' ),
					'teams' => array(
						'accessibility',
						'core',
						'core-performance',
						'mobile',
						'test',
					),
				),
				array(
					'label' => esc_html__( 'Technical writing, project documentation', 'wporg' ),
					'teams' => array(
						'accessibility',
						'documentation',
						'marketing',
						'mobile',
						'polyglots',

					),
				),
				array(
					'label' => esc_html__( 'Web design, UX/UI design, graphic design', 'wporg' ),
					'teams' => array(
						'design',
						'marketing',
						'mobile',
						'openverse',
						'photos',
						'themes',
					),
				),
				array(
					'label' => esc_html__( 'Mobile apps development, design, documentation or testing', 'wporg' ),
					'teams' => array(
						'accessibility',
						'design',
						'documentation',
						'mobile',
						'polyglots',
						'test',
					),
				),
				array(
					'label' => esc_html__( 'Semantic HTML, ARIA, CSS for accessibility, keyboard accessibility, assistive technologies, WCAG standards', 'wporg' ),
					'teams' => array(
						'accessibility',
						'core',
						'design',
						'documentation',
						'hosting',
						'mobile',
						'themes',
					),
				),
				array(
					'label' => esc_html__( 'Writing automated tests, problem-solving, debugging', 'wporg' ),
					'teams' => array(
						'accessibility',
						'cli',
						'core-performance',
						'hosting',
						'mobile',
						'test',
					),
				),
			),
		),
		array(
			'step'      => 4,
			'stepTitle' => esc_html__( 'Your Teams', 'wporg' ),
			'headline'  => esc_html__( 'Based on your answers, you might enjoy contributing to the Make WordPress teams listed below.', 'wporg' ),
			'questions' => false,
		),
	);
}

/**
 * Get the teams for the orientation tool.
 *
 * @return array Associative array of teams with metadata: name, description, icon, and URL.
 */
function get_teams() {
	return array(
		'support' => array(
			'name'          => esc_html__( 'Get started with the Support Team', 'wporg' ),
			'description'   => esc_html__( 'The Support Team facilitates user-generated support through the WordPress support forums. Support is one of the easiest ways to start contributing.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M11 6h-.82C9.07 6 8 7.2 8 8.16V10l-3 3v-3H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2v3zm0 1h6c1.1 0 2 .9 2 2v5c0 1.1-.9 2-2 2h-2v3l-3-3h-1c-1.1 0-2-.9-2-2V9c0-1.1.9-2 2-2z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/support/handbook/getting-started/',
		),
		'community' => array(
			'name'          => esc_html__( 'Get started with the Community Team', 'wporg' ),
			'description'   => esc_html__( 'The Community Team helps develop the global WordPress community by supporting events, contributors, and the general WordPress community. If you’re interested in organizing a meetup or a WordCamp, the Community Team is a great place to get started.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M8.03 4.46c-.29 1.28.55 3.46 1.97 3.46 1.41 0 2.25-2.18 1.96-3.46-.22-.98-1.08-1.63-1.96-1.63-.89 0-1.74.65-1.97 1.63zm-4.13.9c-.25 1.08.47 2.93 1.67 2.93s1.92-1.85 1.67-2.93c-.19-.83-.92-1.39-1.67-1.39s-1.48.56-1.67 1.39zm8.86 0c-.25 1.08.47 2.93 1.66 2.93 1.2 0 1.92-1.85 1.67-2.93-.19-.83-.92-1.39-1.67-1.39-.74 0-1.47.56-1.66 1.39zm-.59 11.43l1.25-4.3C14.2 10 12.71 8.47 10 8.47c-2.72 0-4.21 1.53-3.44 4.02l1.26 4.3C8.05 17.51 9 18 10 18c.98 0 1.94-.49 2.17-1.21zm-6.1-7.63c-.49.67-.96 1.83-.42 3.59l1.12 3.79c-.34.2-.77.31-1.2.31-.85 0-1.65-.41-1.85-1.03l-1.07-3.65c-.65-2.11.61-3.4 2.92-3.4.27 0 .54.02.79.06-.1.1-.2.22-.29.33zm8.35-.39c2.31 0 3.58 1.29 2.92 3.4l-1.07 3.65c-.2.62-1 1.03-1.85 1.03-.43 0-.86-.11-1.2-.31l1.11-3.77c.55-1.78.08-2.94-.42-3.61-.08-.11-.18-.23-.28-.33.25-.04.51-.06.79-.06z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/community/team-projects/',
		),
		'polyglots' => array(
			'name'          => esc_html__( 'Get started with the Polyglots Team', 'wporg' ),
			'description'   => esc_html__( 'The Polyglots Team supports the work of translating WordPress into languages from all over the world. If you’re a polyglot, help out by translating WordPress into your own language. You can also assist with creating the tools that make translations easier.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M11 7H9.49c-.63 0-1.25.3-1.59.7L7 5H4.13l-2.39 7h1.69l.74-2H7v4H2c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h7c1.1 0 2 .9 2 2v2zM6.51 9H4.49l1-2.93zM10 8h7c1.1 0 2 .9 2 2v7c0 1.1-.9 2-2 2h-7c-1.1 0-2-.9-2-2v-7c0-1.1.9-2 2-2zm7.25 5v-1.08h-3.17V9.75h-1.16v2.17H9.75V13h1.28c.11.85.56 1.85 1.28 2.62-.87.36-1.89.62-2.31.62-.01.02.22.97.2 1.46.84 0 2.21-.5 3.28-1.15 1.09.65 2.48 1.15 3.34 1.15-.02-.49.2-1.44.2-1.46-.43 0-1.49-.27-2.38-.63.7-.77 1.14-1.77 1.25-2.61h1.36zm-3.81 1.93c-.5-.46-.85-1.13-1.01-1.93h2.09c-.17.8-.51 1.47-1 1.93l-.04.03s-.03-.02-.04-.03z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/polyglots/handbook/about/get-involved/',
		),
		'training' => array(
			'name'          => esc_html__( 'Get started with the Training Team', 'wporg' ),
			'description'   => esc_html__( 'The Training Team creates educational resources that help people learn how to use, develop or contribute to WordPress through live webinars and self-paced learning courses, as well as downloadable lesson plans for instructors and speakers.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 10L2.54 7.02 3 18H1l.48-11.41L0 6l10-4 10 4zm0-5c-.55 0-1 .22-1 .5s.45.5 1 .5 1-.22 1-.5-.45-.5-1-.5zm0 6l5.57-2.23c.71.94 1.2 2.07 1.36 3.3-.3-.04-.61-.07-.93-.07-2.55 0-4.78 1.37-6 3.41C8.78 13.37 6.55 12 4 12c-.32 0-.63.03-.93.07.16-1.23.65-2.36 1.36-3.3z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/training/handbook/getting-started/',
		),
		'marketing' => array(
			'name'          => esc_html__( 'Get started with the Marketing Team', 'wporg' ),
			'description'   => esc_html__( 'The Marketing Team promotes WordPress to current and future users and contributors. We create and amplify campaigns to support the growth of the WordPress project and the WordPress community.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 1c7 0 9 2.91 9 6.5S17 14 10 14s-9-2.91-9-6.5S3 1 10 1zM5.5 9C6.33 9 7 8.33 7 7.5S6.33 6 5.5 6 4 6.67 4 7.5 4.67 9 5.5 9zM10 9c.83 0 1.5-.67 1.5-1.5S10.83 6 10 6s-1.5.67-1.5 1.5S9.17 9 10 9zm4.5 0c.83 0 1.5-.67 1.5-1.5S15.33 6 14.5 6 13 6.67 13 7.5 13.67 9 14.5 9zM6 14.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5zm-3 2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/marketing/handbook/getting-involved/',
		),
		'tv' => array(
			'name'          => esc_html__( 'Get started with the TV Team', 'wporg' ),
			'description'   => esc_html__( 'The TV Team reviews and approves every video submitted to WordPress.tv. They also help WordCamps around the world with video post-production and are responsible for the captioning and subtitling of published videos. Reviewing videos is a great way to learn about WordPress and help the community: Experience is not required to get involved.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M12 13V7c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h7c1.1 0 2-.9 2-2zm1-2.5l6 4.5V5l-6 4.5v1z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/tv/handbook/video-editing/',
		),
		'core' => array(
			'name'          => esc_html__( 'Get started with the Core Team', 'wporg' ),
			'description'   => esc_html__( 'The Core Team makes WordPress. Whether you’re a seasoned PHP developer or are just learning to code, we’d love to have you on board. You can write code, fix bugs, debate decisions, and help with development.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M9 6l-4 4 4 4-1 2-6-6 6-6zm2 8l4-4-4-4 1-2 6 6-6 6z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/core/handbook/tutorials/getting-started/',
		),
		'themes' => array(
			'name'          => esc_html__( 'Get started with the Themes Team', 'wporg' ),
			'description'   => esc_html__( 'The Theme Review Team reviews and approves every Theme submitted to the WordPress Theme repository. Reviewing Themes sharpens your own Theme development skills. You can help out and join the discussion on the blog.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M14.48 11.06L7.41 3.99l1.5-1.5c.5-.56 2.3-.47 3.51.32 1.21.8 1.43 1.28 2.91 2.1 1.18.64 2.45 1.26 4.45.85zm-.71.71L6.7 4.7 4.93 6.47c-.39.39-.39 1.02 0 1.41l1.06 1.06c.39.39.39 1.03 0 1.42-.6.6-1.43 1.11-2.21 1.69-.35.26-.7.53-1.01.84C1.43 14.23.4 16.08 1.4 17.07c.99 1 2.84-.03 4.18-1.36.31-.31.58-.66.85-1.02.57-.78 1.08-1.61 1.69-2.21.39-.39 1.02-.39 1.41 0l1.06 1.06c.39.39 1.02.39 1.41 0z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/themes/handbook/get-involved/',
		),
		'documentation' => array(
			'name'          => esc_html__( 'Get started with the Documentation Team', 'wporg' ),
			'description'   => esc_html__( 'The Documentation Team is responsible for creating documentation about WordPress software and the processes that support it. The Docs Team is always looking for writers and editors, because good documentation lets people help themselves when they get stuck.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M6 15V2h10v13H6zm-1 1h8v2H3V5h2v11z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/docs/handbook/get-involved/getting-started-at-a-contributor-day/',
		),
		'design'        => array(
			'name'          => esc_html__( 'Get started with the Design Team', 'wporg' ),
			'description'   => esc_html__( 'The Design Team focuses on the designing and developing the Wordpress user interface as well as the official WordPress websites. It is a home for designers and UXers alike. There are regular discussions about mockups, design, and user testing.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M8.55 3.06c1.01.34-1.95 2.01-.1 3.13 1.04.63 3.31-2.22 4.45-2.86.97-.54 2.67-.65 3.53 1.23 1.09 2.38.14 8.57-3.79 11.06-3.97 2.5-8.97 1.23-10.7-2.66-2.01-4.53 3.12-11.09 6.61-9.9zm1.21 6.45c.73 1.64 4.7-.5 3.79-2.8-.59-1.49-4.48 1.25-3.79 2.8z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/design/handbook/get-involved/first-steps/',
		),
		'mobile'        => array(
			'name'          => esc_html__( 'Get started with the Mobile Team', 'wporg' ),
			'description'   => esc_html__( 'The Mobile Team builds the WordPress iOS and Android apps and needs both technical contributors with Java, Objective-C, or Swift skills, and non-technical contributors, such as designers, UX experts, documentation writers, and testers to help create a positive user experience on every device.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M6 2h8c.55 0 1 .45 1 1v14c0 .55-.45 1-1 1H6c-.55 0-1-.45-1-1V3c0-.55.45-1 1-1zm7 12V4H7v10h6zM8 5h4l-4 5V5z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/mobile/',
		),
		'accessibility' => array(
			'name'          => esc_html__( 'Get started with the Accessibility Team', 'wporg' ),
			'description'   => esc_html__( 'The Accessibility Team provides accessibility expertise across the project and works to ensure that WordPress core and all of WordPress’ resources are accessible.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 2.6c.83 0 1.5.67 1.5 1.5s-.67 1.51-1.5 1.51c-.82 0-1.5-.68-1.5-1.51s.68-1.5 1.5-1.5zM3.4 7.36c0-.65 6.6-.76 6.6-.76s6.6.11 6.6.76-4.47 1.4-4.47 1.4 1.69 8.14 1.06 8.38c-.62.24-3.19-5.19-3.19-5.19s-2.56 5.43-3.18 5.19c-.63-.24 1.06-8.38 1.06-8.38S3.4 8.01 3.4 7.36z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/accessibility/handbook/get-involved/',
		),
		'cli' => array(
			'name'          => esc_html__( 'Get started with the CLI Team', 'wporg' ),
			'description'   => esc_html__( 'The CLI Team develops, maintains, and documents WP-CLI, the official command line tool for interacting with and managing your WordPress sites.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/cli/handbook/contributing',
		),
		'photos' => array(
			'name'          => esc_html__( 'Get started with the Photos Team', 'wporg' ),
			'description'   => esc_html__( 'The Photo Directory Team moderates every photo submitted to the WordPress Photo Directory, maintains and improves the directory site itself, and provides resources and documentation to educate, encourage, and facilitate photo contributors.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 5V3H3v2h3Zm12 10V4H9L7 6H2v9h16Zm-4-5c0-1.66-1.34-3-3-3s-3 1.34-3 3 1.34 3 3 3 3-1.34 3-3Z" clip-rule="evenodd"/></svg>',
			'url'           => 'https://make.wordpress.org/photos/handbook/',
		),
		'core-performance' => array(
			'name'          => esc_html__( 'Get started with the Core Performance Team', 'wporg' ),
			'description'   => esc_html__( 'The Core Performance Team is dedicated to monitoring, enhancing, and promoting performance in WordPress core and its surrounding ecosystem.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 94 94"><path d="M39.21 20.85H27.52a2.5 2.5 0 0 0-2.5 2.5v11.69a2.5 2.5 0 0 0 2.5 2.5h11.69a2.5 2.5 0 0 0 2.5-2.5V23.35a2.5 2.5 0 0 0-2.5-2.5zM41.71 58.96v11.69c0 .66-.26 1.3-.73 1.77s-1.11.73-1.77.73H27.52c-.66 0-1.3-.26-1.77-.73s-.73-1.11-.73-1.77V49.28c0-.4.1-.79.28-1.14.03-.06.07-.12.1-.18.21-.33.49-.61.83-.82L37.9 40.1a2.49 2.49 0 0 1 2.87.19c.26.21.47.46.63.75.16.29.26.61.29.94.02.11.02.22.02.34v16.64ZM68.98 30.23v16.84c0 .33-.06.65-.19.96-.13.3-.31.58-.54.81l-6.88 6.88c-.23.23-.51.42-.81.54-.3.13-.63.19-.96.19H46.45c-.66 0-1.3-.26-1.77-.73s-.73-1.11-.73-1.77V42.26c0-.66.26-1.3.73-1.77s1.11-.73 1.77-.73h13.08s1.11 0 1.11-1.11-1.11-1.11-1.11-1.11H46.45c-.66 0-1.3-.26-1.77-.73s-.73-1.11-.73-1.77V23.35c0-.66.26-1.3.73-1.77s1.11-.73 1.77-.73H59.6c.33 0 .65.06.96.19.3.13.58.31.81.54l6.88 6.88c.23.23.42.51.54.81.13.3.19.63.19.96Z"/></svg>',
			'url'           => 'https://make.wordpress.org/performance/handbook/get-involved/',
		),
		'sustainability' => array(
			'name'          => esc_html__( 'Get started with the Sustainability Team', 'wporg' ),
			'description'   => esc_html__( 'The Sustainability Team aims to embed sustainable practices into the WordPress community and its processes. The Sustainability Team focuses on ensuring longevity: socially, economically, and environmentally.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 0a9 9 0 1 0 .001 18.001A9 9 0 0 0 9 0ZM1.11 9.68h2.51c.04.91.167 1.814.38 2.7H1.84a7.864 7.864 0 0 1-.73-2.7Zm8.57-8.49v3.09l2.76-.01a8.568 8.568 0 0 0-.54-1.08 4.128 4.128 0 0 0-2.22-2Zm3.22 4.44c.232.883.37 1.788.41 2.7H9.68v-2.7h3.22ZM8.32 4.28V1.19A4.135 4.135 0 0 0 6.1 3.2a8.568 8.568 0 0 0-.54 1.08h2.76Zm0 1.35v2.7H4.7c.04-.912.178-1.817.41-2.7h3.21ZM1.11 8.32h2.51c.04-.91.167-1.814.38-2.7H1.84a7.864 7.864 0 0 0-.73 2.7ZM4.7 9.68h3.62v2.7H5.11a12.84 12.84 0 0 1-.41-2.7Zm3.63 7.09v-3.09l-2.76.01c.154.372.335.733.54 1.08a4.128 4.128 0 0 0 2.22 2Zm1.35 0v-3.04h2.76a8.568 8.568 0 0 1-.54 1.08 4.128 4.128 0 0 1-2.22 2v-.04Zm0-7.14v2.7h3.21c.232-.883.37-1.788.41-2.7H9.68Zm4.71 0h2.51a7.864 7.864 0 0 1-.73 2.7H14c.21-.87.337-1.757.38-2.65l.01-.05Zm-.39-4c.214.87.344 1.756.39 2.65l2.5.05a7.864 7.864 0 0 0-.73-2.7H14Zm1.35-1.35H13.6a8.922 8.922 0 0 0-1.39-2.52 8.017 8.017 0 0 1 3.14 2.52Zm-10.95 0c.324-.91.793-1.76 1.39-2.52a8.017 8.017 0 0 0-3.14 2.52H4.4Zm-1.76 9.48h.032a7.992 7.992 0 0 0 3.118 2.52 8.922 8.922 0 0 1-1.39-2.52H2.672l-.022-.03-.01.03Zm9.602 2.466-.042.054.01-.04.032-.014Zm0 0a7.992 7.992 0 0 0 3.108-2.466h-1.76a8.922 8.922 0 0 1-1.348 2.466Z" clip-rule="evenodd"/></svg>',
			'url'           => 'https://make.wordpress.org/sustainability/2023/06/08/welcome/',
		),
		'test' => array(
			'name'          => esc_html__( 'Get started with the Test Team', 'wporg' ),
			'description'   => esc_html__( 'The Test Team tests, documents, and reports on the WordPress user experience using every device available. Through continuous dogfooding and visual records, the Test Team understands not only what is wrong, but also what is right, while championing user experience.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17 3c.55 0 1 .45 1 1v10c0 .55-.45 1-1 1H3c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1h14Zm-1 6c-1.6-1.86-3.7-3-6-3S5.6 7.14 4 9c1.6 1.86 3.7 3 6 3s4.4-1.14 6-3Zm-4 0c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2Zm5 8h-3v-1H6v1H3v1h14v-1Z" clip-rule="evenodd"/></svg>',
			'url'           => 'https://make.wordpress.org/test/handbook/',
		),
		'hosting' => array(
			'name'          => esc_html__( 'Get started with the Hosting Team', 'wporg' ),
			'description'   => esc_html__( 'The Hosting Team works to improve WordPress’ end-user experience across hosting environments through industry collaboration and user education.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M14.9 9c0-.3.1-.6.1-1 0-2.2-1.8-4-4-4-1.6 0-2.9.9-3.6 2.2-.2-.1-.6-.2-.9-.2C5.1 6 4 7.1 4 8.5c0 .2 0 .4.1.5-1.8.3-3.1 1.7-3.1 3.5C1 14.4 2.6 16 4.5 16h10c1.9 0 3.5-1.6 3.5-3.5 0-1.8-1.3-3.3-3.1-3.5z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/hosting/handbook/get-involved/',
		),
		'openverse' => array(
			'name'        => esc_html__( 'Get started with the Openverse Team', 'wporg' ),
			'description' => esc_html__( 'The Openverse Team implements new features and new media types; maintains the public API and front-end search engine; and develops WordPress integrations to share Openverse with the entire WordPress community.', 'wporg' ),
			'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 42"><g xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path class="circle" d="M37.559 18.795c5.156 0 9.335-4.207 9.335-9.398 0-5.19-4.18-9.397-9.335-9.397-5.156 0-9.335 4.207-9.335 9.398 0 5.19 4.18 9.397 9.335 9.397zM1 9.398c0 5.17 4.172 9.397 9.335 9.397V0C5.172 0 1 4.2 1 9.398zM14.612 9.398c0 5.17 4.172 9.397 9.335 9.397V0c-5.137 0-9.335 4.2-9.335 9.398zM37.559 41.921c5.156 0 9.335-4.207 9.335-9.397s-4.18-9.398-9.335-9.398c-5.156 0-9.335 4.208-9.335 9.398 0 5.19 4.18 9.397 9.335 9.397zM14.612 32.524c0 5.171 4.172 9.397 9.335 9.397V23.153c-5.137 0-9.335 4.2-9.335 9.37zM1 32.602C1 37.8 5.172 42 10.335 42V23.231c-5.163 0-9.335 4.2-9.335 9.371z"/></g></svg>',
			'url'         => 'https://make.wordpress.org/openverse/handbook/new-contributor-guide/',
		),

		/**
		 * Note: The Plugins, Meta, and Tide teams are intentionally not currently
		 * displaying as suggested teams because they do not have sufficient new
		 * contributor onboarding processes.
		 */
		'plugins' => array(
			'name'          => esc_html__( 'Get started with the Plugins Team', 'wporg' ),
			'description'   => esc_html__( 'If you are a Plugin developer, subscribe to the Plugin review team blog to keep up with the latest updates, find resources, and learn about any issues around Plugin development.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M13.11 4.36L9.87 7.6 8 5.73l3.24-3.24c.35-.34 1.05-.2 1.56.32.52.51.66 1.21.31 1.55zm-8 1.77l.91-1.12 9.01 9.01-1.19.84c-.71.71-2.63 1.16-3.82 1.16H6.14L4.9 17.26c-.59.59-1.54.59-2.12 0-.59-.58-.59-1.53 0-2.12l1.24-1.24v-3.88c0-1.13.4-3.19 1.09-3.89zm7.26 3.97l3.24-3.24c.34-.35 1.04-.21 1.55.31.52.51.66 1.21.31 1.55l-3.24 3.25z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/plugins/handbook/',
		),
		'meta' => array(
			'name'          => esc_html__( 'Get started with the Meta Team', 'wporg' ),
			'description'   => esc_html__( 'The Meta Team makes WordPress.org, provides support, and builds tools for use by all the contributor groups. If you want to help make WordPress.org better, sign up for updates from the Meta blog.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M18 13h1c.55 0 1 .45 1 1.01v2.98c0 .56-.45 1.01-1 1.01h-4c-.55 0-1-.45-1-1.01v-2.98c0-.56.45-1.01 1-1.01h1v-2h-5v2h1c.55 0 1 .45 1 1.01v2.98c0 .56-.45 1.01-1 1.01H8c-.55 0-1-.45-1-1.01v-2.98c0-.56.45-1.01 1-1.01h1v-2H4v2h1c.55 0 1 .45 1 1.01v2.98C6 17.55 5.55 18 5 18H1c-.55 0-1-.45-1-1.01v-2.98C0 13.45.45 13 1 13h1v-2c0-1.1.9-2 2-2h5V7H8c-.55 0-1-.45-1-1.01V3.01C7 2.45 7.45 2 8 2h4c.55 0 1 .45 1 1.01v2.98C13 6.55 12.55 7 12 7h-1v2h5c1.1 0 2 .9 2 2v2z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/meta/handbook/about/contributor-day/',
		),
		'tide' => array(
			'name'          => esc_html__( 'Get started with the Tide Team', 'wporg' ),
			'description'   => esc_html__( 'Tide Team manages the Tide project, a series of automated tests run against every plugin and theme in the directory and displays PHP compatibility and test errors/warnings in the directory.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M17 7.2V3H3v7.1c2.6-.5 4.5-1.5 6.4-2.6.2-.2.4-.3.6-.5v3c-1.9 1.1-4 2.2-7 2.8V17h14V9.9c-2.6.5-4.4 1.5-6.2 2.6-.3.1-.5.3-.8.4V10c2-1.1 4-2.2 7-2.8z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/tide/feedback-support/',
		),
	);
}
