/**
 * Team Social Links Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamSocialLinksVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
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
                className: 'lsx-social-links-wrapper',
                layout: { type: 'flex', flexWrap: 'wrap', verticalAlignment: 'middle' },
            },
            innerBlocks: [
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: { source: 'lsx/post-meta', args: { key: 'facebook' } },
                            },
                        },
                        prefix: __('Facebook:', 'to-team'),
                        prefixBold: true,
                    },
                ],
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: { source: 'lsx/post-meta', args: { key: 'twitter' } },
                            },
                        },
                        prefix: __('Twitter:', 'to-team'),
                        prefixBold: true,
                    },
                ],
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: { source: 'lsx/post-meta', args: { key: 'linkedin' } },
                            },
                        },
                        prefix: __('LinkedIn:', 'to-team'),
                        prefixBold: true,
                    },
                ],
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: { source: 'lsx/post-meta', args: { key: 'pinterest' } },
                            },
                        },
                        prefix: __('Pinterest:', 'to-team'),
                        prefixBold: true,
                    },
                ],
                [
                    'core/paragraph',
                    {
                        metadata: {
                            bindings: {
                                content: { source: 'lsx/post-meta', args: { key: 'skype' } },
                            },
                        },
                        prefix: __('Skype:', 'to-team'),
                        prefixBold: true,
                    },
                ],
            ],
            example: {
                innerBlocks: [
                    { name: 'core/paragraph', attributes: { content: '<strong>' + __('Facebook: ', 'to-team') + '</strong>' + __('https://facebook.com/guide', 'to-team') } },
                    { name: 'core/paragraph', attributes: { content: '<strong>' + __('Twitter: ', 'to-team') + '</strong>' + __('@safaiguide', 'to-team') } },
                    { name: 'core/paragraph', attributes: { content: '<strong>' + __('LinkedIn: ', 'to-team') + '</strong>' + __('https://linkedin.com/in/guide', 'to-team') } },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['team'], ['team'], registerTeamSocialLinksVariation);
    conditionalRegister();
});
