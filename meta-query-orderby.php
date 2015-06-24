<?php
/*
* Template Name: meta_query_orderby
*/
get_header();

// Query arguments



$args = array(
    'post_type' => 'cpt_program',
    'meta_key' => 'prog_date_start',
    'orderby' => 'menu_order meta_value_num',
    'order' => 'ASC',
    'meta_query' => array (
        array (
            'meta_key' => 'prog_ongoing',
         ),
        array (
            'meta_key' => 'prog_date_start'
        )
    ),
);

 
// The query
$meta_query = new WP_Query( $args );
//var_dump($meta_query);
if ( $meta_query->have_posts() ) {
        echo '<ul>';
        while ( $meta_query->have_posts() ) {
            $meta_query->the_post();

            // $age_min = get_field( 'prog_age_min' );
            // $age_max = get_field( 'prog_age_max' );
            // $experience = ( get_field( 'prog_activity_level' ) ? implode( ", ", get_field( 'prog_activity_level' ) ) : "All" );
            $cost = get_field( 'prog_cost' );
            if ( get_field( 'prog_date_start' ) ) {
                $date_start_obj = DateTime::createFromFormat( 'Ymd', get_field( 'prog_date_start' ) );
                $date_start = $date_start_obj->format('m/d/y');
            } else {
                $date_start = false;
            } ?>
            <li id="<?php the_id(); ?>" class="program-list <?php echo $loc->has_loc; ?> " >
                <h2<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="programs-meta-fields">
                    <ul>
                        <!-- <li>Age: <?php echo $age_min . '&#150;' . $age_max; ?></li> -->
                        <li>Date: <?php echo $date_start; ?> </li>
                        <li>Cost: <?php echo '$' . $cost; ?></li>
                        <li><?php the_field( 'prog_ongoing' ); ?>
                    </ul>
                    <!-- <ul>
                        <li class="distance <?php echo $loc->has_loc; ?>">Distance: Contact organization for location.</li>
                        <li>Days: <?php echo implode( ", ", $days_offered ); ?> </li>
                        <li>Experience: <?php echo $experience; ?> </li>
                    </ul> -->
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