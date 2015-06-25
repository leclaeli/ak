
<?php
/*
* Template Name: Search Query
*/
get_header();
?>

<?php require_once( 'inc/asap-query.php' ); ?>

<div>
    <form action="search-results/" method="get">
        <select name="sr" id="sort-results" onchange="this.form.submit()">
            <option>Sort Results</option>
            <option value="title_az">Sort by Title: A-Z</option>
            <option value="title_za">Sort by Title: Z-A</option>
            <option value="date">Sort by Date</option>
        </select>
    </form>
</div>
<div class="total-results"></div>
<div>
    <button id="map-view">Map View</button>
    <button id="list-view" class="clicked">List View</button>
</div>

<div id="container">
    <div id="programs-list" class="box">
    <?php
    $query = new WP_Query( $args );
    // echo '<pre>'; print_r($query); echo '</pre>'; 
    // $total_results = $query->found_posts;
        // The Loop
        if ( $query->have_posts() ) {
            echo '<ul>';
            $locations = array();
            $location_titles = array();
            $location_post_ids = array();
            $query_ids = array();
            
            while ( $query->have_posts() ) {
                $query->the_post();
                
                // ACF Fields
                $age_min = get_field( 'prog_age_min' );
                $age_max = get_field( 'prog_age_max' );
                if ( get_field( 'prog_date_start' ) ) {
                    $date_start_obj = DateTime::createFromFormat( 'Ymd', get_field( 'prog_date_start' ) );
                    $date_start = $date_start_obj->format('m/d/y');
                } else {
                    $date_start = false;
                }
                if ( get_field( 'prog_date_end' ) ) {
                    $date_end_obj = DateTime::createFromFormat( 'Ymd', get_field( 'prog_date_end' ) );
                    $date_end = '&#150;' . $date_end_obj->format('m/d/y');
                } else {
                    $date_end = "";
                }
                $cost = get_field( 'prog_cost' );
                $days_offered = get_field( 'prog_days_offered' );
                $experience = ( get_field( 'prog_activity_level' ) ? implode( ", ", get_field( 'prog_activity_level' ) ) : "All" );
                $organization = get_field( 'prog_organization' );
                $ongoing = get_field( 'prog_ongoing' );

                $loc = new Location(); // class in functions.php
                $prog_id = get_the_id();
                array_push( $locations, $loc->my_location );
                array_push( $query_ids, $prog_id );
                ?>

                <li id="<?php the_id(); ?>" class="program-list<?php echo $loc->has_loc; ?>" >
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php // the_excerpt(); ?>
                    <div class="programs-meta-fields">
                        <ul>
                            <li>Age: <?php echo $age_min . '&#150;' . $age_max; ?></li>
                            <li>Date: <?php echo $date_start . ' (' . $ongoing . ')'; ?> </li>
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
    </div> <!-- end #list-view -->


    <div id="programs-map" class="acf-map box">
    <?php
    $i = 0;
        foreach ( $locations as $location ) { 
            if ( $location != NULL ) {?>
            <div id="<?php echo $location_post_ids[$i]; ?>" class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">
                <p class="program-name"><?php echo $location_titles[$i]; ?></p>
                <p class="address"><?php echo $location['address']; ?></p>
            </div>
            <?php 
            }
            $i++;
         }
    ?>
    </div>
</div> <!-- End #container -->

<div id="content-pane">
    <div id="outputDiv"></div>
</div>



<?php get_footer(); ?>
