<?php
/*
* Template Name: Query Test
*/
get_header();
?>

<?php
// List all posts with custom field Color, sorted by the value of custom field Display_Order
// does not exclude any 'post_type'
// assumes each post has just one custom field for Color, and one for Display_Order
$meta_key1 = 'prog_ongoing';
$meta_key2 = 'prog_date_start';
$start_date = date('Ymd');

$postids = $wpdb->get_col( $wpdb->prepare( 
    "
    SELECT      DISTINCT key1.post_id
    FROM        $wpdb->postmeta key1
    INNER JOIN  $wpdb->postmeta key2
                ON key2.post_id = key1.post_id
                AND key2.meta_key = %s
    WHERE       key1.meta_key = %s
                AND key1.meta_value is TRUE
                OR key2.meta_value >= %d
    ORDER BY    COALESCE(NULLIF(key1.meta_value, 0), 0) DESC, COALESCE(NULLIF(key2.meta_value, ''), $start_date) ASC, key2.meta_value ASC 
    ",
    $meta_key2,
    $meta_key1,
    $start_date
) ); 

/** UNION Doesn't work **/


// $postids = $wpdb->get_col( $wpdb->prepare( 
//     "
//     SELECT      DISTINCT key2.post_id
//     FROM        $wpdb->postmeta key2
//     WHERE       key2.meta_key = %s
//                 AND key2.meta_value >= %s
//     ORDER BY    COALESCE(NULLIF(key2.meta_value, ''), 20150619) ASC 
//     ",
//     $meta_key2,
//     $start_date
// ) );

if ( $postids ) 
{
    echo "List of {$meta_key1} posts, sorted by {$meta_key2}";
    foreach ( $postids as $id ) 
    {
        $post = get_post( intval( $id ) );
        setup_postdata( $post );
        $og = get_field( 'prog_ongoing' );
        ?>
        <p>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                <?php the_title(); ?>
            </a>
            <span><?php the_field( 'prog_cost' ) ?> : <?php the_field( 'prog_date_start' ); ?> : <?php echo $og; ?></span>
        </p>
        <?php
    }
}
?>

<?php get_footer(); ?>