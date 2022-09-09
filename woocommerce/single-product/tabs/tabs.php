<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

	<ul class="theme-border-top" uk-accordion>
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
			<li class="theme-border-bottom">
				<a class="uk-accordion-title uk-text-boldest" href="#"><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></a>
				<div class="uk-accordion-content">
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
				</div>
			</li>
			<li class="theme-border-bottom" hidden>
				<a class="uk-accordion-title" href="#">Item 2</a>
				<div class="uk-accordion-content">
					<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor reprehenderit.</p>
				</div>
			</li>
			<li class="theme-border-bottom" hidden>
				<a class="uk-accordion-title" href="#">Item 3</a>
				<div class="uk-accordion-content">
					<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat proident.</p>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="woocommerce-tabs wc-tabs-wrapper uk-background-muted uk-padding-small uk-margin-medium-top" hidden>
		<ul class="uk-tab" data-uk-tab="{connect:'#my-id'}">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<li class="tab_id_<?php echo $product_tab['callback']; ?>"><a href=""><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		<ul id="my-id" class="uk-switcher">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<li class="tab_id_<?php echo $product_tab['callback']; ?>">
					<div class="tab-content padding-10">
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>
