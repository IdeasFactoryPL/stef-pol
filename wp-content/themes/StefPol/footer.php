<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package Openstrap
 * @subpackage Openstrap
 * @since Openstrap 0.1
 */
?>
</div> <!-- #main-row-->
</div> <!-- .container #main-container-->
</div> <!-- #wrap -->

<footer>
	<div id="footer">
		<div class="container footer-nav ">
			<div class="footer-navigtion">
				<?php wp_nav_menu( array( 'theme_location'=> 'footer-menu', 'menu_class' => 'list-inline', 'depth' =>1, 'container' => false, 'fallback_cb' => false ) ); ?>
			</div>

		</div>
	</div>
	
		<?php
		/* A sidebar in the footer? Yep. You can customize
		 * your footer with three columns of widgets.
		 */
			get_sidebar( 'footer' );		
			$copyrighttxt = of_get_option('copyright_text');
			$copyrighttxt = ! empty($copyrighttxt) ? $copyrighttxt : __('&copy; ', 'openstrap') . __(date('Y')) . sprintf(' <a href="%1$s" title="%2$s" rel="home">%3$s</a>', esc_url(home_url( '/' )), get_bloginfo( 'name' ), get_bloginfo( 'name' ));
			$d=sprintf('<a href="%1$s" title="%2$s" rel="home">%3$s</a>', esc_url(home_url( '/' )), get_bloginfo( 'name' ), get_bloginfo( 'name' ));			
		?>

</footer> 
</div> <!-- #bodychild -->
<?php wp_footer(); ?>
</body>
</html>