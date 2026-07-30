# Build Tooling — TO Team

## v2.2: Migration from Gulp to wp-scripts

In v2.2, the build tooling was migrated from Gulp to `@wordpress/scripts` (wp-scripts), aligning with the parent tour-operator plugin and the other child plugins (to-reviews, to-specials).

## Available Scripts

```bash
npm run build             # Production build
npm run start             # Development watch mode
npm run build:pot         # Generate .pot translation file
npm run build:mopo        # Compile .po → .mo files
npm run build:translate-US  # Build en_US translation
npm run lint:js           # Lint JavaScript
npm run lint:css          # Lint CSS/SCSS
npm run lint:all          # Run all linters
```

## Entry Points

The webpack config at `webpack.config.js` compiles all block `index.js` files plus the main `assets/js/to-team.js` and `assets/js/to-team-admin.js` bundles.

Block entry points are auto-discovered from `src/blocks/*/index.js`.

## `@utils` Alias

The webpack config provides an `@utils` alias pointing to `src/utils/`. Use it in block files:

```js
import { registerForPostTypesAndTemplates } from '@utils/conditional-block-registration.js';
```

## Output

Built files land in `build/`:
- `build/blocks/{block-name}/index.js` — compiled block script
- `build/js/to-team.js` — frontend bundle
- `build/js/to-team-admin.js` — admin bundle
- `build/css/to-team.css` — frontend styles
- `build/css/to-team-admin.css` — admin styles
