# Change log

## [2.2.0] - 2026-07-29

### Fixed
- Security: escaped `tel:` and `mailto:` contact links in `lsx_to_team_contact_number()` and `lsx_to_team_contact_email()` template tags using `esc_url()` and `esc_html()`.
- Security: escaped social profile URLs and icon class with `esc_url()` and `esc_attr()` in `lsx_to_team_social_profiles()`.
- Security: added `ABSPATH` early-exit guard to all class files, config includes, template tags, and the team-card pattern.
- Bug: fixed user array population in `config-team.php` — the `$users` array was being overwritten on each iteration instead of appended; `WP_User_Query` is now also scoped to the `team` edit screen to avoid unnecessary queries.
- Bug: `glob()` call in `LSX_TO_Team_Blocks::register_blocks()` no longer iterates when the directory is empty or does not exist; prevents PHP warnings on fresh installs.
- Bug: `parse_url()` replaced with `wp_parse_url()` in the contact-link URL normalisation method.
- Bug: `posts_per_page` for Envira Gallery queries changed from string `'-1'` to integer `-1`.
- Bug: taxonomy `rewrite` for the `role` taxonomy corrected from `array( 'role' )` to `array( 'slug' => 'role' )`.
- i18n: `load_plugin_textdomain()` simplified to use WordPress auto-discovery (second and third arguments removed).
- Code quality: inline `phpcs:ignore` comment updated to the modern `phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found` format.
- Docs: added missing docblocks for `lsx_to_maps_args()` and `lsx_to_has_maps_location()` in `class-to-team-frontend.php`.

### Updated
- `Requires Plugins: tour-operator` added to plugin header.
- Version bumped to `2.2.0` in plugin header and `readme.txt`.
- `readme.txt`: tested up to WordPress 7.0; stable tag updated to `2.2.0`; corrected theme name from "to Theme" to "LSX Theme"; fixed two incomplete FAQ question headings.

## [[2.2]](https://github.com/lightspeeddevelopment/to-team/releases/tag/2.2) - Unreleased

### Description
This release renames and rebuilds the team member block variations for consistency, and adds new "related" block variations for surfacing connected content on the team member edit screen.

### Added
- New block variations for surfacing content connected to a team member: `special-related-team`, `review-related-team`, and `post-related-team` (matching the existing `accommodation-related-team`, `destination-related-team`, and `tour-related-team` pattern)
- `Team - Social Links` block variation, with custom field integration and editor styling

### Updated
- Renamed block folders to drop the redundant `team-` prefix (e.g. `team-contact-email` → `contact-email`, `team-gallery` → `gallery`, `team-role` → `role`, `team-social-links` → `social-links`, `team-tagline` → `tagline`) and updated their titles to `Team - <Field>` for consistency
- Renamed the "-to-" connection blocks to lead with the connected post type (e.g. `team-to-accommodation` → `accommodation-to-team`, `team-to-destination` → `destination-to-team`, `team-to-tour` → `tour-to-team`), updating each block's registered name, title, and CSS class to match
- Renamed `team-related-tour` → `tour-related-team` to match the `<post-type>-related-team` naming used by its siblings, and corrected its registration to the `team` post type
- Updated `accommodation-related-team` and `destination-related-team` titles, descriptions, and pattern references to correctly describe accommodation/destination content, and fixed their `core/query` `postType` (previously hardcoded to `team`, which caused both queries to return no results)

### Removed
- `team-related-team` block variation (no longer needed)

## [[2.1]](https://github.com/lightspeeddevelopment/to-team/releases/tag/2.1) - 2025-12-20

### Description
This release includes major plugin structure refactoring, field improvements, and proper block editor template support for the Team post type.

### Added
- Block editor templates for team archive and single pages (`templates/archive-team.html` and `templates/single-team.html`)
- New `LSX_TO_Team_Templates` class for proper template registration

### Updated
- Refactored all class files to use consistent `to-team` naming convention (removed `lsx-to-` prefix)
- Post, accommodation, destination and tour relation fields with improved CMB2 configurations
- Plugin assets converted to PNG format for better quality (banners and icons)
- Updated CMB2 field configurations for better customization options
- Improved field descriptions and user guidance

