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
	 * Constructor.
	 *
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null $context Yoast context, or null when Yoast is inactive.
	 */
	public function __construct( $context = null ) {
		$this->context   = $context;
		$this->post_id   = ( null !== $context ) ? (int) $context->id : (int) get_the_ID();
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
	 * @return array Schema.org Person data.
	 */
	public function generate() {
		$data = array(
			'@type'            => 'Person',
			'@id'              => $this->canonical . '#/schema/person/' . $this->post_id,
			'name'             => get_the_title( $this->post_id ),
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
	 * Adds jobTitle from the role taxonomy terms.
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
			$names[] = $term->name;
		}

		if ( ! empty( $names ) ) {
			$data['jobTitle'] = implode( ',', $names );
		}

		return $data;
	}

	/**
	 * Adds the email and telephone contact details.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_contact_details( array $data ) {
		$email = Helpers::get_meta( $this->post_id, 'contact_email' );
		if ( '' !== $email ) {
			$data['email'] = $email;
		}

		$telephone = Helpers::get_meta( $this->post_id, 'contact_number' );
		if ( '' !== $telephone ) {
			$data['telephone'] = $telephone;
		}

		return $data;
	}

	/**
	 * Adds the sameAs entries for any populated social profile fields.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_same_as( array $data ) {
		$same_as = array();
		foreach ( array( 'facebook', 'twitter', 'googleplus', 'linkedin', 'pinterest' ) as $field ) {
			$value = Helpers::get_meta( $this->post_id, $field );
			if ( '' !== $value ) {
				$same_as[] = $value;
			}
		}

		if ( ! empty( $same_as ) ) {
			$data['sameAs'] = $same_as;
		}

		return $data;
	}

	/**
	 * Adds the associated tours and accommodation as additionalProperty values.
	 *
	 * @param array $data Person data.
	 * @return array
	 */
	protected function add_associated_products( array $data ) {
		$data = $this->add_associated_property( $data, 'accommodation_to_team', __( 'Associated Accommodation', 'to-team' ) );
		$data = $this->add_associated_property( $data, 'tour_to_team', __( 'Associated Tours', 'to-team' ) );
		return $data;
	}

	/**
	 * Builds a single additionalProperty entry from a list of related post IDs.
	 *
	 * @param array  $data     Person data.
	 * @param string $meta_key The meta key storing the related post IDs.
	 * @param string $label    The additionalProperty name.
	 * @return array
	 */
	protected function add_associated_property( array $data, $meta_key, $label ) {
		$titles = array();
		foreach ( Helpers::get_meta_array( $this->post_id, $meta_key ) as $related_id ) {
			$title = get_the_title( (int) $related_id );
			if ( '' !== $title ) {
				$titles[] = $title;
			}
		}

		if ( ! empty( $titles ) ) {
			$data['additionalProperty'][] = Helpers::make_property_value( $label, implode( ', ', $titles ) );
		}

		return $data;
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
