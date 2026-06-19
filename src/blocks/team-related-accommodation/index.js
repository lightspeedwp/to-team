/**
 * Team Related Accommodation Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamRelatedAccommodationVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-related-accommodation',
            title: __('Related Team', 'to-team'),
            icon: 'admin-users',
            category: 'lsx-tour-operator',
            description: __('Display team members related to this accommodation.', 'to-team'),
            keywords: [__('team', 'to-team'), __('accommodation', 'to-team'), __('related', 'to-team'), __('query', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Related Team', 'to-team') },
                className: 'lsx-related-accommodation-query-wrapper',
                layout: { type: 'constrained' },
            },
            isDefault: false,
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['accommodation'],
        ['accommodation'],
        registerTeamRelatedAccommodationVariation
    );
    conditionalRegister();
});
