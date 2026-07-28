/**
 * Post Related Review Block Variation
 *
 * Registers a block variation for displaying posts related to the current review.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerPostRelatedReviewVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/post-related-review',
            title: __('Related Posts', 'to-team'),
            icon: 'star-filled',
            description: __('Display post related to this review.', 'to-team'),
            category: 'lsx-tour-operator',
            keywords: [
                __('review', 'to-team'),
                __('post', 'to-team'),
                __('related', 'to-team'),
                __('query', 'to-team'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Posts', 'to-team'),
                },
                className: 'lsx-post-related-review-query-wrapper',
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
                                content: __('Related Posts', 'to-team'),
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
                                    name: __('Related post query', 'to-team'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'post',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-post-related-review-query',
                                        layout: { type: 'grid', columnCount: 3 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/post-card' },
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
                                    content: __('Related Posts', 'to-team'),
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
                                    className: 'lsx-post-related-review-query',
                                    layout: { type: 'grid', columnCount: 3 },
                                },
                                innerBlocks: [
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-post-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Top 10 Safari Tips', 'to-team'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('Everything you need to know before heading out on your first African safari.', 'to-team') } },
                                        ],
                                    },
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-post-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Best Time to Visit East Africa', 'to-team'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('A season-by-season guide to planning the perfect trip.', 'to-team') } },
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
        registerPostRelatedReviewVariation
    );
    conditionalRegister();
});
