<?php
/*
* Template Name: Update Child
*/
?>
<?php acf_form_head(); ?>
<?php get_header(); ?>
<?php $current_child = get_query_var( 'chid' ) ?>
    <div id="primary">
        <div id="content" role="main">

            <?php /* The loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>

                <?php acf_form(array(
                    'post_id'   => $current_child,
                    'submit_value'  => 'Update the post!'
                )); ?>

            <?php endwhile; ?>

        </div><!-- #content -->
    </div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
