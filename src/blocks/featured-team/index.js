/**
 * Featured Team Block Variation
 *
 * Globally registered query loop variation for featured team members.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';

wp.domReady(() => {
    wp.blocks.registerBlockVariation('core/group', {
        name: 'lsx-tour-operator/featured-team',
        title: __('Featured Team', 'to-team'),
        icon: 'admin-users',
        category: 'lsx-tour-operator',
        description: __('Display a featured team member query.', 'to-team'),
        keywords: [__('featured', 'to-team'), __('team', 'to-team'), __('query', 'to-team'), __('guide', 'to-team')],
        isActive: (blockAttributes, variationAttributes) => {
            return blockAttributes.className === variationAttributes.className;
        },
        attributes: {
            metadata: { name: __('Featured Team', 'to-team') },
            className: 'lsx-featured-team-query-wrapper',
            layout: { type: 'constrained' },
        },
        isDefault: false,
    });
});
