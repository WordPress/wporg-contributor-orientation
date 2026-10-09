<?php
/**
 * Plugin Name: Contributor Orientation Block
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
 * Each page asks one question. Every answer carries tags, and each team weighs
 * those tags (see get_teams()), so the chosen answers add up to a score per team.
 * Checkbox questions accept up to three answers, radio questions accept one.
 *
 * @return array Nested array of pages with headings, answer type, and answers.
 */
function get_pages() {
	return array(
		array(
			'step'      => 1,
			'stepTitle' => esc_html__( 'Your Interests', 'wporg' ),
			'headline'  => esc_html__( 'Which of these activities sound most exciting to you?', 'wporg' ),
			'type'      => 'checkbox',
			'questions' => array(
				array(
					'label' => esc_html__( 'Writing or reviewing code', 'wporg' ),
					'tags'  => array( 'code', 'php', 'javascript', 'dev' ),
				),
				array(
					'label' => esc_html__( 'Designing interfaces and user experiences', 'wporg' ),
					'tags'  => array( 'design', 'ux', 'ui' ),
				),
				array(
					'label' => esc_html__( 'Writing documentation, tutorials, or guides', 'wporg' ),
					'tags'  => array( 'writing', 'docs', 'education' ),
				),
				array(
					'label' => esc_html__( 'Helping people solve problems', 'wporg' ),
					'tags'  => array( 'support', 'community', 'education' ),
				),
				array(
					'label' => esc_html__( 'Making software available in other languages', 'wporg' ),
					'tags'  => array( 'translation', 'i18n', 'community' ),
				),
				array(
					'label' => esc_html__( 'Creating or curating multimedia content', 'wporg' ),
					'tags'  => array( 'media', 'video', 'photos' ),
				),
				array(
					'label' => esc_html__( 'Organizing events and growing communities', 'wporg' ),
					'tags'  => array( 'community', 'events', 'outreach' ),
				),
			),
		),
		array(
			'step'      => 2,
			'stepTitle' => esc_html__( 'Your Skills', 'wporg' ),
			'headline'  => esc_html__( 'Which of these best describe your strongest skills?', 'wporg' ),
			'type'      => 'checkbox',
			'questions' => array(
				array(
					'label' => esc_html__( 'PHP or back-end development', 'wporg' ),
					'tags'  => array( 'php', 'code', 'dev', 'backend' ),
				),
				array(
					'label' => esc_html__( 'JavaScript or front-end development', 'wporg' ),
					'tags'  => array( 'javascript', 'code', 'dev', 'frontend' ),
				),
				array(
					'label' => esc_html__( 'Mobile development (iOS or Android)', 'wporg' ),
					'tags'  => array( 'mobile', 'swift', 'kotlin', 'dev' ),
				),
				array(
					'label' => esc_html__( 'DevOps, infrastructure, or server management', 'wporg' ),
					'tags'  => array( 'devops', 'hosting', 'infrastructure' ),
				),
				array(
					'label' => esc_html__( 'Graphic design or UI/UX', 'wporg' ),
					'tags'  => array( 'design', 'ux', 'ui' ),
				),
				array(
					'label' => esc_html__( 'Writing or content creation', 'wporg' ),
					'tags'  => array( 'writing', 'docs', 'education' ),
				),
				array(
					'label' => esc_html__( 'Speaking or writing in multiple languages', 'wporg' ),
					'tags'  => array( 'translation', 'i18n' ),
				),
				array(
					'label' => esc_html__( 'QA, testing, or bug reporting', 'wporg' ),
					'tags'  => array( 'testing', 'qa', 'bugs' ),
				),
				array(
					'label' => esc_html__( 'Event organization or community management', 'wporg' ),
					'tags'  => array( 'community', 'events', 'outreach' ),
				),
				array(
					'label' => esc_html__( 'Accessibility or inclusive design', 'wporg' ),
					'tags'  => array( 'accessibility', 'a11y', 'design' ),
				),
				array(
					'label' => esc_html__( 'Command-line tooling or developer experience', 'wporg' ),
					'tags'  => array( 'cli', 'devtools', 'dev' ),
				),
				array(
					'label' => esc_html__( 'Performance optimization', 'wporg' ),
					'tags'  => array( 'performance', 'code', 'dev' ),
				),
			),
		),
		array(
			'step'      => 3,
			'stepTitle' => esc_html__( 'Your WordPress', 'wporg' ),
			'headline'  => esc_html__( 'How would you describe your experience with WordPress?', 'wporg' ),
			'type'      => 'radio',
			'questions' => array(
				array(
					'label' => esc_html__( 'Brand new: I am still learning the basics', 'wporg' ),
					'tags'  => array( 'beginner', 'support', 'education' ),
				),
				array(
					'label' => esc_html__( 'Experienced user: I build sites but rarely write code', 'wporg' ),
					'tags'  => array( 'intermediate', 'support', 'community', 'docs' ),
				),
				array(
					'label' => esc_html__( 'Developer: I write plugins or themes regularly', 'wporg' ),
					'tags'  => array( 'advanced', 'code', 'dev', 'php' ),
				),
				array(
					'label' => esc_html__( 'Core contributor: I already contribute to WordPress', 'wporg' ),
					'tags'  => array( 'expert', 'code', 'dev', 'meta' ),
				),
			),
		),
		array(
			'step'      => 4,
			'stepTitle' => esc_html__( 'Your Goals', 'wporg' ),
			'headline'  => esc_html__( 'Which outcomes matter most to you?', 'wporg' ),
			'type'      => 'checkbox',
			'questions' => array(
				array(
					'label' => esc_html__( 'Improving the quality and reliability of WordPress', 'wporg' ),
					'tags'  => array( 'testing', 'qa', 'performance', 'code' ),
				),
				array(
					'label' => esc_html__( 'Making WordPress accessible to everyone, everywhere', 'wporg' ),
					'tags'  => array( 'accessibility', 'translation', 'i18n', 'community' ),
				),
				array(
					'label' => esc_html__( 'Helping people learn and grow with WordPress', 'wporg' ),
					'tags'  => array( 'education', 'docs', 'training', 'support' ),
				),
				array(
					'label' => esc_html__( 'Building powerful new features and tools', 'wporg' ),
					'tags'  => array( 'code', 'dev', 'php', 'javascript' ),
				),
				array(
					'label' => esc_html__( 'Making WordPress more beautiful and easier to use', 'wporg' ),
					'tags'  => array( 'design', 'ux', 'themes' ),
				),
				array(
					'label' => esc_html__( 'Strengthening the plugin and theme ecosystem', 'wporg' ),
					'tags'  => array( 'plugins', 'themes', 'code' ),
				),
			),
		),
		array(
			'step'      => 5,
			'stepTitle' => esc_html__( 'Your Style', 'wporg' ),
			'headline'  => esc_html__( 'How would you prefer to contribute?', 'wporg' ),
			'type'      => 'radio',
			'questions' => array(
				array(
					'label' => esc_html__( 'Independently, on my own schedule', 'wporg' ),
					'tags'  => array( 'async', 'docs', 'translation', 'photos' ),
				),
				array(
					'label' => esc_html__( 'In live team meetings and real-time collaboration', 'wporg' ),
					'tags'  => array( 'sync', 'community', 'events', 'support' ),
				),
				array(
					'label' => esc_html__( 'A mix of both', 'wporg' ),
					'tags'  => array( 'async', 'sync', 'code', 'dev' ),
				),
			),
		),
		array(
			'step'      => 6,
			'stepTitle' => esc_html__( 'Your Teams', 'wporg' ),
			'headline'  => esc_html__( 'Based on your answers, you might enjoy contributing to the Make WordPress teams listed below.', 'wporg' ),
			'questions' => false,
		),
	);
}

