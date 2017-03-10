<?php
	if ( post_password_required() || !comments_open()) {
			return;
	}

	global $tt_theme;
?>
<?php $tt_theme->comment_form(); ?>
<?php if ( have_comments() ) : ?>
<div class="post-box bg-white">
	<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
		<h3 id="comments" class="title-underlined text-center">
			<?php comments_popup_link('0 '. esc_html__('Comments', 'cre8or'), '1 '. esc_html__('Comment', 'cre8or'),'% '. esc_html__('Comments', 'cre8or'), '' ); ?>
		</h3>
	<?php endif; ?>
	<?php $tt_theme->comments(); ?>
</div>
<?php endif; ?>

<?php $tt_theme->comments_pagination();  ?>