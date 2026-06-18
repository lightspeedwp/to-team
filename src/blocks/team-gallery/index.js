/**
 * Team Gallery Block Variation
 *
 * Registers a gallery block variation scoped to the team post type.
 * Only available on team post type edit screens.
 *
 * @since 2.2.0
 * @package TO_Team
 */

import { __ } from '@wordpress/i18n';
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';

wp.domReady(() => {
    const registerTeamGalleryVariation = () => {
        wp.blocks.registerBlockVariation('core/gallery', {
            name: 'lsx-tour-operator/team-gallery',
            title: __('Team Gallery', 'to-team'),
            icon: 'format-gallery',
            category: 'lsx-tour-operator',
            description: __('Display the gallery images for this team member.', 'to-team'),
            keywords: [__('gallery', 'to-team'), __('images', 'to-team'), __('team', 'to-team'), __('photos', 'to-team')],
            attributes: {
                metadata: {
                    name: __('Team Gallery', 'to-team'),
                    bindings: {
                        content: { source: 'lsx/gallery' },
                    },
                },
                linkTo: 'none',
                sizeSlug: 'thumbnail',
            },
            innerBlocks: [
                ['core/image', { sizeSlug: 'large', url: lsxToEditor.assetsUrl + 'blocks/placeholder.png' }],
                ['core/image', { sizeSlug: 'large', url: lsxToEditor.assetsUrl + 'blocks/placeholder.png' }],
                ['core/image', { sizeSlug: 'large', url: lsxToEditor.assetsUrl + 'blocks/placeholder.png' }],
            ],
            isDefault: false,
        });
    };

    const conditionalRegister = registerForPostTypesAndTemplates(['team'], ['team'], registerTeamGalleryVariation);
    conditionalRegister();
});
