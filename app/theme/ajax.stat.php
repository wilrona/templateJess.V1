<?php

add_action('wp_ajax_load_statcv_by_ajax', 'load_statcv_by_ajax_callback');
add_action('wp_ajax_nopriv_load_statcv_by_ajax', 'load_statcv_by_ajax_callback');



function load_statcv_by_ajax_callback() {
	check_ajax_referer('load_more_cv', 'security');


    $date_start = date("Y-m-d", strtotime($_POST['start']));
    $date_end = date("Y-m-d", strtotime($_POST['end']));

	//// debut des données des commentaires d'un article
    ///
//    $arg = array(
//        'post_type' => 'curriculum',
//    );


    $arg_user_m = array(
        'role' => 'Subscriber',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'user_sexe',
                'value'   => 'm',
                'compare' => '='
            ),

        )
    );

    $arg_user_f = array(
        'role' => 'Subscriber',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'user_sexe',
                'value'   => 'f',
                'compare' => '='
            ),

        )
    );


    $user_query_f = new WP_User_Query($arg_user_f);
    $user_f = array();
    foreach ($user_query_f->get_results() as $user){
        array_push($user_f, $user->ID);
    }


    $user_query_m = new WP_User_Query($arg_user_m);
    $user_m = array();
    foreach ($user_query_m->get_results() as $user){
        array_push($user_m, $user->ID);
    }

    if(!sizeof($user_m)){
        array_push($user_m, '0');
    }

    if(!sizeof($user_f)){
        array_push($user_f, '0');
    }


    $arg_m = array(
        'post_type' => 'curriculum',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'user_cv',
                'value'   => $user_m,
                'compare' => 'IN'
            ),

        ),
        'post_status'   => 'publish',
        'date_query'    => array(
            'column'  => 'post_date',
            'after'   => $date_start,
            'before' => $date_end
        )
    );

    $arg_f = array(
        'post_type' => 'curriculum',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'user_cv',
                'value'   => $user_f,
                'compare' => 'IN'
            ),

        ),
        'post_status'   => 'publish',
        'date_query'    => array(
            'column'  => 'post_date',
            'after'   => $date_start,
            'before' => $date_end
        )
    );



    $arg = array(
        'post_type' => 'curriculum',
        'post_status'   => 'publish',
        'date_query'    => array(
            'column'  => 'post_date',
            'after'   => $date_start,
            'before' => $date_end
        )

    );


// Create a new filtering function that will add our where clause to the query
//    $filter_where = function ( $where = '') use ($start, $end ) {
//
//        $where .= " AND post_date >= $start AND post_date <= $end";
//        return $where;
//    };

//    add_filter( 'posts_where', $filter_where );
    $global_cv = new WP_Query( $arg );
    $masculin_cv = new WP_Query($arg_m);
    $feminin_cv = new WP_Query($arg_f);
//    remove_filter( 'posts_where', $filter_where );


    ?>
    <table class="uk-table uk-margin-remove uk-table-divider">
        <thead>
        <tr>
            <th>Libelle</th>
            <th class="uk-width-small">Nombre</th>
        </tr>
        </thead>
        <body>
        <tr>
            <td>CV Total</td>
            <td><?= $global_cv->post_count ?></td>
        </tr>
        <tr>
            <td>CV Masculin</td>
            <td><?= $masculin_cv->post_count ?></td>
        </tr>
        <tr>
            <td>CV Féminin</td>
            <td><?= $feminin_cv->post_count ?></td>
        </tr>

        </body>
    </table>
    <?php

    wp_die();
}


add_action('wp_ajax_load_statplacement_by_ajax', 'load_statplacement_by_ajax_callback');
add_action('wp_ajax_nopriv_load_statplacement_by_ajax', 'load_statplacement_by_ajax_callback');



function load_statplacement_by_ajax_callback() {
    check_ajax_referer('load_more_placement', 'security');


    $date_start = date("Y-m-d", strtotime($_POST['start']));
    $date_end = date("Y-m-d", strtotime($_POST['end']));

    //// debut des données des commentaires d'un article
    ///
//    $arg = array(
//        'post_type' => 'curriculum',
//    );


    $arg_user_m = array(
        'role' => 'Subscriber',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'user_sexe',
                'value'   => 'm',
                'compare' => '='
            ),

        )
    );

    $arg_user_f = array(
        'role' => 'Subscriber',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'user_sexe',
                'value'   => 'f',
                'compare' => '='
            ),

        )
    );


    $user_query_f = new WP_User_Query($arg_user_f);
    $user_f = array();
    foreach ($user_query_f->get_results() as $user){
        array_push($user_f, $user->ID);
    }




    $user_query_m = new WP_User_Query($arg_user_m);
    $user_m = array();
    foreach ($user_query_m->get_results() as $user){
        array_push($user_m, $user->ID);
    }

    if(!sizeof($user_m)){
        array_push($user_m, '0');
    }

    if(!sizeof($user_f)){
        array_push($user_f, '0');
    }

    $arg_m = array(
        'post_type' => 'placement',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'employee',
                'value'   => $user_m,
                'compare' => 'IN'
            ),

        ),
        'post_status'   => 'publish',
        'date_query'    => array(
            'column'  => 'post_date',
            'after'   => $date_start,
            'before' => $date_end
        )
    );

    $arg_f = array(
        'post_type' => 'placement',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'employee',
                'value'   => $user_f,
                'compare' => 'IN'
            ),

        ),
        'post_status'   => 'publish',
        'date_query'    => array(
            'column'  => 'post_date',
            'after'   => $date_start,
            'before' => $date_end
        )
    );

    $arg = array(
        'post_type' => 'placement',
        'post_status'   => 'publish',
        'date_query'    => array(
            'column'  => 'post_date',
            'after'   => $date_start,
            'before' => $date_end
        )

    );


    $global_cv = new WP_Query( $arg );
    $masculin_cv = new WP_Query($arg_m);
    $feminin_cv = new WP_Query($arg_f);


    ?>
    <table class="uk-table uk-margin-remove uk-table-divider">
        <thead>
        <tr>
            <th>Libelle</th>
            <th class="uk-width-small">Nombre</th>
        </tr>
        </thead>
        <body>
        <tr>
            <td>Placement Total</td>
            <td><?= $global_cv->post_count ?></td>
        </tr>
        <tr>
            <td>Placement Masculin</td>
            <td><?= $masculin_cv->post_count ?></td>
        </tr>
        <tr>
            <td>Placement Féminin</td>
            <td><?= $feminin_cv->post_count ?></td>
        </tr>

        </body>
    </table>
    <?php

    wp_die();
}