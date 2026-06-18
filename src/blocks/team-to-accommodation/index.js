/**
 * Team to Accommodation Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamToAccommodationVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-to-accommodation',
            title: __('Team to Accommodation', 'to-team'),
            icon: 'admin-home',
            category: 'lsx-tour-operator',
            description: __('Displays the accommodations connected to this team member.', 'to-team'),
            keywords: [__('accommodation', 'to-team'), __('team', 'to-team'), __('connection', 'to-team'), __('lodging', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Team to Accommodation', 'to-team') },
                className: 'lsx-team-to-accommodation-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                    [['lsx-tour-operator/icons', { iconType: 'solid', iconName: 'accommodationIcon' }]],
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
                                        content: { source: 'lsx/post-connection', args: { key: 'accommodation_to_team' } },
                                    },
                                },
                                prefix: __('Accommodation:', 'to-team'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'accommodationIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Accommodation: ', 'to-team') + '</strong>' + __('Safari Lodge', 'to-team') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['team'], ['team'], registerTeamToAccommodationVariation);
    conditionalRegister();
});
