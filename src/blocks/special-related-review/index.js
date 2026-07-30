/**
 * Special Related Review Block Variation
 *
 * Registers a block variation for displaying specials related to the current review.
 * Only available on review post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialRelatedReviewVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-related-review',
            title: __('Related Specials', 'to-team'),
            icon: 'star-filled',
            description: __('Display special related to this review.', 'to-team'),
            category: 'lsx-tour-operator',
            keywords: [
                __('review', 'to-team'),
                __('special', 'to-team'),
                __('related', 'to-team'),
                __('query', 'to-team'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Specials', 'to-team'),
                },
                className: 'lsx-special-related-review-query-wrapper',
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
                                content: __('Related Specials', 'to-team'),
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
                                    name: __('Related special query', 'to-team'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'special',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-special-related-review-query',
                                        layout: { type: 'grid', columnCount: 3 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/special-card' },
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
                                    content: __('Related Specials', 'to-team'),
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
                                    className: 'lsx-special-related-review-query',
                                    layout: { type: 'grid', columnCount: 3 },
                                },
                                innerBlocks: [
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-special-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Early Bird Safari Discount', 'to-team'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('Save 20% when you book your African safari at least three months in advance.', 'to-team') } },
                                        ],
                                    },
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'lsx-special-card',
                                            style: { border: { width: '1px', style: 'solid', color: '#e2e8f0' }, spacing: { padding: '1.5rem' } },
                                        },
                                        innerBlocks: [
                                            { name: 'core/heading', attributes: { content: __('Honeymoon Package Offer', 'to-team'), level: 3 } },
                                            { name: 'core/paragraph', attributes: { content: __('A romantic getaway package including a complimentary spa treatment and private dinner.', 'to-team') } },
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
        registerSpecialRelatedReviewVariation
    );
    conditionalRegister();
});
