<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Violet_Cards
 */

if (!is_active_sidebar('sidebar-1')) {
	return;
}
?>

<aside id="secondary" class="widget-area p-4 col-3">
	<?php dynamic_sidebar('sidebar-1'); ?>

	<div class="site-info border-top">
		<?php printf(_x('&copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'violet-cards'), esc_attr(date_i18n(__('Y', 'violet-cards'))), '<a href="' . esc_url(home_url('/')) . '">' . esc_attr(get_bloginfo('name', 'display')) . '</a>'); ?>
		- <a href="mailto:info@violet.cards"><i class="fas fa-envelope"></i></a>
	</div><!-- .site-info -->
</aside><!-- #secondary -->