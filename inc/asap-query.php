<?php
    $interests = get_query_var( 'ai' ); // associated_interests
    $daysofweek = get_query_var( 'dow' ); // day of week
    //$start_date = get_query_var( 'sd' ); // start date 
    //$end_date = get_query_var( 'ed' ); // end date 
    $prog_orgs = get_query_var( 'org' ); // end date
    $age = get_query_var( 'age' ); 
    $user_address = ( get_query_var( 'addy' ) != 0 ? get_query_var( 'addy' ) : "Milwaukee, WI" );
    $sr = get_query_var( 'sr' ); // sort results
    switch ($sr) {
        case "title_za" :
            $order = 'DESC';
            $order_by = 'title';
            break;
        case "title_az" :
            $order = 'ASC';
            $order_by = 'title';
            break;
        case "date" :
            $order = 'ASC';
            $order_by = 'prog_date_start';
            break;
        default :
            $order = 'ASC';
            $order_by = 'prog_date_start';
    }
?>

<?php 
    // if (empty($start_date)) {
    //     $start_date = date('Ymd');
    //     echo "run this code";
    // } else {
    //     echo "run this other code";
    // }
    // if (empty($end_date)) {
    //     $end_date = date('Ymd');
    // }

?>

<?php 
    /**
     * The WordPress Query class.
     * @link http://codex.wordpress.org/Function_Reference/WP_Query
     *
     */



    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'cpt_program',
        // 'meta_query' => array(
        //     'relation' => 'OR',
        //     'ongoing' => array(
        //         'key'     => 'prog_ongoing',
        //         'value'   => 1
        //     ),
        //     'start_date' => array(
        //         'key' => 'prog_date_start',
        //         'value'   => date('Ymd'),
        //         'type'    => 'numeric',
        //         'compare' => '>='
        //     )
        // ),
        'meta_query' => array(
            array(
            'relation' => 'OR',
                array (
                    'key' => 'prog_ongoing',
                    'value' => true,
                    'compare' => '=',
                ),
                   
                array (
                    'key' => 'prog_date_start',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                ),
            ),
        ),

        'orderby' => 'prog_ongoing',
        // 'orderby' => array(
        //     'start_date' => 'ASC',
        //     'ongoing' => 'DESC',
        // ),
        'order'   => 'DESC',
    );

    // if ( !empty( $start_date ) ) {
    //     array_push( $args['meta_query'], array (
    //         array (
    //             'relation' => 'OR',
    //             array (
    //                 'key' => 'prog_ongoing',
    //                 'value' => true,
    //                 'compare' => '=',
    //             ),
    //             array (
    //                 'relation' => 'AND',
    //                 array (
    //                     'key' => 'prog_date_start',
    //                     'value' => $start_date,
    //                     'compare' => '>=',
    //                 ),
    //                 array (
    //                     'key' => 'prog_date_end',
    //                     'value' => $end_date,
    //                     'compare' => '<=',
    //                 ),
    //             )
    //         )
    //     ));
    // }

    if (!empty( $age )) {
        array_push($args['meta_query'],  array (
                'key' => 'prog_age_min',
                'value' => $age,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ),
            array (
                'key' => 'prog_age_max',
                'value' => $age,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
    }

    if (!empty( $interests )) {
        $i = 0;
        $ints['relation'] = 'OR';
        foreach ($interests as $interest) {
            $ints[$i] = array(
                'key' => 'associated_interests',
                'value' => '"' . $interest . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $ints);
    }

    if (!empty( $daysofweek )) {
        $i = 0;
        $days['relation'] = 'OR';
        foreach ( $daysofweek as $day ) {
            $days[$i] = array(
                'key' => 'prog_days_offered',
                'value' => '"' . $day . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $days );
    }

    if (!empty( $activity_level )) {
        $i = 0;
        $levels['relation'] = 'OR';
        foreach ( $activity_level as $level ) {
            $levels[$i] = array(
                'key' => 'prog_activity_level',
                'value' => '"' . $level . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $levels );
    }

    if (!empty( $prog_orgs )) {
        $i = 0;
        $orgs['relation'] = 'OR';
        foreach ( $prog_orgs as $org ) {
            $orgs[$i] = array(
                'key' => 'prog_organization',
                'value' => '"' . $org . '"',
                'compare' => 'LIKE'
            );
            $i++;
        }
        array_push( $args['meta_query'], $orgs );
    }
?>