# Block Development — TO Team

All blocks use the `lsx-tour-operator/` namespace and are registered as **block variations** via `wp.blocks.registerBlockVariation()`.

## Block Reference

### Featured

| Block Name | Variation | Registration | Description |
|---|---|---|---|
| `lsx-tour-operator/featured-team` | `core/group` | Global | Query loop for featured team members |

### Related — Same Type

| Block Name | Post Types | Templates | className |
|---|---|---|---|
| `lsx-tour-operator/team-related-team` | `team` | `team` | `lsx-team-related-team-query-wrapper` |

### Related — Cross Type

| Block Name | Post Types | Templates | className |
|---|---|---|---|
| `lsx-tour-operator/team-related-destination` | `destination` | `destination`, `country`, `region` | `lsx-team-related-destination-query-wrapper` |
| `lsx-tour-operator/team-related-accommodation` | `accommodation` | `accommodation` | `lsx-team-related-accommodation-query-wrapper` |
| `lsx-tour-operator/team-related-tour` | `tour` | `tour` | `lsx-team-related-tour-query-wrapper` |

### Post Meta

All scoped to `team` post type and template.

| Block Name | Binding Key | Element |
|---|---|---|
| `lsx-tour-operator/team-tagline` | `tagline` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/team-role` | `role` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/team-contact-email` | `contact_email` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/team-contact-number` | `contact_number` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/team-social-links` | `facebook`, `twitter`, `linkedin`, `pinterest`, `skype` | `core/group` wrapping five `core/paragraph` |

### Post Connection

All scoped to `team` post type and template.

| Block Name | Connection Key | Icon |
|---|---|---|
| `lsx-tour-operator/team-to-accommodation` | `accommodation_to_team` | `accommodationIcon` |
| `lsx-tour-operator/team-to-destination` | `destination_to_team` | `destinationIcon` |
| `lsx-tour-operator/team-to-tour` | `tour_to_team` | `tourIcon` |

### Gallery

| Block Name | Source | Registration |
|---|---|---|
| `lsx-tour-operator/team-gallery` | `lsx/gallery` | `team` post type only |

## Conditional Registration

Blocks use `registerForPostTypesAndTemplates(postTypes, templates, registerFn)` from `@utils/conditional-block-registration.js` to limit insertion to relevant post type edit screens. Featured blocks are registered globally.

## Binding Sources

| Source | Use |
|---|---|
| `lsx/post-meta` | Binds `core/paragraph` content to a CMB2 meta field via `args.key` |
| `lsx/post-connection` | Binds `core/paragraph` content to a connected post via `args.key` |
| `lsx/gallery` | Binds `core/gallery` images to the post gallery meta |

## Adding a New Block

1. Create `src/blocks/{block-name}/block.json` with `"editorScript": "file:index.js"` and `"textdomain": "to-team"`
2. Create `src/blocks/{block-name}/index.js` with the variation registration
3. Run `npm run build`
