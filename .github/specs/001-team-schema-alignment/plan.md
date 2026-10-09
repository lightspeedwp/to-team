# Implementation Plan: Team Schema 2.0 Alignment

**Branch**: `fix/to-171-team-schema-alignment` | **Date**: 2026-10-09 | **Spec**: [spec.md](./spec.md)

## Summary

Rebuild the Team `Person` schema piece as a self-contained class that follows the Tour Operator core 2.2 schema-piece pattern, then close the remaining gaps in the spec: `sameAs` validation, `jobTitle` formatting, email validation, published-only associated items, and no `makesOffer`. Add PHPUnit regression tests and record QA evidence.

## Technical Context

**Language/Version**: PHP 7.2+ (plugin minimum); tests run on PHP 8.x with PHPUnit 10.5
**Primary Dependencies**: Tour Operator core 2.2 (`lsx\schema\Helpers`); Yoast SEO graph API (optional)
**Testing**: PHPUnit 10.5 with WordPress function stubs (same approach as core `tests/php/SchemaHelpersTest.php`), plus manual validator QA on the local Studio site
**Target Platform**: WordPress 6.x front end
**Constraints**: Output must not change for tours, accommodation, destinations, reviews or specials. Front-end display of team data must not change.

## Research and Key Decisions

### R1 – Core removed the legacy schema base classes

Core commit `f62d3b5dd` ("Removing the unused files") on `tour-operator` `fix/itinerary-schema-fix-1143` deletes `LSX_TO_Schema_Graph_Piece` and `lsx\legacy\Schema_Utils`. The current Team piece extends that base class and calls `Schema_Utils::add_image()`.

Confirmed on the local site (core 2.2.0 from that branch, Yoast 27.6): `/team/warwick-booth/` emits **no `Person` node**. The `class_exists( 'LSX_TO_Schema_Graph_Piece' )` guard prevents a fatal error, but it also suppresses all output.

**Decision**: The Team piece becomes a plain class with no parent. It has `is_needed()` and `generate()`, takes an optional Yoast context, and depends only on `lsx\schema\Helpers`, mirroring `lsx\schema\pieces\Accommodation`. This works whether or not the legacy classes are present, so it is safe against core `develop` and against the core branch that removes them.

### R2 – Registration guard (FR-012)

Register the piece only when `class_exists( '\lsx\schema\Helpers' )`. That class ships only with core 2.2+, so core 2.1 sites get no Team node and no fatal error.

### R3 – Output when Yoast is inactive

Core prints its own standalone JSON-LD when Yoast is inactive, but only for its three pieces. To keep parity, Team prints its own standalone `<script type="application/ld+json">` on single Team pages when `WPSEO_VERSION` is not defined. It uses the same JSON flags as core (`JSON_HEX_TAG | JSON_HEX_AMP` for `</script>` safety).

### R4 – Identifiers and references

- `@id`: `{permalink}#/schema/person/{post_id}` (unchanged).
- `mainEntityOfPage`: `{ "@id": permalink }`. Yoast 14+ uses the canonical URL as the WebPage `@id`, and core pieces do the same. The old `WPSEO_Schema_IDs::WEBPAGE_HASH` constant depends on a removed Yoast class.
- `image`: Yoast primary image reference when the context has an image, otherwise the featured image URL. This is the same logic as core.
- `worksFor`: `$context->site_represents_reference` when present.

### R5 – Field rules

| Property | Source | Rule |
| --- | --- | --- |
| `jobTitle` | `role` terms | Exclude "Uncategorized"; join with `", "`; omit if empty |
| `email` | `contact_email` | Emit only if `is_email()`; `sanitize_email()` |
| `telephone` | `contact_number` | `sanitize_text_field()`; emit as entered |
| `sameAs` | `facebook`, `twitter`, `linkedin`, `pinterest` | Absolute `http`/`https` URLs only; `esc_url_raw()`; de-duplicated; Google+ and Skype excluded; field list filterable via `lsx_to_team_schema_social_fields` |
| `additionalProperty` | `tour_to_team`, `accommodation_to_team` | Published posts only; de-duplicated; titles joined with `", "` |
| `makesOffer` | — | Not emitted (FR-011 default) |

The whole data array passes through `lsx_to_schema_team_data`, following core's `lsx_to_schema_{type}_data` convention.

### R6 – Tests

There is no PHP test harness in to-team. Add `phpunit.xml.dist` and `tests/php/` with a bootstrap that stubs the WordPress functions the piece uses, backed by in-memory post, meta and term fixtures. The core `Helpers` class is loaded from `TO_CORE_PATH` when set, or from the sibling `../tour-operator` checkout, and the tests are skipped with a clear message if it is unavailable. Bump `require-dev` PHPUnit to `^10.5`, matching core and the other extensions, and add a `composer test` script.

## Project Structure (files touched)

```text
classes/class-to-team-schema.php     # rewritten piece (self-contained)
classes/class-to-team.php            # guard + standalone output
tests/php/bootstrap.php              # WP stubs + fixtures
tests/php/TeamSchemaTest.php         # regression tests
phpunit.xml.dist                     # test config
composer.json                        # phpunit ^10.5, test script
changelog.md                         # Unreleased entry
.github/specs/001-team-schema-alignment/quickstart.md  # QA steps + evidence
```

## Risks

- **Yoast context shape**: Yoast 14+ passes `Meta_Tags_Context`. Only `id`, `has_image` and `site_represents_reference` are read, and all three exist on both the legacy and the current context objects.
- **Duplicate Person nodes**: Yoast emits a `Person` for post authors, but only on author archives and articles. Team pages are a custom post type, so that `Person` node does not appear on them, which the baseline graph capture confirms.
