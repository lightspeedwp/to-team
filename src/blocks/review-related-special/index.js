/**
 * Review Related Special Block Variation
 *
 * Registers a block variation for displaying reviews related to the current special.
 * Only available on special post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerReviewRelatedSpecialVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/review-related-special',
            title: __('Related Reviews', 'to-team'),
            icon: 'tag',
            description: __('Display reviews related to this special.', 'to-team'),
            category: 'lsx-tour-operator',
            keywords: [
                __('review', 'to-team'),
                __('special', 'to-team'),
                __('related', 'to-team'),
                __('query', 'to-team'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Reviews', 'to-team'),
                },
                className: 'lsx-review-related-special-query-wrapper',
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
                                content: __('Related Reviews', 'to-team'),
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
                                    name: __('Related reviews query', 'to-team'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'review',
                                    order: 'desc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-review-related-special-query',
                                        layout: { type: 'grid', columnCount: 2 },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            { slug: 'lsx-tour-operator/review-card' },
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
                                    content: __('Related Reviews', 'to-team'),
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
                                    className: 'lsx-review-related-special-query',
                                    layout: { type: 'grid', columnCount: 2 },
                                },
                                innerBlocks: [
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'is-style-shadow-sm',
                                            style: { spacing: { blockGap: '0px', padding: { top: '0px', bottom: '0px', left: '0px', right: '0px' } }, border: { radius: '8px' } },
                                            backgroundColor: 'base',
                                            layout: { type: 'constrained' },
                                        },
                                        innerBlocks: [
                                            {
                                                name: 'core/group',
                                                attributes: { style: { spacing: { padding: { top: '5px', bottom: '0px', left: '5px', right: '5px' } } }, layout: { type: 'constrained' } },
                                                innerBlocks: [
                                                    { name: 'core/heading', attributes: { textAlign: 'center', content: __('Sarah Johnson', 'to-team'), level: 3, fontSize: 'small', style: { spacing: { margin: { top: '0', bottom: '0' } } } } },
                                                    { name: 'core/paragraph', attributes: { content: __('"This special offer made our dream safari affordable. Every detail was perfectly arranged."', 'to-team'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
                                                ],
                                            },
                                        ],
                                    },
                                    {
                                        name: 'core/group',
                                        attributes: {
                                            className: 'is-style-shadow-sm',
                                            style: { spacing: { blockGap: '0px', padding: { top: '0px', bottom: '0px', left: '0px', right: '0px' } }, border: { radius: '8px' } },
                                            backgroundColor: 'base',
                                            layout: { type: 'constrained' },
                                        },
                                        innerBlocks: [
                                            {
                                                name: 'core/group',
                                                attributes: { style: { spacing: { padding: { top: '5px', bottom: '0px', left: '5px', right: '5px' } } }, layout: { type: 'constrained' } },
                                                innerBlocks: [
                                                    { name: 'core/heading', attributes: { textAlign: 'center', content: __('Michael Chen', 'to-team'), level: 3, fontSize: 'small', style: { spacing: { margin: { top: '0', bottom: '0' } } } } },
                                                    { name: 'core/paragraph', attributes: { content: __('"Great value for money thanks to this special. Would book again in a heartbeat."', 'to-team'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
                                                ],
                                            },
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
        ['special'],
        ['special'],
        registerReviewRelatedSpecialVariation
    );
    conditionalRegister();
});
