<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Reyl Lite
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <div class="post-content">

			<header class="entry-header">
				<?php if ( 'post' === get_post_type() ) : ?>
				<div class="metadata">
	                <?php reyl_lite_metadata(); ?>
	                <div class="clearfix"></div>
	            </div><!-- /metadata -->
            <?php endif; ?>
        		<?php the_title( sprintf( '<h2 class="post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        	</header><!-- .entry-header -->

        	<div class="entry-content">
				<?php
				if ( is_archive() ) {
					the_excerpt();
				}else{
					the_content();
				}
				?>

				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'reyl-lite' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->

			<div class="clearfix"></div>

		</div><!-- /post_content -->
</article><!-- #post-## -->
