/**
 * Team Related Review Block Variation
 *
 * Registers a block variation for displaying team members related to the current review.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamRelatedReviewVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/team-related-review',
            title: __('Related Team', 'to-team'),
            icon: 'star-filled',
            description: __('Display team related to this review.', 'to-team'),
            category: 'lsx-tour-operator',
            keywords: [
                __('review', 'to-team'),
                __('team', 'to-team'),
                __('related', 'to-team'),
                __('query', 'to-team'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Team', 'to-team'),
                },
                className: 'lsx-team-related-review-query-wrapper',
                align: 'full',
                layout: {
                    type: 'constrained',
                },
                tagName: 'section',
            },
            innerBlocks: [
                [
                    'core/group',
                    {
                        align: 'wide',
                        layout: { type: 'flex', flexWrap: 'nowrap' },
                    },
                    [
                        [
                            'core/separator',
                            { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                        ],
                        [
                            'core/heading',
                            {
                                textAlign: 'center',
                                content: __('Related Team', 'to-team'),
                                level: 2,
                            },
                        ],
                        [
                            'core/separator',
                            { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                        ],
                    ],
                ],
                [
                    'core/group',
                    { align: 'wide', layout: { type: 'constrained' } },
                    [
                        [
                            'core/query',
                            {
                                metadata: {
                                    name: __('Related team query', 'to-team'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'team',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-team-related-review-query',
                                        layout: { type: 'grid', columnCount: 3 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/team-card' },
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            example: {
                innerBlocks: [
                    {
                        name: 'core/group',
                        attributes: {
                            align: 'wide',
                            layout: { type: 'flex', flexWrap: 'nowrap' },
                        },
                        innerBlocks: [
                            {
                                name: 'core/separator',
                                attributes: { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                            },
                            {
                                name: 'core/heading',
                                attributes: {
                                    textAlign: 'center',
                                    content: __('Related Team', 'to-team'),
                                    level: 2,
                                },
                            },
                            {
                                name: 'core/separator',
                                attributes: { style: { layout: { selfStretch: 'fill', flexSize: null } } },
                            },
                        ],
                    },
                    {
                        name: 'core/group',
                        attributes: { align: 'wide', layout: { type: 'constrained' } },
                        innerBlocks: [
                            {
                                name: 'core/group',
                                attributes: {
                                    className: 'lsx-team-related-review-query',
                                    layout: { type: 'grid', columnCount: 3 },
                                },
                                innerBlocks: [
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-team-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Sarah Johnson', 'to-team'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('Experienced safari guide with over 10 years leading tours across East Africa.', 'to-team') } },
                                        ],
                                    },
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-team-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Michael Chen', 'to-team'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('Wildlife biologist turned tour guide, passionate about conservation.', 'to-team') } },
                                        ],
                                    },
                                ],
                            },
                        ],
                    },
                ],
            },
            isActive: (blockAttributes, variationAttributes) => {
                return blockAttributes.className === variationAttributes.className;
            },
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(
        ['review'],
        ['review'],
        registerTeamRelatedReviewVariation
    );
    conditionalRegister();
});
