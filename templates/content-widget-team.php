<?php
/**
 * Team Widget Content Part
 *
 * @package 	tour-operator
 * @category	team
 * @subpackage	widget
 */

global $disable_placeholder, $disable_text, $post;

$member_name = get_the_title();

$has_single = ! lsx_to_is_single_disabled();
$permalink = '';

if ( $has_single ) {
	$permalink = get_the_permalink();
} elseif ( ! is_post_type_archive( 'team' ) ) {
	$has_single = true;
	$permalink = get_post_type_archive_link( 'team' ) . '#team-' . $post->post_name;
}
?>
<article <?php post_class(); ?>>
	<?php if ( empty( $disable_placeholder ) ) { ?>
		<div class="lsx-to-widget-thumb">
			<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
				<?php lsx_thumbnail( 'lsx-thumbnail-square' ); ?>
			<?php if ( $has_single ) { ?></a><?php } ?>
		</div>
	<?php } ?>

	<h4 class="lsx-to-widget-title text-center">
		<?php if ( $has_single ) { ?><a href="<?php echo esc_url( $permalink ); ?>"><?php } ?>
			<?php the_title(); ?>
		<?php if ( $has_single ) { ?></a><?php } ?>
	</h4>

	<?php
		if ( empty( $disable_text ) ) {
			lsx_to_team_role( '<p class="lsx-to-widget-tagline text-center">', '</p>' );
		}
	?>

	<?php if ( $has_single ) { ?>
		<p class="text-center lsx-to-single-link"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html__( 'More about', 'to-team' ) . ' ' . esc_html( strtok( $member_name, ' ' ) ); ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a></p>
	<?php } ?>
</article>
