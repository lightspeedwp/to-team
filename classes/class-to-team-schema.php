<?php
/**
 * Team Schema Graph Piece
 *
 * Outputs schema.org Person markup for the `team` post type. When Yoast SEO
 * is active the class is registered as a graph piece via
 * LSX_TO_Team::add_graph_pieces(). When Yoast is inactive
 * LSX_TO_Team::output_standalone_schema() calls generate() directly.
 *
 * The piece is self-contained and depends only on the Tour Operator core
 * schema helpers (lsx\schema\Helpers, core 2.2+), following the same pattern
 * as the core Trip, Accommodation and Destination pieces.
 *
 * @package to-team
 */

use lsx\schema\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates schema.org Person data for a single team member.
 */
class LSX_TO_Team_Schema {

	/**
	 * Yoast schema context (null when Yoast is not active).
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null
	 */
	protected $context;

	/**
	 * Post ID being processed.
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * Post object being processed.
	 *
	 * @var \WP_Post|null
	 */
	protected $post;

	/**
	 * Canonical URL for the current post.
	 *
	 * @var string
	 */
	protected $canonical;

	/**
	 * Social profile meta keys that may hold a public profile URL.
	 *
	 * The defunct Google+ field and the Skype field (an account name, not a
	 * URL) are deliberately excluded.
	 *
	 * @var string[]
	 */
	const SOCIAL_FIELDS = array( 'facebook', 'twitter', 'linkedin', 'pinterest' );

	/**
	 * Constructor.
	 *
	 * Without a Yoast context the post is taken from the queried object, which
	 * stays reliable in wp_head even if a custom loop has changed the global
	 * $post, falling back to get_the_ID().
	 *
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null $context Yoast context, or null when Yoast is inactive.
	 */
	public function __construct( $context = null ) {
		$this->context = $context;
		if ( null !== $context ) {
			$this->post_id = (int) $context->id;
		} else {
			$queried_id    = (int) get_queried_object_id();
			$this->post_id = $queried_id > 0 ? $queried_id : (int) get_the_ID();
		}
		$this->post      = get_post( $this->post_id );
		$this->canonical = (string) get_permalink( $this->post_id );
	}

	/**
	 * Determines whether this piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {
		return is_singular( 'team' );
	}

	/**
	 * Generates and returns the Person schema data array.
	 *
	 * Returns an empty array for a missing post or a team member that is not
	 * published, so drafts, private posts and previews never emit a Person.
	 *
	 * @return array Schema.org Person data, or an empty array.
	 */
	public function generate() {
		if ( ! is_object( $this->post ) || 'publish' !== get_post_status( $this->post_id ) ) {
			return array();
		}

		$data = array(
			'@type'            => 'Person',
			'@id'              => $this->canonical . '#/schema/person/' . $this->post_id,
			'name'             => $this->get_plain_title( $this->post_id ),
			'url'              => $this->canonical,
			'mainEntityOfPage' => array( '@id' => $this->canonical ),
		);

		$description = $this->get_description();
		if ( '' !== $description ) {
			$data['description'] = $description;
		}

		if ( null !== $this->context && ! empty( $this->context->site_represents_reference ) ) {
			$data['worksFor'] = $this->context->site_represents_reference;
		}

		$data = $this->add_job_title( $data );
		$data = $this->add_contact_details( $data );
		$data = $this->add_same_as( $data );
		$data = $this->add_associated_products( $data );
		$data = $this->add_image( $data );

		/**
		 * Filter the complete Team Person schema data array.
		 *
		 * @param array $data    Person schema data.
		 * @param int   $post_id Current team post ID.
		 */
		return (array) apply_filters( 'lsx_to_schema_team_data', $data, $this->post_id );
	}

