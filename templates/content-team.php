<?php
/**
 * Team Content Part
 * 
 * @package 	tour-operator
 * @category	team
 */
global $lsx_to_archive;
if(1 !== $lsx_to_archive){
	$lsx_to_archive = false;
}
?>
<?php lsx_entry_before(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php lsx_entry_top(); ?>
		
		<div <?php lsx_to_entry_class( 'entry-content' ); ?>>
			<?php if ( is_singular() && false === $lsx_to_archive ) : ?>
				<div class="single-main-info">
					<h3><?php esc_html_e( 'Summary', 'lsx-activities' ); ?></h3>
					
					<div class="meta taxonomies">
						<?php lsx_to_team_role('<div class="meta role">'.__('Role','to-team').': ','</div>'); ?>
						<?php lsx_to_connected_tours('<div class="meta tours">'.__('Tours','to-team').': ','</div>'); ?>
						<?php lsx_to_connected_accommodation('<div class="meta accommodation">'.__('Accommodation','to-team').': ','</div>'); ?>						
						<?php lsx_to_connected_destinations('<div class="meta destination">'.__('Location','to-team').': ','</div>'); ?>	
					</div>

					<?php lsx_to_sharing(); ?>
				</div>

				<?php the_content(); ?>
				
			<?php else : ?>
			
				<?php the_excerpt(); ?>
				
			<?php endif; ?>

		</div><!-- .entry-content -->		
		
	<?php lsx_entry_bottom(); ?>			
	
</article><!-- #post-## -->

<?php lsx_entry_after();
