<?php
/**
 * Team Content Part
 *
 * @package  tour-operator
 * @category team
 */

global $lsx_to_archive, $post;

if ( 1 !== $lsx_to_archive ) {
	$lsx_to_archive = false;
}
?>

<?php lsx_entry_before(); ?>

<article id="team-<?php echo esc_attr( $post->post_name ); ?>" <?php post_class( 'lsx-to-archive-container' ); ?>>
	<?php lsx_entry_top(); ?>

	<?php if ( is_single() && false === $lsx_to_archive ) { ?>

		<div <?php lsx_to_entry_class( 'entry-content' ); ?>>
			<div class="lsx-to-section-inner">
				<h2 class="lsx-to-team-name"><?php the_title(); ?></h2>
				<?php lsx_to_team_role( '<h5 class="lsx-to-team-job-title">', '</h5>' ); ?>

				<div class="lsx-to-team-content"><?php the_content(); ?></div>

				<ul class="lsx-to-team-contact list-inline">
					<?php
						lsx_to_team_contact_number( '<li><i class="fa fa-phone orange"></i> ', '</li>' );
						lsx_to_team_contact_email( '<li><i class="fa fa-envelope orange"></i> ', '</li>' );
						lsx_to_team_contact_skype( '<li><i class="fa fa-skype orange"></i> ', '</li>' );
					?>
				</ul>
			</div>
		</div>

	<?php } elseif ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_text'] ) ) { ?>

		<div <?php lsx_to_entry_class( 'entry-content' ); ?>><?php
			lsx_to_entry_content_top();
			the_excerpt();
			lsx_to_entry_content_bottom();
		?></div>

	<?php } ?>

	<?php lsx_entry_bottom(); ?>

</article>

<?php lsx_entry_after();
