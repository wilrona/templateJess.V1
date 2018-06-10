<?php

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

function remove_dashboard_widgets()
{
    global $wp_meta_boxes;
    // Tableau de bord général
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // Presse-Minute
//    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Commentaires récents
//    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
//    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // Extensions
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // Liens entrant
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // Billets en brouillon
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // Blogs WordPress
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // Autres actualités WordPress
//    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']); // Active sur le site


}

$boxPlacement = tr_meta_box('Statistiques des Placements');
$boxPlacement->addScreen('dashboard');
$boxPlacement->setCallback(function() {


    $date_show_start = date('01/m/Y');
    $date_show_end = date('d/m/Y');

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


    $date_start = date('Y-m-01');
    $date_end = date('Y-m-d');


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

    <div class="uk-grid-small" uk-grid>
        <div class="uk-width-2-5">
            <input type="text" class="uk-input datepicker_start_2 datepicker_start_2_placement uk-form-small" placeholder="Date début" value="<?= $date_show_start ?>">
        </div>
        <div class="uk-width-2-5">
            <input type="text" class="uk-input datepicker_end_2 datepicker_end_2_placement uk-form-small" placeholder="Date de fin" value="<?= $date_show_end ?>">
        </div>
        <div class="uk-width-1-5">
            <button class="uk-button uk-button-primary uk-button-small uk-width-1-1 loadmorePlacement"><span uk-icon="search"></span>
            </button>
        </div>
    </div>
    <div class="statplacement">
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
    </div>

    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
        jQuery(function($) {
            $('body').on('click', '.loadmorePlacement', function(e) {
                e.preventDefault();
                var data = {
                    'action': 'load_statplacement_by_ajax',
                    'security': '<?php echo wp_create_nonce("load_more_placement"); ?>',
                    'start' : $('.datepicker_start_2_placement').val(),
                    'end' : $('.datepicker_end_2_placement').val()
                };
                $.post(ajaxurl, data, function(response) {
                    $('.statplacement').html(response);
                });
            });
        });
    </script>

    <?php

});

$boxCV = tr_meta_box('Statistiques des CV');
$boxCV->addScreen('dashboard');
$boxCV->setCallback(function() {


    $date_show_start = date('01/m/Y');
    $date_show_end = date('d/m/Y');

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


    $date_start = date('Y-m-01');
    $date_end = date('Y-m-d');

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
//    $filter_where = function ( $where = '') use ($date_start, $date_end ) {
//
//        $where .= " AND post_date between $date_start AND $date_end";
//        return $where;
//    };

//    add_filter( 'posts_where', $filter_where );
    $global_cv = new WP_Query( $arg );
    $masculin_cv = new WP_Query($arg_m);
    $feminin_cv = new WP_Query($arg_f);
//    remove_filter( 'posts_where', $filter_where );


    ?>

    <div class="uk-grid-small" uk-grid>
        <div class="uk-width-2-5">
            <input type="text" class="uk-input datepicker_start_2 uk-form-small" placeholder="Date début" value="<?= $date_show_start ?>">
        </div>
        <div class="uk-width-2-5">
            <input type="text" class="uk-input datepicker_end_2 uk-form-small" placeholder="Date de fin" value="<?= $date_show_end ?>">
        </div>
        <div class="uk-width-1-5">
            <button class="uk-button uk-button-primary uk-button-small uk-width-1-1 loadmore"><span uk-icon="search"></span>
            </button>
        </div>
    </div>
    <div class="statcv">
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
    </div>

    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
        jQuery(function($) {
            $('body').on('click', '.loadmore', function(e) {
                e.preventDefault();
                var data = {
                    'action': 'load_statcv_by_ajax',
                    'security': '<?php echo wp_create_nonce("load_more_cv"); ?>',
                    'start' : $('.datepicker_start_2').val(),
                    'end' : $('.datepicker_end_2').val()
                };
                $.post(ajaxurl, data, function(response) {
                   $('.statcv').html(response);
                });
            });
        });
    </script>

    <?php
});


