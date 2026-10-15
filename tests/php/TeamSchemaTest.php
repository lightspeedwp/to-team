<?php
/**
 * Regression tests for the Team Person schema piece.
 *
 * @package to-team
 */

use PHPUnit\Framework\TestCase;

require_once dirname( __DIR__, 2 ) . '/classes/class-to-team-schema.php';

/**
 * Tests for LSX_TO_Team_Schema.
 */
class TeamSchemaTest extends TestCase {

	const TEAM_ID = 10;

	/**
	 * Sets up a published team member.
	 */
	protected function setUp(): void {
		lsx_to_test_reset();
		lsx_to_test_add_post(
			self::TEAM_ID,
			array(
				'post_title'   => 'Jane &#8216;Safari&#8217; Smith',
				'post_type'    => 'team',
				'post_content' => '<p>Expert <strong>guide</strong>.</p>',
			)
		);
	}

	/**
	 * Builds the piece with a Yoast-like context.
	 *
	 * @param array $overrides Context properties to override.
	 * @return array Generated schema.
	 */
	private function generate( array $overrides = array() ) {
		$context = (object) array_merge(
			array(
				'id'                        => self::TEAM_ID,
				'has_image'                 => false,
				'site_represents_reference' => array( '@id' => 'https://example.test/#organization' ),
			),
			$overrides
		);
		return ( new LSX_TO_Team_Schema( $context ) )->generate();
	}

	public function test_person_type_is_a_string_and_id_is_unique_per_post() {
		$data = $this->generate();

		$this->assertSame( 'Person', $data['@type'] );
		$this->assertSame( 'https://example.test/team/10/#/schema/person/10', $data['@id'] );
		$this->assertSame( 'https://example.test/team/10/', $data['url'] );
		$this->assertSame( array( '@id' => 'https://example.test/team/10/' ), $data['mainEntityOfPage'] );
	}

	public function test_name_and_description_are_plain_text() {
		$data = $this->generate();

		$this->assertSame( "Jane \u{2018}Safari\u{2019} Smith", $data['name'] );
		$this->assertSame( 'Expert guide.', $data['description'] );
	}

	public function test_works_for_is_kept_and_member_of_and_owns_are_not_emitted() {
		$data = $this->generate();

		$this->assertSame( array( '@id' => 'https://example.test/#organization' ), $data['worksFor'] );
		$this->assertArrayNotHasKey( 'memberOf', $data );
		$this->assertArrayNotHasKey( 'owns', $data );
	}

	public function test_works_for_is_omitted_without_a_site_organisation() {
		$data = $this->generate( array( 'site_represents_reference' => false ) );

		$this->assertArrayNotHasKey( 'worksFor', $data );
	}

	public function test_job_title_is_a_readable_string_without_uncategorized() {
		lsx_to_test_add_terms( self::TEAM_ID, 'role', array( 'Guide', 'Uncategorized', 'Consultant' ) );

		$data = $this->generate();

		$this->assertSame( 'Guide, Consultant', $data['jobTitle'] );
	}

	public function test_job_title_is_omitted_without_roles() {
		lsx_to_test_add_terms( self::TEAM_ID, 'role', array( 'Uncategorized' ) );

		$this->assertArrayNotHasKey( 'jobTitle', $this->generate() );
	}

	public function test_valid_email_and_telephone_are_emitted() {
		lsx_to_test_add_meta(
			self::TEAM_ID,
			array(
				'contact_email'  => 'jane@example.test',
				'contact_number' => '+27 21 555 0100',
			)
		);

		$data = $this->generate();

		$this->assertSame( 'jane@example.test', $data['email'] );
		$this->assertSame( '+27 21 555 0100', $data['telephone'] );
	}

	public function test_malformed_email_is_omitted() {
		lsx_to_test_add_meta( self::TEAM_ID, array( 'contact_email' => 'not-an-email' ) );

		$this->assertArrayNotHasKey( 'email', $this->generate() );
	}

	public function test_same_as_only_lists_unique_absolute_profile_urls() {
		lsx_to_test_add_meta(
			self::TEAM_ID,
			array(
				'facebook'   => 'https://www.facebook.com/jane',
				'twitter'    => '@jane',
				'googleplus' => 'https://plus.google.com/+jane',
				'linkedin'   => 'https://www.linkedin.com/in/jane',
				'pinterest'  => 'https://www.facebook.com/jane',
				'skype'      => 'live:jane',
			)
		);

		$data = $this->generate();

		$this->assertSame(
			array( 'https://www.facebook.com/jane', 'https://www.linkedin.com/in/jane' ),
			$data['sameAs']
		);
	}

