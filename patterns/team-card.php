<?php

/**
 * Team Card Pattern
 *
 * A card layout for displaying team members in query loops.
 * Includes featured photo, name, role, tagline, contact details and social links.
 *
 * @package    TO_Team
 * @subpackage Patterns
 * @since      2.2.0
 * @version    2.2.0
 */

// phpcs:ignoreFile PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage

return array(
	'title'         => __( 'Team Card', 'to-team' ),
	'description'   => __( 'A card layout for displaying team members in query loops with photo, role, tagline, contact details and social links.', 'to-team' ),
	'categories'    => array( 'lsx-tour-operator' ),
	'keywords'      => array(
		__( 'team', 'to-team' ),
		__( 'profile', 'to-team' ),
		__( 'social', 'to-team' ),
		__( 'staff', 'to-team' ),
		__( 'guide', 'to-team' ),
	),
	'postTypes'     => array( 'wp_template' ),
	'blockTypes'    => array( 'core/post-template' ),
	'templateTypes' => array( 'archive', 'single', 'archive-team', 'single-tour', 'single-accommodation', 'single-destination' ),
	'viewportWidth' => 400,
	'content'       => '<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Team Card', 'to-team' ) . '","categories":["lsx-tour-operator"],"patternName":"lsx-tour-operator/team-card"},"className":"overflow-hidden is-style-shadow-sm","style":{"spacing":{"blockGap":"var:preset|spacing|20"},"border":{"radius":"0.5rem"}},"layout":{"type":"default"},"ariaLabel":"' . esc_attr__( 'Team Card', 'to-team' ) . '"} -->
<div aria-label="' . esc_attr__( 'Team Card', 'to-team' ) . '" class="wp-block-group overflow-hidden is-style-shadow-sm" style="border-radius:0.5rem"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1/1","linkTarget":"_blank"} /-->

<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Content', 'to-team' ) . '"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Name', 'to-team' ) . '"},"className":"center-vertically","style":{"dimensions":{"minHeight":"3.75rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group center-vertically" style="min-height:3.75rem"><!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"elements":{"link":{":hover":{"color":{"text":"var:preset|color|primary-700"}}}}},"fontSize":"large"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Team - Tagline', 'to-team' ) . '"},"className":"lsx-tagline-wrapper","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"top"}} -->
<div class="wp-block-group lsx-tagline-wrapper"><!-- wp:paragraph {"align":"center","metadata":{"bindings":{"content":{"source":"lsx/post-meta","args":{"key":"tagline"}}}},"fontSize":"medium"} -->
<p class="has-text-align-center has-medium-font-size"></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Team Details', 'to-team' ) . '"},"className":"lsx-team-info","style":{"spacing":{"padding":{"left":"var:preset|spacing|20","right":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"},"blockGap":"var:preset|spacing|10"},"border":{"top":{"width":"1px"},"bottom":{"width":"1px"}}},"fontSize":"medium","layout":{"type":"default"},"ariaLabel":"' . esc_attr__( 'Team member details', 'to-team' ) . '"} -->
<div aria-label="' . esc_attr__( 'Team member details', 'to-team' ) . '" class="wp-block-group lsx-team-info has-medium-font-size" style="border-top-width:1px;border-bottom-width:1px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Team - Role', 'to-team' ) . '"},"className":"lsx-role-wrapper","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group lsx-role-wrapper"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"middle"}} -->
<div class="wp-block-group"><!-- wp:lsx-tour-operator/icons {"iconType":"solid","iconName":"teamIcon"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"lsx/post-meta","args":{"key":"role"}}}},"prefix":"' . esc_attr__( 'Role:', 'to-team' ) . '","prefixBold":true} -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Team - Contact Email', 'to-team' ) . '"},"className":"lsx-contact-email-wrapper","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group lsx-contact-email-wrapper"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"middle"}} -->
<div class="wp-block-group"><!-- wp:lsx-tour-operator/icons {"iconType":"solid","iconName":"emailIcon"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"lsx/post-meta","args":{"key":"contact_email"}}}},"prefix":"' . esc_attr__( 'Email:', 'to-team' ) . '","prefixBold":true} -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Team - Contact Number', 'to-team' ) . '"},"className":"lsx-contact-number-wrapper","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group lsx-contact-number-wrapper"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"middle"}} -->
<div class="wp-block-group"><!-- wp:lsx-tour-operator/icons {"iconType":"solid","iconName":"phoneIcon"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"lsx/post-meta","args":{"key":"contact_number"}}}},"prefix":"' . esc_attr__( 'Phone:', 'to-team' ) . '","prefixBold":true} -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);