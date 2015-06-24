/* Add a categories to attachments */
// function wptp_add_categories_to_attachments() {
//     register_taxonomy_for_object_type( 'category', 'attachment' );
// }
// add_action( 'init' , 'wptp_add_categories_to_attachments' );

/* Add a category filter to images */
// function asap_add_image_category_filter() {
//     $screen = get_current_screen();
//     if ( 'upload' == $screen->id ) {
//         $dropdown_options = array( 'show_option_all' => __( 'View all images categories', 'asap' ), 'hide_empty' => true, 'hierarchical' => true, 'orderby' => 'name', 'taxonomy' => 'image-categories' );
//         wp_dropdown_categories( $dropdown_options );
//     }
// }
// add_action( 'restrict_manage_posts', 'asap_add_image_category_filter' );

// function my_add_attachment_location_field( $form_fields, $post ) {
//     $field_value = get_post_meta( $post->ID, 'location', true );
//     $form_fields['location'] = array(
//         'value' => $field_value ? $field_value : '',
//         'label' => __( 'Location' ),
//         'helps' => __( 'Set a location for this attachment' )
//     );
//     unset($form_fields['media-categories']);
//     return $form_fields;
// }
// add_filter( 'attachment_fields_to_edit', 'my_add_attachment_location_field', 10, 2 );

// // $location = $_REQUEST['attachments'][$attachment_id]['location'];
// // print_r($location);


// function my_save_attachment_location( $attachment_id ) {
//     if ( isset( $_REQUEST['attachments'][$attachment_id]['location'] ) ) {
//         $location = $_REQUEST['attachments'][$attachment_id]['location'];
//         update_post_meta( $attachment_id, 'location', $location );
//     }
// }
// add_action( 'edit_attachment', 'my_save_attachment_location' );


<!-- Secondary Query -->

<?php
$ongoing_args = array(
    'posts_per_page' => -1,
    'post__not_in' => $query_ids,
    'post_type' => 'cpt_program',
    'meta_key' => 'prog_date_start',
    'meta_query' => array(
        array (
            'key' => 'prog_ongoing',
            'value' => true,
            'compare' => '=',
        ),
            
    ),
    'orderby' => array( 'meta_value' => 'ASC',  ),
);

$ongoing_query = new WP_Query( $ongoing_args );
    if ( $ongoing_query->have_posts() ) {
        echo '<ul>';
        while ( $ongoing_query->have_posts() ) {
            $ongoing_query->the_post();
            $age_min = get_field( 'prog_age_min' );
            $age_max = get_field( 'prog_age_max' );
            $experience = ( get_field( 'prog_activity_level' ) ? implode( ", ", get_field( 'prog_activity_level' ) ) : "All" );
            $cost = get_field( 'prog_cost' );
            if ( get_field( 'prog_date_start' ) ) {
                $date_start_obj = DateTime::createFromFormat( 'Ymd', get_field( 'prog_date_start' ) );
                $date_start = $date_start_obj->format('m/d/y');
            } else {
                $date_start = false;
            } ?>
            <li id="<?php the_id(); ?>" class="program-list <?php echo $loc->has_loc; ?> " >
                <h2>Ongoing: <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php the_excerpt(); ?>
                <div class="programs-meta-fields">
                    <ul>
                        <li>Age: <?php echo $age_min . '&#150;' . $age_max; ?></li>
                        <li>Date: <?php echo $date_start . $date_end . $ongoing; ?> </li>
                        <li>Cost: <?php echo '$' . $cost; ?></li>
                    </ul>
                    <ul>
                        <li class="distance <?php echo $loc->has_loc; ?>">Distance: Contact organization for location.</li>
                        <li>Days: <?php echo implode( ", ", $days_offered ); ?> </li>
                        <li>Experience: <?php echo $experience; ?> </li>
                    </ul>
                </div>
            </li>  
        <?php
        }
        echo '</ul>';
    } else {
        // no posts found
    }
    /* Restore original Post Data */
    wp_reset_query();
?>
