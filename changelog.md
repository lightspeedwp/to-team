# Change log

## [[2.0.0]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/2.0.0) - 2025-05-09

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
