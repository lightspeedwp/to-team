---
description: 'Coding standards and review guidelines for TO Team plugin development'
applyTo: '**.php, **.js, **.ts, **.css, **.scss, **.md'
---

# Coding Standards Instructions

## WordPress Coding Standards for TO Team Plugin

### PHP Standards

Follow WordPress PHP Coding Standards (WPCS) strictly:

#### Naming Conventions

- **Classes:** Use `TO_Team_` prefix with Pascal_Case
- **Functions:** Use `to_team_` prefix with snake_case
- **Variables:** Use snake_case (`$team_data`, `$member_role`)
- **Constants:** Use UPPER_SNAKE_CASE (`TO_TEAM_VERSION`)

#### Code Structure

```php
<?php
/**
 * Class description
 *
 * @package TO_Team
 * @since 1.0.0
 */
class TO_Team_Display {

    /**
     * Method description
     *
     * @param int $member_id Team member ID.
     * @return string Member HTML output.
     */
    public function display_member( $member_id ) {
        // Implementation
    }
}
```

#### Security Best Practices

1. **Sanitization:** Always sanitize input data

   ```php
   $member_name = sanitize_text_field( $_POST['member_name'] );
   ```

2. **Validation:** Validate all data before processing

   ```php
   if ( empty( $member_name ) || strlen( $member_name ) > 100 ) {
       return false;
   }
   ```

3. **Escaping:** Escape all output

   ```php
   echo esc_html( $member_name );
   echo wp_kses_post( $member_bio );
   ```

4. **Nonces:** Use nonces for form submissions
   ```php
   wp_nonce_field( 'to_team_submit', 'to_team_nonce' );
   ```

#### Database Operations

- Use `$wpdb->prepare()` for all queries
- Prefix all custom tables with `$wpdb->prefix`
- Use WordPress database functions when possible

#### Hooks and Filters

- Prefix all hooks with `to_team_`
- Document all hooks with proper DocBlocks
- Provide examples in hook documentation

### File Organization

- One class per file
- Use autoloading for classes
- Keep functions in separate includes
- Organize by functionality, not file type

### Documentation

- All functions must have DocBlocks
- Use @since tags for version tracking
- Include @param and @return documentation
- Add inline comments for complex logic

### Performance

- Cache expensive operations
- Use transients for temporary data
- Minimize database queries
- Optimize for mobile and slow connections

## Code Review Guidelines

The following instructions are only to be applied when performing a code review.

### README updates

* [ ] The new file should be added to the `README.md`.

### Prompt file guide

**Only apply to files that end in `.prompt.md`**

* [ ] The prompt has markdown front matter.
* [ ] The prompt has a `mode` field specified of either `agent` or `ask`.
* [ ] The prompt has a `description` field.
* [ ] The `description` field is not empty.
* [ ] The `description` field value is wrapped in single quotes.
* [ ] The file name is lower case, with words separated by hyphens.
* [ ] Encourage the use of `tools`, but it's not required.
* [ ] Strongly encourage the use of `model` to specify the model that the prompt is optimised for.

### Instruction file guide

**Only apply to files that end in `.instructions.md`**

* [ ] The instruction has markdown front matter.
* [ ] The instruction has a `description` field.
* [ ] The `description` field is not empty.
* [ ] The `description` field value is wrapped in single quotes.
* [ ] The file name is lower case, with words separated by hyphens.
* [ ] The instruction has an `applyTo` field that specifies the file or files to which the instructions apply. If they wish to specify multiple file paths they should formated like `'**.js, **.ts'`.

### Chat Mode file guide

**Only apply to files that end in `.chatmode.md`**

* [ ] The chat mode has markdown front matter.
* [ ] The chat mode has a `description` field.
* [ ] The `description` field is not empty.
* [ ] The `description` field value is wrapped in single quotes.
* [ ] The file name is lower case, with words separated by hyphens.
* [ ] Encourage the use of `tools`, but it's not required.
* [ ] Strongly encourage the use of `model` to specify the model that the chat mode is optimised for.