/**
 * Team Related Destination Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamRelatedDestinationVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-related-destination',
            title: __('Related Team', 'to-team'),
            icon: 'admin-users',
            category: 'lsx-tour-operator',
            description: __('Display team members related to this destination.', 'to-team'),
            keywords: [__('team', 'to-team'), __('destination', 'to-team'), __('related', 'to-team'), __('query', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Related Team', 'to-team') },
                className: 'lsx-team-related-destination-query-wrapper',
                layout: { type: 'constrained' },
            },
            isDefault: false,
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['destination'],
        ['destination', 'country', 'region'],
        registerTeamRelatedDestinationVariation
    );
    conditionalRegister();
});
