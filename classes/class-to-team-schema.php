<?php
/**
 * The Team Schema for Tours
 *
 * @package tour-operator
 */

/**
 * Returns schema Person data for Team posts.
 *
 * @since 10.2
 */
class LSX_TO_Team_Schema extends LSX_TO_Schema_Graph_Piece {

	/**
	 * Constructor.
	 *
	 * @param \WPSEO_Schema_Context $context A value object with context variables.
	 */
	public function __construct( WPSEO_Schema_Context $context ) {
		$this->post_type = 'team';
		parent::__construct( $context );
	}

	/**
	 * Returns Person data.
	 *
	 * @return array $data Person data.
	 */
	public function generate() {
		$data = array(
			'@type'            => 'Person',
			'@id'              => $this->context->canonical . '#/schema/person/' . $this->post->ID,
			'name'             => get_the_title( $this->post->ID ),
			'description'      => \lsx\schema\Helpers::strip_to_text( apply_filters( 'the_content', $this->post->post_content ) ),
			'url'              => $this->post_url,
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
		);

		if ( $this->context->site_represents_reference ) {
			$data['worksFor'] = $this->context->site_represents_reference;
		}

		$data = $this->add_taxonomy_terms( $data, 'jobTitle', 'role' );
		$data = $this->add_custom_field( $data, 'email', 'contact_email' );
		$data = $this->add_custom_field( $data, 'telephone', 'contact_number' );
		$data = $this->add_same_as( $data );
		$data = $this->add_associated_products( $data );
		$data = $this->add_offers( $data, 'makesOffer' );
		$data = \lsx\legacy\Schema_Utils::add_image( $data, $this->context );
		return $data;
	}

	/**
	 * Adds the sameAs entries for any populated social profile fields.
	 *
	 * @param array $data Person data.
	 * @return array $data Person data.
	 */
	public function add_same_as( $data ) {
		$social_fields = array( 'facebook', 'twitter', 'googleplus', 'linkedin', 'pinterest' );
		$same_as       = array();

		foreach ( $social_fields as $social_field ) {
			$value = get_post_meta( $this->context->id, $social_field, true );
			if ( false !== $value && '' !== $value ) {
				$same_as[] = $value;
			}
		}

		if ( ! empty( $same_as ) ) {
			$data['sameAs'] = $same_as;
		}

		return $data;
	}

	/**
	 * Adds the associated tours and accommodation as additionalProperty values,
	 * replacing the previous semantically incorrect 'owns' output.
	 *
	 * @param array $data Person data.
	 * @return array $data Person data.
	 */
	public function add_associated_products( $data ) {
		$data = $this->add_associated_property( $data, 'accommodation_to_' . $this->post_type, __( 'Associated Accommodation', 'to-team' ) );
		$data = $this->add_associated_property( $data, 'tour_to_' . $this->post_type, __( 'Associated Tours', 'to-team' ) );
		return $data;
	}

	/**
	 * Builds a single additionalProperty entry from a list of related post IDs.
	 *
	 * @param array  $data     Person data.
	 * @param string $meta_key The meta key storing the related post IDs.
	 * @param string $label    The additionalProperty name/label.
	 * @return array $data Person data.
	 */
	public function add_associated_property( $data, $meta_key, $label ) {
		$ids    = get_post_meta( $this->context->id, $meta_key, false );
		$titles = array();

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $id ) {
				if ( '' !== $id ) {
					$title = get_the_title( $id );
					if ( '' !== $title ) {
						$titles[] = $title;
					}
				}
			}
		}

		if ( ! empty( $titles ) ) {
			$data['additionalProperty'][] = array(
				'@type' => 'PropertyValue',
				'name'  => $label,
				'value' => implode( ', ', $titles ),
			);
		}

		return $data;
	}
}
