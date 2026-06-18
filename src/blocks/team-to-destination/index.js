/**
 * Team to Destination Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamToDestinationVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-to-destination',
            title: __('Team to Destination', 'to-team'),
            icon: 'admin-site',
            category: 'lsx-tour-operator',
            description: __('Displays the destinations connected to this team member.', 'to-team'),
            keywords: [__('destination', 'to-team'), __('team', 'to-team'), __('connection', 'to-team'), __('location', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Team to Destination', 'to-team') },
                className: 'lsx-team-to-destination-wrapper',
                layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'top' },
            },
            innerBlocks: [
                [
                    'core/group',
                    { layout: { type: 'flex', flexWrap: 'nowrap', verticalAlignment: 'middle' } },
                    [['lsx-tour-operator/icons', { iconType: 'solid', iconName: 'destinationIcon' }]],
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
                                        content: { source: 'lsx/post-connection', args: { key: 'destination_to_team' } },
                                    },
                                },
                                prefix: __('Destination:', 'to-team'),
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
                            { name: 'lsx-tour-operator/icons', attributes: { iconType: 'solid', iconName: 'destinationIcon' } },
                            { name: 'core/paragraph', attributes: { content: '<strong>' + __('Destination: ', 'to-team') + '</strong>' + __('Cape Town', 'to-team') } },
                        ],
                    },
                ],
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['team'], ['team'], registerTeamToDestinationVariation);
    conditionalRegister();
});