/**
 * Get the teams for the orientation tool.
 *
 * @return array Associative array of teams with metadata: name, description, icon, URL,
 *               and tag weights (tag => weight) used to score answers.
 */
function get_teams() {
	return array(
		'support' => array(
			'name'          => esc_html__( 'Get started with the Support Team', 'wporg' ),
			'description'   => esc_html__( 'The Support Team facilitates user-generated support through the WordPress support forums. Support is one of the easiest ways to start contributing.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M11 6h-.82C9.07 6 8 7.2 8 8.16V10l-3 3v-3H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2v3zm0 1h6c1.1 0 2 .9 2 2v5c0 1.1-.9 2-2 2h-2v3l-3-3h-1c-1.1 0-2-.9-2-2V9c0-1.1.9-2 2-2z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/support/handbook/getting-started/',
			'tags'          => array(
				'support'      => 5,
				'community'    => 4,
				'beginner'     => 3,
				'intermediate' => 3,
				'education'    => 2,
				'sync'         => 3,
				'async'        => 3,
			),
		),
		'community' => array(
			'name'          => esc_html__( 'Get started with the Community Team', 'wporg' ),
			'description'   => esc_html__( 'The Community Team helps develop the global WordPress community by supporting events, contributors, and the general WordPress community. If you’re interested in organizing a meetup or a WordCamp, the Community Team is a great place to get started.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M8.03 4.46c-.29 1.28.55 3.46 1.97 3.46 1.41 0 2.25-2.18 1.96-3.46-.22-.98-1.08-1.63-1.96-1.63-.89 0-1.74.65-1.97 1.63zm-4.13.9c-.25 1.08.47 2.93 1.67 2.93s1.92-1.85 1.67-2.93c-.19-.83-.92-1.39-1.67-1.39s-1.48.56-1.67 1.39zm8.86 0c-.25 1.08.47 2.93 1.66 2.93 1.2 0 1.92-1.85 1.67-2.93-.19-.83-.92-1.39-1.67-1.39-.74 0-1.47.56-1.66 1.39zm-.59 11.43l1.25-4.3C14.2 10 12.71 8.47 10 8.47c-2.72 0-4.21 1.53-3.44 4.02l1.26 4.3C8.05 17.51 9 18 10 18c.98 0 1.94-.49 2.17-1.21zm-6.1-7.63c-.49.67-.96 1.83-.42 3.59l1.12 3.79c-.34.2-.77.31-1.2.31-.85 0-1.65-.41-1.85-1.03l-1.07-3.65c-.65-2.11.61-3.4 2.92-3.4.27 0 .54.02.79.06-.1.1-.2.22-.29.33zm8.35-.39c2.31 0 3.58 1.29 2.92 3.4l-1.07 3.65c-.2.62-1 1.03-1.85 1.03-.43 0-.86-.11-1.2-.31l1.11-3.77c.55-1.78.08-2.94-.42-3.61-.08-.11-.18-.23-.28-.33.25-.04.51-.06.79-.06z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/community/team-projects/',
			'tags'          => array(
				'community' => 5,
				'events'    => 5,
				'outreach'  => 5,
				'sync'      => 4,
			),
		),
		'polyglots' => array(
			'name'          => esc_html__( 'Get started with the Polyglots Team', 'wporg' ),
			'description'   => esc_html__( 'The Polyglots Team supports the work of translating WordPress into languages from all over the world. If you’re a polyglot, help out by translating WordPress into your own language. You can also assist with creating the tools that make translations easier.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M11 7H9.49c-.63 0-1.25.3-1.59.7L7 5H4.13l-2.39 7h1.69l.74-2H7v4H2c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h7c1.1 0 2 .9 2 2v2zM6.51 9H4.49l1-2.93zM10 8h7c1.1 0 2 .9 2 2v7c0 1.1-.9 2-2 2h-7c-1.1 0-2-.9-2-2v-7c0-1.1.9-2 2-2zm7.25 5v-1.08h-3.17V9.75h-1.16v2.17H9.75V13h1.28c.11.85.56 1.85 1.28 2.62-.87.36-1.89.62-2.31.62-.01.02.22.97.2 1.46.84 0 2.21-.5 3.28-1.15 1.09.65 2.48 1.15 3.34 1.15-.02-.49.2-1.44.2-1.46-.43 0-1.49-.27-2.38-.63.7-.77 1.14-1.77 1.25-2.61h1.36zm-3.81 1.93c-.5-.46-.85-1.13-1.01-1.93h2.09c-.17.8-.51 1.47-1 1.93l-.04.03s-.03-.02-.04-.03z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/polyglots/handbook/about/get-involved/',
			'tags'          => array(
				'translation' => 5,
				'i18n'        => 5,
				'community'   => 3,
				'async'       => 3,
			),
		),
		'training' => array(
			'name'          => esc_html__( 'Get started with the Training Team', 'wporg' ),
			'description'   => esc_html__( 'The Training Team creates educational resources that help people learn how to use, develop or contribute to WordPress through live webinars and self-paced learning courses, as well as downloadable lesson plans for instructors and speakers.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 10L2.54 7.02 3 18H1l.48-11.41L0 6l10-4 10 4zm0-5c-.55 0-1 .22-1 .5s.45.5 1 .5 1-.22 1-.5-.45-.5-1-.5zm0 6l5.57-2.23c.71.94 1.2 2.07 1.36 3.3-.3-.04-.61-.07-.93-.07-2.55 0-4.78 1.37-6 3.41C8.78 13.37 6.55 12 4 12c-.32 0-.63.03-.93.07.16-1.23.65-2.36 1.36-3.3z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/training/handbook/getting-started/',
			'tags'          => array(
				'education' => 5,
				'writing'   => 4,
				'docs'      => 3,
				'training'  => 5,
				'beginner'  => 2,
				'async'     => 3,
			),
		),
		'tv' => array(
			'name'          => esc_html__( 'Get started with the TV Team', 'wporg' ),
			'description'   => esc_html__( 'The TV Team reviews and approves every video submitted to WordPress.tv. They also help WordCamps around the world with video post-production and are responsible for the captioning and subtitling of published videos. Reviewing videos is a great way to learn about WordPress and help the community: Experience is not required to get involved.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M12 13V7c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h7c1.1 0 2-.9 2-2zm1-2.5l6 4.5V5l-6 4.5v1z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/tv/handbook/video-editing/',
			'tags'          => array(
				'media'   => 5,
				'video'   => 5,
				'writing' => 2,
				'async'   => 4,
			),
		),
		'core' => array(
			'name'          => esc_html__( 'Get started with the Core Team', 'wporg' ),
			'description'   => esc_html__( 'The Core Team makes WordPress. Whether you’re a seasoned PHP developer or are just learning to code, we’d love to have you on board. You can write code, fix bugs, debate decisions, and help with development.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M9 6l-4 4 4 4-1 2-6-6 6-6zm2 8l4-4-4-4 1-2 6 6-6 6z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/core/handbook/tutorials/getting-started/',
			'tags'          => array(
				'code'       => 5,
				'php'        => 5,
				'javascript' => 4,
				'dev'        => 5,
				'backend'    => 4,
				'frontend'   => 3,
				'advanced'   => 2,
				'expert'     => 2,
				'async'      => 2,
				'sync'       => 2,
			),
		),
		'themes' => array(
			'name'          => esc_html__( 'Get started with the Themes Team', 'wporg' ),
			'description'   => esc_html__( 'The Theme Review Team reviews and approves every Theme submitted to the WordPress Theme repository. Reviewing Themes sharpens your own Theme development skills. You can help out and join the discussion on the blog.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M14.48 11.06L7.41 3.99l1.5-1.5c.5-.56 2.3-.47 3.51.32 1.21.8 1.43 1.28 2.91 2.1 1.18.64 2.45 1.26 4.45.85zm-.71.71L6.7 4.7 4.93 6.47c-.39.39-.39 1.02 0 1.41l1.06 1.06c.39.39.39 1.03 0 1.42-.6.6-1.43 1.11-2.21 1.69-.35.26-.7.53-1.01.84C1.43 14.23.4 16.08 1.4 17.07c.99 1 2.84-.03 4.18-1.36.31-.31.58-.66.85-1.02.57-.78 1.08-1.61 1.69-2.21.39-.39 1.02-.39 1.41 0l1.06 1.06c.39.39 1.02.39 1.41 0z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/themes/handbook/get-involved/',
			'tags'          => array(
				'themes' => 5,
				'design' => 4,
				'ui'     => 3,
				'php'    => 3,
				'code'   => 3,
			),
		),
		'documentation' => array(
			'name'          => esc_html__( 'Get started with the Documentation Team', 'wporg' ),
			'description'   => esc_html__( 'The Documentation Team is responsible for creating documentation about WordPress software and the processes that support it. The Docs Team is always looking for writers and editors, because good documentation lets people help themselves when they get stuck.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M6 15V2h10v13H6zm-1 1h8v2H3V5h2v11z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/docs/handbook/get-involved/getting-started-at-a-contributor-day/',
			'tags'          => array(
				'writing'   => 5,
				'docs'      => 5,
				'education' => 4,
				'async'     => 4,
				'beginner'  => 2,
			),
		),
		'design'        => array(
			'name'          => esc_html__( 'Get started with the Design Team', 'wporg' ),
			'description'   => esc_html__( 'The Design Team focuses on the designing and developing the Wordpress user interface as well as the official WordPress websites. It is a home for designers and UXers alike. There are regular discussions about mockups, design, and user testing.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M8.55 3.06c1.01.34-1.95 2.01-.1 3.13 1.04.63 3.31-2.22 4.45-2.86.97-.54 2.67-.65 3.53 1.23 1.09 2.38.14 8.57-3.79 11.06-3.97 2.5-8.97 1.23-10.7-2.66-2.01-4.53 3.12-11.09 6.61-9.9zm1.21 6.45c.73 1.64 4.7-.5 3.79-2.8-.59-1.49-4.48 1.25-3.79 2.8z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/design/handbook/get-involved/first-steps/',
			'tags'          => array(
				'design' => 5,
				'ux'     => 5,
				'ui'     => 5,
				'themes' => 2,
				'async'  => 2,
				'sync'   => 2,
			),
		),
		'mobile'        => array(
			'name'          => esc_html__( 'Get started with the Mobile Team', 'wporg' ),
			'description'   => esc_html__( 'The Mobile Team builds the WordPress iOS and Android apps and needs both technical contributors with Java, Objective-C, or Swift skills, and non-technical contributors, such as designers, UX experts, documentation writers, and testers to help create a positive user experience on every device.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M6 2h8c.55 0 1 .45 1 1v14c0 .55-.45 1-1 1H6c-.55 0-1-.45-1-1V3c0-.55.45-1 1-1zm7 12V4H7v10h6zM8 5h4l-4 5V5z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/mobile/',
			'tags'          => array(
				'mobile'     => 5,
				'swift'      => 5,
				'kotlin'     => 5,
				'javascript' => 3,
				'dev'        => 4,
				'code'       => 3,
				'frontend'   => 3,
			),
		),
		'accessibility' => array(
			'name'          => esc_html__( 'Get started with the Accessibility Team', 'wporg' ),
			'description'   => esc_html__( 'The Accessibility Team provides accessibility expertise across the project and works to ensure that WordPress core and all of WordPress’ resources are accessible.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 2.6c.83 0 1.5.67 1.5 1.5s-.67 1.51-1.5 1.51c-.82 0-1.5-.68-1.5-1.51s.68-1.5 1.5-1.5zM3.4 7.36c0-.65 6.6-.76 6.6-.76s6.6.11 6.6.76-4.47 1.4-4.47 1.4 1.69 8.14 1.06 8.38c-.62.24-3.19-5.19-3.19-5.19s-2.56 5.43-3.18 5.19c-.63-.24 1.06-8.38 1.06-8.38S3.4 8.01 3.4 7.36z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/accessibility/handbook/get-involved/',
			'tags'          => array(
				'accessibility' => 5,
				'a11y'          => 5,
				'design'        => 3,
				'testing'       => 3,
				'code'          => 2,
			),
		),
		'cli' => array(
			'name'          => esc_html__( 'Get started with the CLI Team', 'wporg' ),
			'description'   => esc_html__( 'The CLI Team develops, maintains, and documents WP-CLI, the official command line tool for interacting with and managing your WordPress sites.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/cli/handbook/contributing',
			'tags'          => array(
				'cli'      => 5,
				'devtools' => 5,
				'dev'      => 4,
				'php'      => 4,
				'code'     => 4,
				'advanced' => 3,
				'expert'   => 2,
			),
		),
		'photos' => array(
			'name'          => esc_html__( 'Get started with the Photos Team', 'wporg' ),
			'description'   => esc_html__( 'The Photo Directory Team moderates every photo submitted to the WordPress Photo Directory, maintains and improves the directory site itself, and provides resources and documentation to educate, encourage, and facilitate photo contributors.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 5V3H3v2h3Zm12 10V4H9L7 6H2v9h16Zm-4-5c0-1.66-1.34-3-3-3s-3 1.34-3 3 1.34 3 3 3 3-1.34 3-3Z" clip-rule="evenodd"/></svg>',
			'url'           => 'https://make.wordpress.org/photos/handbook/',
			'tags'          => array(
				'photos' => 5,
				'media'  => 4,
				'async'  => 4,
			),
		),
		'core-performance' => array(
			'name'          => esc_html__( 'Get started with the Core Performance Team', 'wporg' ),
			'description'   => esc_html__( 'The Core Performance Team is dedicated to monitoring, enhancing, and promoting performance in WordPress core and its surrounding ecosystem.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 94 94"><path d="M39.21 20.85H27.52a2.5 2.5 0 0 0-2.5 2.5v11.69a2.5 2.5 0 0 0 2.5 2.5h11.69a2.5 2.5 0 0 0 2.5-2.5V23.35a2.5 2.5 0 0 0-2.5-2.5zM41.71 58.96v11.69c0 .66-.26 1.3-.73 1.77s-1.11.73-1.77.73H27.52c-.66 0-1.3-.26-1.77-.73s-.73-1.11-.73-1.77V49.28c0-.4.1-.79.28-1.14.03-.06.07-.12.1-.18.21-.33.49-.61.83-.82L37.9 40.1a2.49 2.49 0 0 1 2.87.19c.26.21.47.46.63.75.16.29.26.61.29.94.02.11.02.22.02.34v16.64ZM68.98 30.23v16.84c0 .33-.06.65-.19.96-.13.3-.31.58-.54.81l-6.88 6.88c-.23.23-.51.42-.81.54-.3.13-.63.19-.96.19H46.45c-.66 0-1.3-.26-1.77-.73s-.73-1.11-.73-1.77V42.26c0-.66.26-1.3.73-1.77s1.11-.73 1.77-.73h13.08s1.11 0 1.11-1.11-1.11-1.11-1.11-1.11H46.45c-.66 0-1.3-.26-1.77-.73s-.73-1.11-.73-1.77V23.35c0-.66.26-1.3.73-1.77s1.11-.73 1.77-.73H59.6c.33 0 .65.06.96.19.3.13.58.31.81.54l6.88 6.88c.23.23.42.51.54.81.13.3.19.63.19.96Z"/></svg>',
			'url'           => 'https://make.wordpress.org/performance/handbook/get-involved/',
			'tags'          => array(
				'performance' => 5,
				'code'        => 4,
				'php'         => 4,
				'javascript'  => 3,
				'dev'         => 4,
			),
		),
		'test' => array(
			'name'          => esc_html__( 'Get started with the Test Team', 'wporg' ),
			'description'   => esc_html__( 'The Test Team works to improve WordPress quality and usability by advancing the development process through manual and automated testing. Whether through a browser, app, terminal, or code, the team focuses on testing across the entire WordPress ecosystem, including Core and Gutenberg, mobile apps and themes, and feature plugins and importers.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17 3c.55 0 1 .45 1 1v10c0 .55-.45 1-1 1H3c-.55 0-1-.45-1-1V4c0-.55.45-1 1-1h14Zm-1 6c-1.6-1.86-3.7-3-6-3S5.6 7.14 4 9c1.6 1.86 3.7 3 6 3s4.4-1.14 6-3Zm-4 0c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2Zm5 8h-3v-1H6v1H3v1h14v-1Z" clip-rule="evenodd"/></svg>',
			'url'           => 'https://make.wordpress.org/test/handbook/',
			'tags'          => array(
				'testing'      => 5,
				'qa'           => 5,
				'bugs'         => 5,
				'code'         => 2,
				'async'        => 3,
				'intermediate' => 2,
				'beginner'     => 2,
			),
		),
		'hosting' => array(
			'name'          => esc_html__( 'Get started with the Hosting Team', 'wporg' ),
			'description'   => esc_html__( 'The Hosting Team works to improve WordPress’ end-user experience across hosting environments through industry collaboration and user education.', 'wporg' ),
			'icon'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M14.9 9c0-.3.1-.6.1-1 0-2.2-1.8-4-4-4-1.6 0-2.9.9-3.6 2.2-.2-.1-.6-.2-.9-.2C5.1 6 4 7.1 4 8.5c0 .2 0 .4.1.5-1.8.3-3.1 1.7-3.1 3.5C1 14.4 2.6 16 4.5 16h10c1.9 0 3.5-1.6 3.5-3.5 0-1.8-1.3-3.3-3.1-3.5z"/></g></svg>',
			'url'           => 'https://make.wordpress.org/hosting/handbook/get-involved/',
			'tags'          => array(
				'hosting'        => 5,
				'infrastructure' => 5,
				'devops'         => 5,
				'backend'        => 3,
				'dev'            => 3,
			),
		),
		'openverse' => array(
			'name'        => esc_html__( 'Get started with the Openverse Team', 'wporg' ),
			'description' => esc_html__( 'The Openverse Team implements new features and new media types; maintains the public API and front-end search engine; and develops WordPress integrations to share Openverse with the entire WordPress community.', 'wporg' ),
			'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 42"><g xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path class="circle" d="M37.559 18.795c5.156 0 9.335-4.207 9.335-9.398 0-5.19-4.18-9.397-9.335-9.397-5.156 0-9.335 4.207-9.335 9.398 0 5.19 4.18 9.397 9.335 9.397zM1 9.398c0 5.17 4.172 9.397 9.335 9.397V0C5.172 0 1 4.2 1 9.398zM14.612 9.398c0 5.17 4.172 9.397 9.335 9.397V0c-5.137 0-9.335 4.2-9.335 9.398zM37.559 41.921c5.156 0 9.335-4.207 9.335-9.397s-4.18-9.398-9.335-9.398c-5.156 0-9.335 4.208-9.335 9.398 0 5.19 4.18 9.397 9.335 9.397zM14.612 32.524c0 5.171 4.172 9.397 9.335 9.397V23.153c-5.137 0-9.335 4.2-9.335 9.37zM1 32.602C1 37.8 5.172 42 10.335 42V23.231c-5.163 0-9.335 4.2-9.335 9.371z"/></g></svg>',
			'url'         => 'https://make.wordpress.org/openverse/handbook/new-contributor-guide/',
			'tags'        => array(
				'code'       => 4,
				'javascript' => 4,
				'media'      => 4,
				'photos'     => 3,
				'dev'        => 4,
				'frontend'   => 4,
			),
		),

		/**
		 * Note: The Plugins, Meta, and Tide teams are intentionally not currently
		 * displaying as suggested teams because they do not have sufficient new
		 * contributor onboarding processes. They have no tag weights, so they never
		 * score, and teams without a score are left out of the results.
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
