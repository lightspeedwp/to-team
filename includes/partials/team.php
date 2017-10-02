<div class="uix-field-wrapper">

	<ul class="ui-tab-nav">
		<li><a href="#ui-general" class="active"><?php esc_html_e('General','to-team'); ?></a></li>
		<?php if(class_exists('LSX_TO_Search')) { ?>
			<li><a href="#ui-search"><?php esc_html_e('Search','to-team'); ?></a></li>
		<?php } ?>
		<li><a href="#ui-placeholders"><?php esc_html_e('Placeholders','to-team'); ?></a></li>
		<li><a href="#ui-archives"><?php esc_html_e('Archives','to-team'); ?></a></li>
		<li><a href="#ui-single"><?php esc_html_e('Single','to-team'); ?></a></li>
	</ul>

	<div id="ui-general" class="ui-tab active">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_team_tab_content','team','general'); ?>
			</tbody>
		</table>
	</div>

	<?php if(class_exists('LSX_TO_Search')) { ?>
		<div id="ui-search" class="ui-tab">
			<table class="form-table">
				<tbody>
				<?php do_action('lsx_to_framework_team_tab_content','team','search'); ?>
				</tbody>
			</table>
		</div>
	<?php } ?>

	<div id="ui-placeholders" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_team_tab_content','team','placeholders'); ?>
			</tbody>
		</table>
	</div>

	<div id="ui-archives" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_team_tab_content','team','archives'); ?>
			</tbody>
		</table>
	</div>

	<div id="ui-single" class="ui-tab">
		<table class="form-table">
			<tbody>
			<?php do_action('lsx_to_framework_team_tab_content','team','single'); ?>
			</tbody>
		</table>
	</div>
	<?php do_action('lsx_to_framework_team_tab_bottom','team'); ?>
</div>
