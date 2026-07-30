# TO Team

The Tour Operator Team extension adds the `team` post type with full Gutenberg block support for displaying team member profiles on your site.

## Requirements

- [Tour Operator Plugin](https://touroperator.solutions/) (parent plugin)
- WordPress 6.7+
- PHP 8.0+

## Installation

1. Ensure the Tour Operator Plugin is installed and activated.
2. Upload or install the `to-team` plugin.
3. Activate via **Plugins → Installed Plugins**.

## Post Type

**Slug:** `team`

Post meta fields:

| Field | Key | Type |
|---|---|---|
| Tagline | `tagline` | text |
| Role | `role` | text |
| Contact Email | `contact_email` | text |
| Contact Number | `contact_number` | text |
| Facebook | `facebook` | text |
| Twitter | `twitter` | text |
| LinkedIn | `linkedin` | text |
| Pinterest | `pinterest` | text |
| Skype | `skype` | text |

## Blocks

See [block-development.md](block-development.md) for a full block reference.

## Building

See [build-tooling.md](build-tooling.md) for tooling details.

```bash
npm install
npm run build
```

## Support

[LightSpeed support form](https://lightspeedwp.agency/lsx/support/)
