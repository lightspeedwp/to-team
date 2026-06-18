/**
 * Team Related Team Block Variation
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamRelatedTeamVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-related-team',
            title: __('Team Related Team', 'to-team'),
            icon: 'admin-users',
            category: 'lsx-tour-operator',
            description: __('Display team members related to this team member.', 'to-team'),
            keywords: [__('team', 'to-team'), __('related', 'to-team'), __('query', 'to-team'), __('guide', 'to-team')],
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
            attributes: {
                metadata: { name: __('Team Related Team', 'to-team') },
                className: 'lsx-team-related-team-query-wrapper',
                layout: { type: 'constrained' },
            },
            isDefault: false,
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['team'], ['team'], registerTeamRelatedTeamVariation);
    conditionalRegister();
});
