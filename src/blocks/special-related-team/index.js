/**
 * Special Related Team Block Variation
 *
 * Registers a block variation for displaying specials related to the current team member.
 * Only available on team post types and team template screens.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerSpecialRelatedTeamVariation = () => {
        wp.blocks.registerBlockVariation('core/group', {
            name: 'lsx-tour-operator/special-related-team',
            title: __('Related Specials', 'to-team'),
            icon: 'admin-users',
            description: __('Display specials related to this team member.', 'to-team'),
            category: 'lsx-tour-operator',
            keywords: [
                __('team', 'to-team'),
                __('special', 'to-team'),
                __('related', 'to-team'),
                __('query', 'to-team'),
            ],
            attributes: {
                metadata: {
                    name: __('Related Specials', 'to-team'),
                },
                className: 'lsx-special-related-team-query-wrapper',
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
                            {
                                style: {
                                    layout: { selfStretch: 'fill', flexSize: null },
                                },
                            },
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
                            {
                                style: {
                                    layout: { selfStretch: 'fill', flexSize: null },
                                },
                            },
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
                                    name: __('Related specials query', 'to-team'),
                                },
                                query: {
                                    perPage: 8,
                                    postType: 'special',
                                    order: 'asc',
                                    orderBy: 'date',
                                },
                                align: 'wide',
                            },
                            [
                                [
                                    'core/post-template',
                                    {
                                        className: 'lsx-special-related-team-query',
                                        layout: {
                                            type: 'grid',
                                            columnCount: 3,
                                        },
                                    },
                                    [
                                        [
                                            'core/pattern',
                                            {
                                                slug: 'lsx-tour-operator/special-card',
                                            },
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
                                attributes: {
                                    style: { layout: { selfStretch: 'fill', flexSize: null } },
                                },
                            },
                            {
                                name: 'core/heading',
                                attributes: {
                                    textAlign: 'center',
                                    content: __('Related Special', 'to-team'),
                                    level: 2,
                                },
                            },
                            {
                                name: 'core/separator',
                                attributes: {
                                    style: { layout: { selfStretch: 'fill', flexSize: null } },
                                },
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
                                    className: 'lsx-special-related-team-query',
                                    layout: { type: 'grid', columnCount: 3 },
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
                                                    { name: 'core/group', attributes: { style: { spacing: { padding: { top: '5px', bottom: '10px', left: '5px', right: '5px' }, blockGap: '2px' }, border: { top: { width: '2px' }, bottom: { width: '2px' } } }, layout: { type: 'constrained' } }, innerBlocks: [ { name: 'core/paragraph', attributes: { content: '<strong>' + __('Role: Safari Guide', 'to-team') + '</strong>' } } ] },
                                                    { name: 'core/paragraph', attributes: { content: __('Experienced safari guide with over 10 years leading tours across East Africa.', 'to-team'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
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
                                                    { name: 'core/group', attributes: { style: { spacing: { padding: { top: '5px', bottom: '10px', left: '5px', right: '5px' }, blockGap: '2px' }, border: { top: { width: '2px' }, bottom: { width: '2px' } } }, layout: { type: 'constrained' } }, innerBlocks: [ { name: 'core/paragraph', attributes: { content: '<strong>' + __('Role: Wildlife Expert', 'to-team') + '</strong>' } } ] },
                                                    { name: 'core/paragraph', attributes: { content: __('Wildlife biologist turned tour guide, passionate about conservation and sharing Africa\'s natural wonders.', 'to-team'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
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
                                                    { name: 'core/heading', attributes: { textAlign: 'center', content: __('Amelia Williams', 'to-team'), level: 3, fontSize: 'small', style: { spacing: { margin: { top: '0', bottom: '0' } } } } },
                                                    { name: 'core/group', attributes: { style: { spacing: { padding: { top: '5px', bottom: '10px', left: '5px', right: '5px' }, blockGap: '2px' }, border: { top: { width: '2px' }, bottom: { width: '2px' } } }, layout: { type: 'constrained' } }, innerBlocks: [ { name: 'core/paragraph', attributes: { content: '<strong>' + __('Role: Tour Director', 'to-team') + '</strong>' } } ] },
                                                    { name: 'core/paragraph', attributes: { content: __('Dedicated tour director ensuring every guest enjoys a seamless and unforgettable African adventure.', 'to-team'), style: { spacing: { padding: { left: '5px', right: '5px' } } } } },
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
        ['team'],
        ['team'],
        registerSpecialRelatedTeamVariation
    );
    conditionalRegister();
});
