# Tasks: Team Schema 2.0 Alignment

**Input**: [spec.md](./spec.md), [plan.md](./plan.md)

## Phase 1 – Setup

- [ ] T001 Bump `require-dev` PHPUnit to `^10.5` and add a `test` script in `composer.json`
- [ ] T002 Add `phpunit.xml.dist` and `tests/php/bootstrap.php` (WordPress stubs plus in-memory fixtures)

## Phase 2 – Foundational (blocks all stories)

- [ ] T003 Rewrite `classes/class-to-team-schema.php` as a self-contained piece (no legacy base class) emitting the baseline properties: `@type`, `@id`, `name`, `description`, `url`, `mainEntityOfPage`, `worksFor`, `image` (FR-001–FR-003, FR-010)
- [ ] T004 Register the piece only when `lsx\schema\Helpers` exists, and print standalone JSON-LD when Yoast is inactive, in `classes/class-to-team.php` (FR-012)

## Phase 3 – User Story 1: valid Person (P1)

- [ ] T005 [US1] `jobTitle` from roles, excluding "Uncategorized" and joined with ", " (FR-004, FR-005)
- [ ] T006 [US1] `telephone` and `additionalProperty` for associated tours and accommodation, published only and de-duplicated, with no `owns` (FR-006, FR-009)

## Phase 4 – User Story 2: trustworthy links and contact data (P2)

- [ ] T007 [US2] `email` only when well-formed (FR-006)
- [ ] T008 [US2] `sameAs` limited to absolute http(s) URLs, de-duplicated, with Google+ and Skype excluded (FR-007, FR-008)

## Phase 5 – User Story 3: approved properties only (P2)

- [ ] T009 [US3] No `makesOffer` output (FR-011)

## Phase 6 – User Story 5: regression tests and QA (P3)

- [ ] T010 [US5] `tests/php/TeamSchemaTest.php` covering FR-001, FR-003, FR-004/FR-005, FR-006, FR-007/FR-008, FR-009, FR-011 (FR-013)
- [ ] T011 [US5] Capture before/after JSON-LD from the local site and document QA steps in `quickstart.md` (FR-014)

## Phase 7 – Polish

- [ ] T012 PHPCS (WPCS) clean on changed PHP files
- [ ] T013 `changelog.md` Unreleased entry

## Dependencies

T001 → T002 → T010. T003 → T004 → T005–T009 → T011. The work is committed per phase.
