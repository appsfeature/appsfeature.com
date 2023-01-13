<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reyl Lite
 */

?>

        <div class="clearfix"></div>
    </div><!-- /row -->
            
</div><!-- /#container -->

	<div class="sub-footer">
        <div class="container">
            <div class="row">

                <div class="col-md-5">
                    <p>
                    <?php esc_html_e( 'Powered by ', 'reyl-lite' ); ?><a href="<?php echo esc_url( __( 'https://wordpress.org/', 'reyl-lite' ) ); ?>"><?php esc_html_e( 'WordPress', 'reyl-lite' ); ?></a>. <?php esc_html_e( 'Reyl Lite designed by ', 'reyl-lite' ); ?><a rel="nofollow" rel="designer" href="<?php echo esc_url( __( 'https://www.quemalabs.com/', 'reyl-lite' ) ); ?>"><?php esc_html_e( 'Quema Labs', 'reyl-lite' ); ?></a>.
                    </p>
                </div>
                <div class="col-md-7">
                    <?php get_template_part( '/template-parts/social-menu', 'footer' ); ?>
                </div>

            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .sub-footer -->


<?php wp_footer(); ?>

</body>
</html>