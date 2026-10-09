# QA: Team Schema 2.0 Alignment

How to verify TO-171 and the evidence captured during implementation.

## Automated checks

```bash
composer install
composer test
# or, with a tour-operator checkout elsewhere:
TO_CORE_PATH=/path/to/tour-operator vendor/bin/phpunit -c phpunit.xml.dist
```

Expected result: `OK (14 tests)`. Seven of the tests fail against the pre-fix piece (`8e7cb2f`), which confirms they catch each defect.

## Manual QA

1. Use a site with Tour Operator core 2.2+, Yoast SEO and this branch of TO Team active.
2. Populate a Team post with: title, content, featured image, two roles, email, international phone number, a Facebook URL, a LinkedIn URL, a handle in the Twitter field, and a Google+ value. Link at least one tour and one accommodation, including a draft or duplicate.
3. View the Team page source and copy the `yoast-schema-graph` JSON-LD.
4. Run the page URL, or the copied JSON-LD for a local site, through:
   - [Schema.org Validator](https://validator.schema.org/)
   - [Google Rich Results Test](https://search.google.com/test/rich-results)
5. Check that:
   - Exactly one `Person` node is present, with string `@type` and an `@id` ending `#/schema/person/{ID}`.
   - `jobTitle` is one string ("Role A, Role B").
   - `sameAs` lists only full profile URLs: no handle, no Google+ value, no duplicates.
   - "Associated Tours" and "Associated Accommodation" list only published items, as plain text.
   - There is no `memberOf`, `owns`, `makesOffer` or `PriceSpecification`.
   - The validators report 0 errors.
6. **Without Yoast**: deactivate Yoast SEO, reload the page and confirm that a standalone `<script type="application/ld+json">` containing the Person node is printed in `<head>`.
7. **Core 2.1**: activate with Tour Operator core 2.1. The Team page renders with no PHP errors and no Person node.

## Evidence (local Studio site, 2026-10-09)

Environment: `https://beta.local`, Tour Operator 2.2.0 (`fix/itinerary-schema-fix-1143`, which has the legacy schema classes removed), Yoast SEO 27.6, PHP 8.4.

**Before** (`update-2.2` baseline): `/team/warwick-booth/` emitted **no Person node**. The graph contained only `WebPage`, `ImageObject`, `BreadcrumbList`, `WebSite` and `Organization`.

**After** (this branch):

```json
{
    "@type": "Person",
    "@id": "https://beta.local/team/warwick-booth/#/schema/person/307",
    "name": "Warwick Booth",
    "url": "https://beta.local/team/warwick-booth/",
    "mainEntityOfPage": { "@id": "https://beta.local/team/warwick-booth/" },
    "description": "Lorem ipsum dolor sit amet",
    "worksFor": { "@id": "https://beta.local/#organization" },
    "email": "warwick@lightspeedwp.agency",
    "telephone": "+278544444444",
    "sameAs": [ "https://www.facebook.com/crimsonbinome" ],
    "additionalProperty": [
        { "@type": "PropertyValue", "name": "Associated Accommodation", "value": "Koh Yao Yai Village, The Simple Koh Yao Noi, The Surin Phuket, Eastin Yama Phuket Hotel, Ben’s House, Clouds Estate" },
        { "@type": "PropertyValue", "name": "Associated Tours", "value": "Phuket – Krabi – Thailand – 14 Nights, Switaly – 14 Nights, Western Cape 12 Day" }
    ],
    "image": { "@id": "https://beta.local/team/warwick-booth/#primaryimage" }
}
```

The reference post's Twitter, Google+, LinkedIn and Pinterest fields hold plain handles such as `warwickTwitter`. These are correctly excluded from `sameAs`. No PHP errors from TO Team on the page.

**Still to do before sign-off**: the external validator runs (step 4), the no-Yoast check (step 6) and the core 2.1 check (step 7) have not been run yet. Run them on `tour-operator.lightspeedwp.dev` with a fully populated Team post, including roles.
