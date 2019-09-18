<?php
/**
 * The Team Schema for Tours
 *
 * @package tour-operator
 */

/**
 * Returns schema Review data.
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
	 * Returns Review data.
	 *
	 * @return array $data Review data.
	 */
	public function generate() {
		$data = array(
			'@type'            => array(
				'Person',
			),
			'@id'              => $this->context->canonical . '#person',
			'name'             => $this->post->post_title,
			'description'      => wp_strip_all_tags( $this->post->post_content ),
			'url'              => $this->post_url,
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
		);

		if ( $this->context->site_represents_reference ) {
			$data['worksFor'] = $this->context->site_represents_reference;
			$data['memberOf'] = $this->context->site_represents_reference;
		}

		$data = $this->add_taxonomy_terms( $data, 'jobTitle', 'role' );
		$data = $this->add_custom_field( $data, 'email', 'contact_email' );
		$data = $this->add_custom_field( $data, 'telephone', 'contact_number' );
		$data = $this->add_products( $data );
		$data = $this->add_offers( $data, 'makesOffer' );
		$data = \lsx\legacy\Schema_Utils::add_image( $data, $this->context );
		return $data;
	}

	/**
	 * Adds the accommodation and tour products under the 'owns' parameter
	 *
	 * @param array $data
	 * @return array
	 */
	public function add_products( $data ) {
		$places_array = array();
		$places_array = $this->add_accommodation( $places_array );
		$places_array = $this->add_tours( $places_array );
		if ( ! empty( $places_array ) ) {
			$data['owns'] = $places_array;
		}
		return $data;
	}
}