	public function test_same_as_rejects_non_http_schemes() {
		lsx_to_test_add_meta( self::TEAM_ID, array( 'facebook' => 'javascript://alert(1)' ) );

		$this->assertArrayNotHasKey( 'sameAs', $this->generate() );
	}

	public function test_associated_items_use_additional_property_with_published_unique_items() {
		lsx_to_test_add_post( 20, array( 'post_title' => 'Cape &#8211; Safari', 'post_type' => 'tour' ) );
		lsx_to_test_add_post( 21, array( 'post_title' => 'Draft Tour', 'post_type' => 'tour', 'post_status' => 'draft' ) );
		lsx_to_test_add_post( 30, array( 'post_title' => 'Ben&#8217;s Lodge', 'post_type' => 'accommodation' ) );
		lsx_to_test_add_meta(
			self::TEAM_ID,
			array(
				'tour_to_team'          => array( 20, 21, 20, 999 ),
				'accommodation_to_team' => array( 30 ),
			)
		);

		$data = $this->generate();

		$this->assertSame(
			array(
				array(
					'@type' => 'PropertyValue',
					'name'  => 'Associated Accommodation',
					'value' => "Ben\u{2019}s Lodge",
				),
				array(
					'@type' => 'PropertyValue',
					'name'  => 'Associated Tours',
					'value' => "Cape \u{2013} Safari",
				),
			),
			$data['additionalProperty']
		);
	}

	public function test_no_offers_are_emitted_for_linked_specials() {
		lsx_to_test_add_post( 40, array( 'post_type' => 'special' ) );
		lsx_to_test_add_meta(
			40,
			array(
				'price'      => '1000',
				'price_type' => 'per_person',
			)
		);
		lsx_to_test_add_meta( self::TEAM_ID, array( 'special_to_team' => array( 40 ) ) );

		$data = $this->generate();

		$this->assertArrayNotHasKey( 'makesOffer', $data );
		$this->assertArrayNotHasKey( 'offers', $data );
		$this->assertStringNotContainsString( 'PriceSpecification', (string) json_encode( $data ) );
	}

	public function test_image_references_yoast_primary_image_when_available() {
		$data = $this->generate( array( 'has_image' => true ) );

		$this->assertSame( array( '@id' => 'https://example.test/team/10/#primaryimage' ), $data['image'] );
	}

	public function test_image_falls_back_to_featured_image_without_yoast() {
		lsx_to_test_add_meta( self::TEAM_ID, array( '_test_thumbnail_url' => 'https://example.test/jane.jpg' ) );
		$GLOBALS['lsx_to_test']['singular'] = 'team';

		$piece = new LSX_TO_Team_Schema( (object) array( 'id' => self::TEAM_ID ) );
		$data  = $piece->generate();

		$this->assertTrue( $piece->is_needed() );
		$this->assertSame( 'https://example.test/jane.jpg', $data['image'] );
	}

	/**
	 * Non-public post statuses.
	 *
	 * @return array
	 */
	public static function unpublished_statuses() {
		return array(
			'draft'   => array( 'draft' ),
			'pending' => array( 'pending' ),
			'private' => array( 'private' ),
			'future'  => array( 'future' ),
		);
	}

	/**
	 * @dataProvider unpublished_statuses
	 *
	 * @param string $status Post status.
	 */
	public function test_unpublished_team_members_emit_nothing( $status ) {
		$GLOBALS['lsx_to_test']['posts'][ self::TEAM_ID ]->post_status = $status;

		$this->assertSame( array(), $this->generate() );
	}

	public function test_missing_post_emits_nothing() {
		$this->assertSame( array(), $this->generate( array( 'id' => 999 ) ) );
	}

	public function test_without_context_the_queried_post_is_used_not_the_loop_post() {
		lsx_to_test_add_post( 50, array( 'post_title' => 'Some other post' ) );
		$GLOBALS['lsx_to_test']['queried'] = self::TEAM_ID;
		$GLOBALS['lsx_to_test']['loop_id'] = 50;

		$data = ( new LSX_TO_Team_Schema() )->generate();

		$this->assertSame( 'https://example.test/team/10/#/schema/person/10', $data['@id'] );
	}

	public function test_without_context_falls_back_to_the_loop_post() {
		$GLOBALS['lsx_to_test']['loop_id'] = self::TEAM_ID;

		$data = ( new LSX_TO_Team_Schema() )->generate();

		$this->assertSame( 'https://example.test/team/10/#/schema/person/10', $data['@id'] );
	}
}
