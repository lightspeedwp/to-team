/**
 * Tour Related Team Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTourRelatedTeamVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/tour-related-team',
            title: __('Related Tour', 'to-team'),
            icon: 'admin-users',
            category: 'lsx-tour-operator',
            description: __('Display tours related to this team member.', 'to-team'),
            keywords: [__('team', 'to-team'), __('tour', 'to-team'), __('related', 'to-team'), __('query', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Related Tour', 'to-team') },
                className: 'lsx-tour-related-team-query-wrapper',
                layout: { type: 'constrained' },
            },
            isDefault: false,
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['tour'],
        ['tour'],
        registerTourRelatedTeamVariation
    );
    conditionalRegister();
});
