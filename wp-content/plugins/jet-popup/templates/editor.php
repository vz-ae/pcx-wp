<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$popup_id = get_the_ID();

$uniq_popup_id = 'jet-popup-' . $popup_id;

$popup_settings_main = jet_popup()->settings->get_popup_settings( $popup_id );

$close_button_html = '';

$use_close_button = isset( $popup_settings_main['use_close_button'] ) ? filter_var( $popup_settings_main['use_close_button'], FILTER_VALIDATE_BOOLEAN ) : true;

if ( isset( $popup_settings_main['close_button_icon'] ) && $use_close_button ) {
	$default_close_button_html = sprintf( '<div class="jet-popup__close-button">%s</div>', \Jet_Popup_Utils::get_default_svg_html( 'close' ) );
	$close_button_html = \Jet_Popup_Utils::get_svg_icon_html( $popup_settings_main[ 'close_button_icon' ], $default_close_button_html, [ 'class' => 'jet-popup__close-button' ], true );
	$close_button_html = apply_filters( 'jet-popup/popup-generator/close-icon-html', $close_button_html, $popup_id, $popup_settings_main, 'close_button_icon' );

	if ( empty( $close_button_html ) ) {
		$close_button_html = $default_close_button_html;
	}
}

$use_overlay = isset( $popup_settings_main['use_overlay'] ) ? filter_var( $popup_settings_main['use_overlay'], FILTER_VALIDATE_BOOLEAN ) : true;

$overlay_html = sprintf(
	'<div class="jet-popup__overlay %s"></div>',
	! $use_overlay ? 'hidden' : ''
);

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo wp_get_document_title(); ?></title>
		<?php endif; ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<div class="jet-popup-edit-area">
		<div id="<?php echo $uniq_popup_id; ?>" class="jet-popup jet-popup--edit-mode">
			<div class="jet-popup__inner">
				<?php echo $overlay_html; ?>
				<div class="jet-popup__container">
					<?php echo $close_button_html; ?>
					<div class="jet-popup__container-inner">
					<div class="jet-popup__container-overlay"></div><?php

					do_action( 'jet-popup/blank-page/before-content' );

					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;

					do_action( 'jet-popup/blank-page/after-content' );

					wp_footer();
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="secondary"></div>
	</body>
</html>