### Fixed
- Corrected template class naming from `LSX_TO_Specials_Templates` to `LSX_TO_Team_Templates`
- Registration of the default team single and archive templates
- Block editor template compatibility issues

### Changed
- Class file structure: `class-lsx-to-team-*.php` → `class-to-team-*.php` for consistency
- Removed incorrect `class-to-specials-templates.php` file

### Security
- Tested with WordPress 6.9+
- Tested with PHP 8.0+

## [[2.0.0]](https://github.com/lightspeeddevelopment/to-team/releases/tag/2.0.0) - 2025-05-09

### Description
The following PR contains the code for the block updates and the removal of the legacy code.

### Added
- WordPress block editor support
- Tour Operator 2.0 Support.

### Updated
- Custom fields to CMB2 and its add-ons.
- WPCS warnings notices fixed.

### Removed
- Old PHP Templates, function and legacy template code.

### Security
- Tested with WordPress 6.8.1

## [[1.2.7]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.2.7) - 2023-08-09

### Security
- General testing to ensure compatibility with latest WordPress version (6.3).

## [[1.2.6]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.2.6) - 2023-04-20

### Security
- General testing to ensure compatibility with latest WordPress version (6.2).

## [[1.2.5]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.2.5) - 2022-12-23

### Updated
- Updated the WP User Queries.
- The Post Connections text.

### Security
- General testing to ensure compatibility with latest WordPress version (6.1.1).

## [[1.2.4]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.2.4) - 2022-09-12

### Security
- General testing to ensure compatibility with latest WordPress version (6.0).

## [[1.2.3]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.2.3) - 2021-01-15

### Added
- Allowing the block editor for the single team description area.

### Updated
- Documentation and support links.

### Security
- General testing to ensure compatibility with latest WordPress version (5.6).

## [[1.2.2]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.2.2) - 2020-03-30

### Fixed
* Fix - Fixing the options for the Team widget, making sure all of them work, also on shortcode mode.

### Security
- General testing to ensure compatibility with latest WordPress version (5.4).
- General testing to ensure compatibility with latest LSX Theme version (2.7).


## [[1.2.1]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.2.1) - 2019-12-19

### Added
- Changing a variable name $ID to $id.
- Enabled the sorting of the gallery field.
- General testing to ensure compatibility with latest WordPress version (5.3).
- Checking compatibility with LSX 2.6 release.

### Fixed
- Changing the map priority on the single team pages.


## [[1.2.0]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.1.2) - 2019-09-27

### Added
- Adding the .gitattributes file to remove unnecessary files from the WordPress version.
- Adding the Posts slider to the single team.
- Adding the map option for single team.
- Adding width and height to the map.
- Adding map filters.
- Added in a 'Person' Schema via the Yoast WordPress SEO plugin.


## [[1.1.1]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.1.1) - 2019-08-06

### Fixed
- Removed API Call Error.


## [[1.1.0]](https://github.com/lightspeeddevelopment/to-team/releases/tag/1.1.0) - 2017-10-10

### Added
- Added compatibility with LSX 2.0.
- Added compatibility with Tour Operator 1.1.
- Support LSX Theme 2.0 new designs.
- New project structure.
- Updated the the way the post type registers to match the refactored TO plugin.
- Updated the registering of the metaboxes.

### Fixed
- Fixed scripts/styles loading order.
- Fixed small issues.
- Replaced 'global $tour_operators' by 'global $tour_operator'.


## [[1.0.4]]()

### Added
- Standardized the Gallery and Video fields.
- Added in an option to disable the team member panel if you just want an archive.


## [[1.0.3]]()

### Added
- Fixed menu navigation improved.

### Fixed
- Make the addon compatible with the latest version from TO Search addon.
- API key and email grabbed from the correct settings tab.
- Added TO Search as subtab on LSX TO settings page.
- Code refactored to follow the latest Tour Operator plugin workflow.
- Small fixes on front-end fields.
- Fixed content_part filter for plugin and add-ons.


## [[1.0.2]]()

### Fixed
- Fixed all prefixes replaces (to_ > lsx_to_, TO_ > LSX_TO_).


## [[1.0.1]]()

### Fixed
- Reduced the access to server (check API key status) using transients.
- Made the API URLs dev/live dynamic using a prefix "dev-" in the API KEY.


## [[1.0.0]]()

### Added
* First Version.
