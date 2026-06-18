/**
 * Team Contact Email Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamContactEmailVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-contact-email',
            title: __('Team Contact Email', 'to-team'),
            icon: 'email',
            category: 'lsx-tour-operator',
            description: __('Display the contact email for this team member.', 'to-team'),
            keywords: [__('email', 'to-team'), __('contact', 'to-team'), __('team', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Team Contact Email', 'to-team') },
                className: 'lsx-team-contact-email-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                    [['lsx-tour-operator/icons', { iconType: 'solid', iconName: 'emailIcon' }]],
                ],
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap' } },
                    [
                        [
                            'core/paragraph',
                            {
                                metadata: {
                                    bindings: {
                                        content: { source: 'lsx/post-meta', args: { key: 'contact_email' } },
                                    },
                                },
                                prefix: __('Email:', 'to-team'),
                                prefixBold: true,
                            },
                        ],
                    ],
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/group',
                        attributes: { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                        innerBlocks: [
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'emailIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Email: ', 'to-team') + '</strong>' + __('guide@safarico.com', 'to-team') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['team'], ['team'], registerTeamContactEmailVariation);
    conditionalRegister();
});
