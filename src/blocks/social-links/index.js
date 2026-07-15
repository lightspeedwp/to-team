/**
 * Team Social Links Block Variation
 *
 * A locked variation of core/social-links preloaded with the team member's
 * social profile fields. The URL of each icon is driven entirely by the
 * matching custom field on the front end (see LSX_TO_Team_Blocks::render_team_social_link()
 * in class-to-team-blocks.php), since core block bindings do not support the
 * `url` attribute of core/social-link.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

const WRAPPER_CLASS_NAME = 'lsx-team-social-links';

/**
 * One entry per field in LSX_TO_Team_Blocks::register_multi_field_wrappers().
 * `service` is the core/social-link icon; `metaKey` is the matching custom field
 * read by the PHP render filter.
 */
const SOCIAL_FIELDS = [
    { service: 'facebook', metaKey: 'facebook', label: __( 'Facebook', 'to-team' ) },
    { service: 'x', metaKey: 'twitter', label: __( 'Twitter / X', 'to-team' ) },
    { service: 'linkedin', metaKey: 'linkedin', label: __( 'LinkedIn', 'to-team' ) },
    { service: 'pinterest', metaKey: 'pinterest', label: __( 'Pinterest', 'to-team' ) },
    { service: 'skype', metaKey: 'skype', label: __( 'Skype', 'to-team' ) },
];

wp.domReady(() => {
    const registerTeamSocialLinksVariation = () => {
        wp.blocks.registerBlockVariation('core/social-links', {
            name: 'lsx-tour-operator/team-social-links',
            title: __('Team - Social Links', 'to-team'),
            icon: 'share',
            category: 'lsx-tour-operator',
            description: __('Display the social media links for this team member.', 'to-team'),
            keywords: [__('social', 'to-team'), __('team', 'to-team'), __('facebook', 'to-team'), __('twitter', 'to-team'), __('linkedin', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.metadata?.name === variationAttributes.metadata?.name;
            },
            attributes: {
                metadata: { name: __('Team - Social Links', 'to-team') },
                className: WRAPPER_CLASS_NAME,
                // Note: core/social-links hardcodes its own InnerBlocks
                // `templateLock` to `false` and ignores a `templateLock`
                // attribute (unlike core/group or core/columns), so it can't
                // be used here to block inserting extra icons. The "+"
                // appender is hidden via editor.scss instead, and each icon
                // below is individually locked against removal.
            },
            innerBlocks: SOCIAL_FIELDS.map(({ service, label }) => [
                'core/social-link',
                {
                    service,
                    label,
                    // Placeholder only - the real URL is injected server-side from
                    // the team member's custom fields.
                    url: '#',
                    lock: { remove: true, move: false },
                },
            ]),
            example: {
                innerBlocks: SOCIAL_FIELDS.map(({ service, label }) => ({
                    name: 'core/social-link',
                    attributes: { service, label, url: '#' },
                })),
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['team'], ['team'], registerTeamSocialLinksVariation);
    conditionalRegister();
});
