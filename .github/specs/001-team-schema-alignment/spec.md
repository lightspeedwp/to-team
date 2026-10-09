# Feature Specification: Team Schema 2.0 Alignment

**Feature Branch**: `fix/to-171-team-schema-alignment` (based on `update-2.2`)

**Created**: 2026-10-09

**Status**: Implemented, external QA pending (2026-10-09)

**Tracking**: [TO-171](https://linear.app/lightspeedwp/issue/TO-171/align-team-schema-with-approved-schema-20-mappings) · [lightspeedwp/to-team#124](https://github.com/lightspeedwp/to-team/issues/124)

**Approved mapping**: [Tour Operator – Structured Data › Team (audit)](https://docs.google.com/document/d/1UVfrdgR1o2A-5YuyMbY6Bxevy3EXYfOkj-p6dwXFxMA/edit?tab=t.6vo3cqfr9ooz)

**Input**: "Align the Team extension schema output with the approved Tour Operator Schema 2.0 mappings, so Team posts emit a valid `Person` node."

## Context: Baseline on `update-2.2`

The earlier `feature/1143-schema-2.2-team-alignment` work, including commits `bc7ed32` and `e3a1fae`, has already been merged into `update-2.2`. In this repository `update-2.2` is **29 commits ahead of `develop`**, so it is the correct base. The audit below compares each TO-171 task with the current baseline.

| TO-171 task | Baseline status |
| --- | --- |
| `@type` is the string `Person` | ✅ Done |
| `@id` is unique (`#/schema/person/{post_id}`) | ✅ Done |
| `name` from filtered title | ✅ Done |
| `description` from filtered, HTML-stripped content | ✅ Done |
| `url` + `mainEntityOfPage` kept | ✅ Done |
| `worksFor` from the site organisation | ✅ Done |
| Redundant `memberOf` removed | ✅ Done |
| `jobTitle` as text, not an array | ✅ Done. Gap: roles are joined with a bare comma ("Guide,Consultant") (see FR-005) |
| `contact_email` → `email` | ✅ Done. Gap: not validated (see FR-006) |
| `contact_number` → `telephone`, preserving international format | ✅ Done |
| `owns` replaced with "Associated Tours" / "Associated Accommodation" properties | ✅ Done. Gap: unpublished items can be listed (see FR-009) |
| `sameAs` for social profiles | ⚠️ Partly done. Reads the defunct Google+ field and emits the free-text values unvalidated, so handles or invalid URLs can be output (see FR-007, FR-008) |
| Image via the shared schema utility | ✅ Done |
| Tests / manual validation for one populated Team post | ❌ Not done |

Other gaps found during the audit:

- **Inherited `makesOffer` output**: The Person node calls the shared core "offers" builder under the `makesOffer` key. When a Special is linked to a team member, that builder emits an Offer with a mis-cased `PriceSpecification` plain string, which is the defect TO-217 removes from Specials. `makesOffer` is not part of the approved Team mapping and implies the person is the seller.
- **Core version dependency**: The schema piece relies on shared helpers that ship only with Tour Operator core 2.2+. The loader checks only for the older base class, which core 2.1 also provides, so running this extension with core 2.1 would cause a fatal error on Team pages.
- **Legacy base classes removed from core** *(added 2026-10-09)*: Core commit `f62d3b5dd` deletes the legacy schema base class and utilities that the Team piece extends. With that core build, Team pages currently emit **no Person node at all**, which was confirmed on the local site. The piece must therefore stop depending on those legacy classes (see FR-012 and [plan.md](./plan.md) R1).

## Implementation Status (2026-10-09)

The baseline table above records the starting point. Every gap marked there has been closed on this branch.

### Requirements

- [x] FR-001: string `Person` `@type`, unique `@id`
- [x] FR-002: plain-text name and description, `url`, `mainEntityOfPage`
- [x] FR-003: `worksFor` kept, `memberOf` removed
- [x] FR-004 / FR-005: `jobTitle` as one string, joined with ", ", excluding "Uncategorized"
- [x] FR-006: `email` only when valid; `telephone` as entered
- [x] FR-007 / FR-008: `sameAs` limited to unique http(s) URLs, excluding Google+ and Skype
- [x] FR-009: associated tours and accommodation as `additionalProperty` (published, unique), with no `owns`
- [x] FR-010: image via the Yoast primary image or the featured image
- [x] FR-011: no `makesOffer`
- [x] FR-012: self-contained piece (no legacy base class), registered only when core 2.2 helpers exist, standalone output without Yoast
- [x] FR-013: PHPUnit regression tests (14 tests)
- [x] FR-015: front-end display unchanged (schema files only)
- [ ] FR-014: manual QA. The local capture is done (see [quickstart.md](./quickstart.md)); external validators, the no-Yoast check and the core 2.1 check are still outstanding

### Key commits

- `8e7cb2f`: rebuild the Person piece without the legacy core base class
- `671585a`: sameAs, email, jobTitle and related-item fixes
- `f9fdd33`: regression tests

## User Scenarios & Testing *(mandatory)*

### User Story 1 – Search engines read a valid Person for each team member (Priority: P1)

A travel operator publishes a profile for a safari consultant with bio, photo, role, contact details and social profiles. Search engines and AI assistants find a single valid `Person` node. It identifies the consultant, says who they work for and what their role is, and links to their verified social profiles. This supports E-E-A-T signals for the expert behind tours and lodges.

**Why this priority**: This is the outcome TO-171 exists for, and Person markup for named experts builds trust signals across the site.

**Independent Test**: Load one fully populated Team post on the QA site and validate it with the Schema.org Validator and the Google Rich Results Test.

**Acceptance Scenarios**:

1. **Given** a Team post with title, content, featured image, two roles, email, international phone number, Facebook and LinkedIn URLs, one linked tour and one linked accommodation, **When** the page is validated, **Then** exactly one `Person` node is reported with string `@type`, a unique `@id`, `name`, `description`, `url`, `mainEntityOfPage`, `image`, `worksFor`, `jobTitle` as one readable string, `email`, `telephone`, `sameAs` with two URLs, and `additionalProperty` entries "Associated Tours" and "Associated Accommodation".
2. **Given** the same page, **When** the JSON-LD is inspected, **Then** no `memberOf` and no `owns` properties are present.
3. **Given** a Team post whose content contains HTML, shortcodes or blocks, **When** the page is validated, **Then** `description` is plain text.

---

### User Story 2 – Only trustworthy profile links and contact data are emitted (Priority: P2)

Editors often paste handles such as "@jane_safaris" or leave legacy Google+ links in profile fields. Only real, absolute profile URLs should reach `sameAs`, and only a well-formed email should be published.

**Why this priority**: Invalid `sameAs` values produce validator errors and weaken entity identity. Google+ has been shut down since 2019.

**Independent Test**: Populate one social field with a handle, one with a valid URL and the Google+ field with a URL. Validate the page.

**Acceptance Scenarios**:

1. **Given** Twitter set to "@jane", LinkedIn set to a full https URL and Google+ set to a URL, **When** the page is validated, **Then** `sameAs` contains only the LinkedIn URL.
2. **Given** the same URL entered in two social fields, **When** the page is validated, **Then** it appears once in `sameAs`.
3. **Given** a malformed contact email, **When** the page is validated, **Then** `email` is omitted.

---

### User Story 3 – Only approved properties are emitted (Priority: P2)

A structured data reviewer checks that the Person node contains only properties from the approved mapping and carries no defects in from other modules.

**Why this priority**: `makesOffer` from linked Specials is outside the approved mapping, implies the guide sells the product, and currently carries the TO-217 price-specification defect.

**Independent Test**: Link a Special to a team member, validate the page, and compare its properties with the approved mapping.

**Acceptance Scenarios**:

1. **Given** a team member linked to a Special, **When** the page is validated, **Then** the Person node contains no mis-cased `PriceSpecification` key, and handles `makesOffer` as agreed in FR-011.
2. **Given** a team member linked to an unpublished or deleted tour, **When** the page is validated, **Then** that tour is not named in "Associated Tours".

---

### User Story 4 – Sites never break because of Team schema (Priority: P2)

A site owner updates the Team extension before updating Tour Operator core. Their Team pages must keep rendering.

**Why this priority**: A front-end fatal error is far more damaging than missing structured data.

**Independent Test**: Activate the extension with Tour Operator core 2.1 and Yoast SEO, then load a Team page.

**Acceptance Scenarios**:

1. **Given** Tour Operator core older than 2.2, **When** a Team page loads, **Then** the page renders, no PHP fatal or warning is raised, and no Person node is emitted.

---

### User Story 5 – QA evidence exists for sign-off (Priority: P3)

A reviewer approving the PR needs repeatable evidence that the output matches the approved mapping.

**Why this priority**: The acceptance criteria require validation "against the agreed structured data QA approach".

**Independent Test**: Follow the documented QA steps and compare the captured JSON-LD with the expected output.

**Acceptance Scenarios**:

1. **Given** the documented QA procedure, **When** a reviewer follows it, **Then** they can reproduce a zero-error validator run for the reference Team post.
2. **Given** the automated checks, **When** they run, **Then** at least one check fails if `@type` becomes an array, `@id` loses its post-specific suffix, `memberOf` or `owns` reappear, or `jobTitle` becomes a list.

### Edge Cases

- **No role assigned**: omit `jobTitle`.
- **Only the default "Uncategorised" term** in the role taxonomy: omit `jobTitle`.
- **Phone number in local format**: emit as entered. Formatting is a content concern.
- **No social profiles, or only invalid ones**: omit `sameAs`.
- **No linked tours or accommodation**: omit the matching `additionalProperty` entry. Omit `additionalProperty` entirely when both are empty.
- **Yoast SEO inactive, or no site organisation configured**: omit `worksFor`. No errors.
- **Skype field**: not a profile URL, so it is not emitted in `sameAs`.
- **Team post in draft or preview**: no Person node is emitted for non-public posts.
- **Team member shown on a Tour page**: out of scope. Linking a guide into a Trip node is a core concern.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: Each public Team page MUST emit exactly one node with a string `@type` of `Person` and an `@id` unique to that team member.
- **FR-002**: The Person MUST include `name` and `description` taken from the filtered title and filtered, markup-free content, plus `url` and `mainEntityOfPage`.
- **FR-003**: The Person MUST include `worksFor` referencing the site organisation whenever one is configured, and MUST NOT include `memberOf`.
- **FR-004**: `jobTitle` MUST be a single text value built from the team member's roles, excluding the default "Uncategorised" term.
- **FR-005**: When a team member has several roles, they MUST be joined with a human-readable separator (", ").
- **FR-006**: `email` MUST be emitted only when the contact email is a well-formed address. `telephone` MUST be emitted as entered whenever it is present.
- **FR-007**: `sameAs` MUST contain only absolute http(s) URLs from the team member's social profile fields, with duplicates removed.
- **FR-008**: The defunct Google+ field and the Skype field MUST NOT contribute to `sameAs`.
- **FR-009**: Linked tours and accommodation MUST be expressed as `additionalProperty` values labelled "Associated Tours" and "Associated Accommodation". Only published items are included, duplicates are removed, and `owns` MUST NOT be emitted.
- **FR-010**: The image MUST continue to be emitted through the shared schema image utility.
- **FR-011**: The Person node MUST NOT emit `makesOffer` from linked Specials. *(Default chosen: `makesOffer` is not in the approved Team mapping, implies the person sells the product, and currently carries the TO-217 price-specification defect. Confirm in `/speckit-clarify`.)*
- **FR-012**: The Team schema piece MUST only register when the Tour Operator core schema helpers it depends on are available. Otherwise it MUST register nothing and raise no errors. It MUST NOT depend on the legacy core schema base classes, and it MUST emit the Person node both with and without Yoast SEO active.
- **FR-013**: The plugin MUST include at least one automated regression check covering FR-001, FR-003, FR-004/FR-005, FR-007 and FR-009.
- **FR-014**: The PR MUST include documented manual QA evidence for one fully populated Team post: validator results and the captured JSON-LD.
- **FR-015**: Front-end display of team contact details and social links, including blocks and template tags, MUST NOT change as a result of this work.

### Key Entities

- **Team member (post)**: A staff profile, for example a guide or consultant. Attributes: name, bio, featured image, role(s), contact email, contact number, Skype, social profile fields (Facebook, Twitter/X, Google+, LinkedIn, Pinterest). Relationships: linked tours, accommodation and specials.
- **Person (schema node)**: The structured-data representation of a team member.
- **Site organisation**: The Organization node supplied by Yoast SEO and referenced as `worksFor`.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: The reference Team post on the QA site returns **0 errors** in the Schema.org Validator and the Google Rich Results Test, with no warnings for any property this spec covers.
- **SC-002**: **100%** of the rows in the approved Team mapping are present and correctly typed in the captured JSON-LD for the reference Team post.
- **SC-003**: **0** non-URL values in `sameAs` across every tested combination of social field inputs: handles, empty values, duplicates, Google+ and valid URLs.
- **SC-004**: Two Team posts rendered on the same page produce two distinct `@id` values.
- **SC-005**: **0** PHP fatal errors, warnings or notices on Team pages across core 2.1 and 2.2, with Yoast SEO active and inactive.
- **SC-006**: The automated regression check fails when any one of the FR-013 defects is reintroduced, and passes on the delivered code.

## Assumptions

- **Base branch**: Work branches from `update-2.2` and the PR targets `update-2.2`, as TO-171's delivery decision specifies. In this repository `update-2.2` contains 29 commits not yet in `develop`.
- **Mapping authority**: The "Team (audit)" tab is the approved mapping. The older "Schema (old reference)" section, which proposes `Role` as the main type, is superseded.
- **QA approach**: Schema.org Validator plus the Google Rich Results Test, run against a fully populated Team post on `tour-operator.lightspeedwp.dev`.
- **Publishing staff email is intentional**: `contact_email` is a business contact that editors chose to publish, unlike guest emails in Reviews. TO-171 explicitly keeps the `email` mapping.
- **Google+ data** stays in the database and admin UI. Only its schema output is dropped. Removing the field from the admin UI is a separate clean-up.
- **The Twitter field** may hold an X URL. Any absolute http(s) URL is accepted whatever the domain.
- **Cross-node linking**: Wiring Person into core `TouristTrip` as `guide`/`employee` is a core concern and out of scope.

### Out of Scope

- Tour, Accommodation and Destination schema in core, including referencing team members from those nodes.
- Specials schema (TO-217) and Reviews schema (TO-216).
- Changes to team admin fields, blocks or templates.
