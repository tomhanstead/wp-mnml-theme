
		</main>

		<footer class="footer" role="contentinfo">
			<?php
				// https://developer.wordpress.org/reference/functions/wp_nav_menu/
				$args = array(
					'container' => 'nav',
					'container_class' => 'footer-menu',
					'fallback_cb' => 'header',
					'menu' => 'footer',
					'theme_location' => 'footer'
				);
				wp_nav_menu( $args );
			?>
		</footer>

	</body>
</html>