	/**
	 * Gets the description from the filtered post content as plain text.
	 *
	 * @return string Plain-text description.
	 */
	protected function get_description() {
		if ( ! is_object( $this->post ) ) {
			return '';
		}
		return Helpers::strip_to_text( apply_filters( 'the_content', $this->post->post_content ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/**
	 * Gets a post title as plain text, with texturised entities decoded.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	protected function get_plain_title( $post_id ) {
		return Helpers::strip_to_text( get_the_title( $post_id ) );
	}

	/**
	 * Adds jobTitle as a single text value from the role taxonomy terms,
	 * excluding the default "Uncategorized" term.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_job_title( array $data ) {
		$terms = get_the_terms( $this->post_id, 'role' );
		if ( ! is_array( $terms ) ) {
			return $data;
		}

		$names = array();
		foreach ( $terms as $term ) {
			// Compare against the WordPress core translation of the default term.
			// phpcs:ignore WordPress.WP.I18n.MissingArgDomainDefault
			if ( __( 'Uncategorized' ) !== $term->name ) {
				$names[] = Helpers::strip_to_text( $term->name );
			}
		}

		$names = array_filter( array_unique( $names ) );
		if ( ! empty( $names ) ) {
			$data['jobTitle'] = implode( ', ', $names );
		}

		return $data;
	}

	/**
	 * Adds the email (only when well-formed) and telephone contact details.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_contact_details( array $data ) {
		$email = trim( Helpers::get_meta( $this->post_id, 'contact_email' ) );
		if ( '' !== $email && is_email( $email ) ) {
			$data['email'] = sanitize_email( $email );
		}

		$telephone = sanitize_text_field( Helpers::get_meta( $this->post_id, 'contact_number' ) );
		if ( '' !== $telephone ) {
			$data['telephone'] = $telephone;
		}

		return $data;
	}

	/**
	 * Adds sameAs from the social profile fields. Only absolute http(s) URLs
	 * are used, so handles such as "@name" are skipped. Duplicates are removed.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_same_as( array $data ) {
		/**
		 * Filter the social profile meta keys used for the Person sameAs list.
		 *
		 * @param string[] $fields  Meta keys.
		 * @param int      $post_id Current team post ID.
		 */
		$fields  = (array) apply_filters( 'lsx_to_team_schema_social_fields', self::SOCIAL_FIELDS, $this->post_id );
		$same_as = array();

		foreach ( $fields as $field ) {
			$url = $this->get_profile_url( Helpers::get_meta( $this->post_id, $field ) );
			if ( '' !== $url && ! in_array( $url, $same_as, true ) ) {
				$same_as[] = $url;
			}
		}

		if ( ! empty( $same_as ) ) {
			$data['sameAs'] = $same_as;
		}

		return $data;
	}

	/**
	 * Returns a value as a clean absolute http(s) URL, or an empty string.
	 *
	 * @param string $value Raw field value.
	 * @return string
	 */
	protected function get_profile_url( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value || false === filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return '';
		}

		$scheme = strtolower( (string) wp_parse_url( $value, PHP_URL_SCHEME ) );
		if ( 'http' !== $scheme && 'https' !== $scheme ) {
			return '';
		}

		return esc_url_raw( $value );
	}

	/**
	 * Adds the associated tours and accommodation as knowsAbout nodes, since
	 * additionalProperty is not valid on a Person.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_associated_products( array $data ) {
		$items = array_merge(
			$this->get_associated_items( 'tour_to_team', 'TouristTrip' ),
			$this->get_associated_items( 'accommodation_to_team', 'LodgingBusiness' )
		);

		if ( ! empty( $items ) ) {
			$data['knowsAbout'] = $items;
		}

		return $data;
	}

	/**
	 * Builds typed nodes, with name and URL, from a list of related post IDs.
	 * Only published posts are included, each once.
	 *
	 * @param string $meta_key The meta key storing the related post IDs.
	 * @param string $type     Schema.org type for the nodes.
	 * @return array[]
	 */
	protected function get_associated_items( $meta_key, $type ) {
		$items = array();
		$ids   = array_unique( array_map( 'intval', Helpers::get_meta_array( $this->post_id, $meta_key ) ) );
		foreach ( $ids as $related_id ) {
			if ( $related_id < 1 || 'publish' !== get_post_status( $related_id ) ) {
				continue;
			}
			$title = $this->get_plain_title( $related_id );
			if ( '' !== $title ) {
				$items[] = array(
					'@type' => $type,
					'name'  => $title,
					'url'   => (string) get_permalink( $related_id ),
				);
			}
		}

		return $items;
	}

	/**
	 * Adds the image from the Yoast context, falling back to the featured image.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_image( array $data ) {
		if ( null !== $this->context && ! empty( $this->context->has_image ) ) {
			$data['image'] = array( '@id' => $this->canonical . Helpers::primary_image_hash() );
		} else {
			$thumbnail_url = get_the_post_thumbnail_url( $this->post_id, 'large' );
			if ( $thumbnail_url ) {
				$data['image'] = esc_url( $thumbnail_url );
			}
		}
		return $data;
	}
}
