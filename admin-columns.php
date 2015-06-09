<?php
/*
** Editing post list columns
*/

// Adding Admin Columns
add_filter('manage_cpt_program_posts_columns', 'bs_event_table_head');
function bs_event_table_head( $defaults ) {
    $defaults['prog_cost']  = 'Cost';
    $defaults['prog_organization']  = 'Organization';
    return $defaults;
}

add_action( 'manage_cpt_program_posts_custom_column', 'bs_event_table_content', 10, 2 );
function bs_event_table_content( $column_name, $post_id ) {
    if ($column_name == 'prog_cost') {
    $prog_cost = get_post_meta( $post_id, 'prog_cost', true );
      echo  $prog_cost;
    }
    if ($column_name == 'prog_organization') {
    $prog_organization = get_field( 'prog_organization', $post_id );
      echo  $prog_organization[0]->post_title;
    }

}

// Ordering Program Columns
add_filter( 'manage_edit-cpt_program_sortable_columns', 'ak_cat_sort' );
function ak_cat_sort( $columns ) {
  $columns['prog_cost'] = 'prog_cost';
  $columns['prog_organization'] = 'prog_organization';
  return $columns;
}

// for cost
add_filter( 'request', 'bs_ticket_status_column_orderby' );
function bs_ticket_status_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'prog_cost' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'prog_cost',
            'orderby' => 'meta_value_num'
        ) );
    }
    return $vars;
}

// for organizations
add_filter( 'request', 'asap_prog_organization_column_orderby' );
function asap_prog_organization_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'prog_organization' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'prog_organization',
            'orderby' => 'meta_value'
        ) );
    }
    return $vars;
}

//Filter Results

// function get_costs() {
//     $args_costs = array('post_type' => 'cpt_program');
//     $query = new WP_Query( $args_costs );
//     if ( $query->have_posts() ) {
//         $costs = array();
//         echo '<select id="filter-by-cost" name="prog_cost">';
//         echo '<option class="level-0" value="0">All costs</option>';
//         while ( $query->have_posts() ) {
//             $query->the_post();
//             $program_cost = get_field('prog_cost');
//             if ( get_field('prog_cost') ) {
//                 if ( !in_array($program_cost, $costs) ) {
//                     echo '<option class="level-0" value="' . $program_cost . '">' . $program_cost . '</option>';
//                 }
//                 $costs[] = $program_cost;
//             }
//         }
//     }
//     /* Restore original Post Data */
//     wp_reset_postdata();
// }

function get_orgs() {
    $args_orgs = array('post_type' => 'cpt_program');
    $query = new WP_Query( $args_orgs );
    if ( $query->have_posts() ) {
        $orgs = array();
        echo '<select id="filter-by-organization" name="prog_organization">';
        echo '<option class="level-0" value="0">All organizations</option>';
        while ( $query->have_posts() ) {
            $query->the_post();
            $program_organization = get_field('prog_organization');
            
            if ( get_field('prog_organization') ) {
                if ( !in_array($program_organization[0]->ID, $orgs) ) {
                    echo '<option class="level-0" value="' . $program_organization[0]->ID . '">' . $program_organization[0]->post_title . '</option>';
                }
                $orgs[] = $program_organization[0]->ID;
                //var_dump($orgs);
            }
        } // end while
        echo '</select>';
        return $orgs;
    }
    /* Restore original Post Data */
    wp_reset_postdata();
}

add_action( 'restrict_manage_posts', 'bs_event_table_filtering' );
function bs_event_table_filtering() {
  global $wpdb, $current_screen;
  if ( $current_screen->post_type == 'cpt_program' ) {
    //var_dump($current_screen);
    // $ticket_statuses = get_costs();
    // echo '';
    //   echo '' . __( 'Show all ticket statuses', 'textdomain' ) . '';
    // foreach( $ticket_statuses as $value => $name ) {
    //   $selected = ( !empty( $_GET['prog_cost'] ) AND $_GET['prog_cost'] == $value ) ? 'selected="selected"' : '';
    //   echo '' . $name . '';
    // }
    // echo '';

    //var_dump(get_orgs());
    $prog_orgs = get_orgs();
    
    echo '';
    //echo '' . __( 'All organizations', 'textdomain' ) . '';
    foreach( $prog_orgs as $value => $name ) {
        $selected = ( !empty( $_GET['prog_organization'] ) AND $_GET['prog_organization'] == $value ) ? 'selected ="selected"' : '';
        //echo '' . $name . '';
    }
    echo '';

}
}

add_filter( 'parse_query','bs_event_table_filter' );
function bs_event_table_filter( $query ) {
  if( is_admin() AND $query->query['post_type'] == 'cpt_program' ) {
    $qv = &$query->query_vars;
    $qv['meta_query'] = array();


    // if( !empty( $_GET['event_date'] ) ) {
    //   $start_time = strtotime( $_GET['event_date'] );
    //   $end_time = mktime( 0, 0, 0, date( 'n', $start_time ) + 1, date( 'j', $start_time ), date( 'Y', $start_time ) );
    //   $end_date = date( 'Y-m-d H:i:s', $end_time );
    //   $qv['meta_query'][] = array(
    //     'field' => '_bs_meta_event_date',
    //     'value' => array( $_GET['event_date'], $end_date ),
    //     'compare' => 'BETWEEN',
    //     'type' => 'DATETIME'
    //   );

    // }

    // if( !empty( $_GET['prog_cost'] ) ) {
    //   $qv['meta_query'][] = array(
    //     'field' => 'prog_cost',
    //     'value' => $_GET['prog_cost'],
    //     'compare' => '=',
    //     'type' => 'CHAR'
    //   );
    // }

    // if( !empty( $_GET['prog_organization'] ) ) {
    //   $qv['meta_query'][] = array(
    //     'field' => 'prog_organization',
    //     'value' => $_GET['prog_organization'],
    //     'compare' => '=',
    //     'type' => 'CHAR'
    //   );
    // }
    if( !empty( $_GET['prog_organization'] ) ) {
        $qv['meta_query'][] = array(
            'key' => 'prog_organization',
            'value' => $_GET['prog_organization'],
            'compare' => 'LIKE',
        );
    }


    // if( !empty( $_GET['orderby'] ) AND $_GET['orderby'] == 'event_date' ) {
    //   $qv['orderby'] = 'meta_value';
    //   $qv['meta_key'] = '_bs_meta_event_date';
    //   $qv['order'] = strtoupper( $_GET['order'] );
    // }

  }
}
?>
