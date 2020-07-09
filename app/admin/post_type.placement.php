<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 07/04/2018
 * Time: 17:38
 */

$mag = tr_post_type('placement', 'Placements');

$mag->setIcon('paperclip');

add_action( 'init', function() {
//	remove_post_type_support( 'placement', 'title' );
	remove_post_type_support( 'placement', 'editor' );
}, 99);


$box_karact = tr_meta_box('userinformation')->setLabel('Information du placement');
$box_karact->addPostType( $mag->getId() );
$box_karact->setCallback(function (){
	$form = tr_form();



	$blogusers = get_users( 'orderby=nicename&role=subscriber' );

	$options = array('Selection d\'un employe' => null);;

	foreach ($blogusers as $user){
		$matricule = 'Non defini';
		if(tr_users_field('matricule', $user->ID)){
			$matricule = tr_users_field('matricule', $user->ID);
		}
		$options[$user->display_name.' ('. $matricule.')'] = $user->ID;
	}

	echo $form->select('employee')->setLabel('Nom de l\'employe')->setOptions($options)->setAttribute('class', 'select');


	$terms = get_terms( array(
		'taxonomy' => 'categorie_demploi',
		'hide_empty' => false,
		'parent' => 0
	));

	$options = array('Selection categorie d\'emploi' => null);

	foreach ($terms as $term){

		$options[$term->name] = array();
		$childs = get_terms( array(
			'taxonomy' => 'categorie_demploi',
			'hide_empty' => false,
			'parent' => $term->term_id
		));

		foreach ($childs as $child){

			$options[$term->name][$child->name] = $child->term_id;

		}
	}


	echo $form->select('categorie_emploi_employe')->setLabel('Categorie d\'emploi')->setOptions($options)->setAttribute('class', 'select');

	echo $form->text('fonctionemploye')->setLabel('Fonction de l\'employe');
//	echo $form->text('echelonemploye')->setLabel('Echellon de l\'employe');

    if(tr_posts_field('dateembaucheemploye')):
        echo $form->text('dateembaucheemploye')->setLabel('Date d\'embauche de l\'employe ')->setAttribute('disabled', 'disabled');
    else:
	    echo $form->text('dateembaucheemploye')->setLabel('Date d\'embauche de l\'employe ')->setAttribute('class', 'datepicker');
    endif;

	$options = [
		'Contrat en cours' => 'encours',
		'Contrat cloturee' => 'cloturee'
	];

	echo $form->select('contratcvalue')->setLabel('Etat du contrat')->setOptions($options);

});

$box_ancien = tr_meta_box('useranciennete')->setLabel('Information sur l\'anciennete');
$box_ancien->addPostType( $mag->getId() );
$box_ancien->setCallback(function (){
	$form = tr_form();

	if(tr_posts_field('ancienneteemploye')):
            echo $form->text('ancienneteemploye')->setLabel('Anciennete au poste à la prise de fonction (En année)')->setAttribute('disabled', 'disabled');
	else:
        echo $form->text('ancienneteemploye')->setLabel('Définir l\'anciennete au poste à la prise de fonction (En année)')->setType("number")->setDefault(0);
	endif;

	$dateemploi = tr_posts_field('dateembaucheemploye');

    $currentYear = 0;

    $taux = 0;

	if($dateemploi):
        $dateemploi = str_replace('/', '-', $dateemploi);
	    $dateemploi = date('Y-m-d', strtotime($dateemploi));
        $dateemploi = new DateTime($dateemploi);


        $datenow = new DateTime();
        $currentMounth = $datenow->diff($dateemploi)->m + ($datenow->diff($dateemploi)->y * 12);
        $currentYear = $currentMounth / 12;

        $currentYear = $currentYear + intval(tr_posts_field('ancienneteemploye'));

        if($currentYear >= 2):
            $taux = 4;
        endif;

        if($currentYear > 2):

            $taux = $taux + 2;

        endif;

	endif;

	echo $form->text('tauxancienneteemploye')->setLabel('Taux d\'anciennete (en %)')->setType('number')->setAttribute('disabled', 'disabled')->setAttribute('value', $taux);

    echo $form->text('anciennetecours')->setLabel('Anciennete en cours')->setAttribute('disabled', 'disabled')->setAttribute('value', round($currentYear, 0));

});


$box_echellon = tr_meta_box('userechellon')->setLabel('Information sur l\'echellon');
$box_echellon->addPostType( $mag->getId() );
$box_echellon->setCallback(function (){
    $form = tr_form();
    echo $form->text('datepassageemploye')->setLabel('Date du dernier passage')->setAttribute('class', 'datepicker');
    echo $form->text('datenextpassageemploye')->setLabel('Date du prochain passage')->setAttribute('disabled', 'disabled');

});

function add_table_columns($columns){
	$news_columns = array(
		'nom_employe' => 'Nom de l\'employe',
		'etat_contrat' => ' Etat du contrat',
		'date_next_passage' => 'Date du prochain passage',
		'title' => 'Nom de l\'entreprise'
	);

	unset($columns['date']);

	$filtered_columns = array_merge($columns, $news_columns);

	return $filtered_columns;
}
add_filter('manage_placement_posts_columns', 'add_table_columns');


function show_table_columns_content($columns){
	global $post;

	switch ($columns){
		case 'nom_employe':
			$nom = get_user_by('id', tr_posts_field('employee', $post->ID))->display_name;
			echo $nom;
			break;

		case 'etat_contrat':

			if(tr_posts_field('contratcvalue', $post->ID) == 'encours'){
				echo 'Contrat en cours';
			}

			if(tr_posts_field('contratcvalue', $post->ID) == 'cloturee'){
				echo 'Contrat cloture';
			}
			break;

		case 'date_next_passage':
			echo tr_posts_field('datenextpassageemploye', $post->ID);
			break;
	}
}
add_filter('manage_placement_posts_custom_column', 'show_table_columns_content');

function sortable_table_columns($columns){

	$columns['nom_employe'] = 'nom_employe';
	$columns['etat_contrat'] = 'etat_contrat';
	$columns['date_next_passage'] = 'date_next_passage';

	return $columns;

}
add_filter('manage_edit-placement_sortable_columns', 'sortable_table_columns');


function extend_admin_search_placement( $query ) {

    // Extend search for document post type
    $post_type = 'placement';
    // Custom fields to search for
    $custom_fields = array(
        "employee",
    );

    if( ! is_admin() )
        return;

    if ( $query->query['post_type'] != $post_type )
        return;

    $search_term = $query->query_vars['s'];

    // Set to empty, otherwise it won't find anything
//    $query->query_vars['s'] = '';

    if ( $search_term != '' ) {
        $meta_query = array( 'relation' => 'OR' );

        foreach( $custom_fields as $custom_field ) {

            if($custom_field == 'employee'){


                $search_string = esc_attr( trim( $search_term ) );
                $users = new WP_User_Query( array(
                    'search'         => "*{$search_string}*",
                    'search_columns' => array(
                        'user_login',
                        'user_nicename',
                        'user_email',
                        'user_url',
                    ),
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'first_name',
                            'value'   => $search_string,
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key'     => 'last_name',
                            'value'   => $search_string,
                            'compare' => 'LIKE'
                        )
                    )
                ) );

                $users_found = $users->get_results();

                $id_user = [];
                foreach ($users_found as $user):
                    array_push( $id_user, $user->ID);
                endforeach;

                array_push( $meta_query, array(
                    'key' => $custom_field,
                    'value' => $id_user,
                    'compare' => 'IN'
                ));

            }else{


                array_push( $meta_query, array(
                    'key' => $custom_field,
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ));

            }

        }

        $query->set( 'meta_query', $meta_query );
    };
}

add_action( 'pre_get_posts', 'extend_admin_search_placement' );

function tutsplus_save_expiry_date_placement( $post_id ) {

    $posted = get_post($post_id);

    // Check if the current user has permission to edit the post. */
    if ( !current_user_can( 'edit_post', $posted->ID ) ):
        return;
    endif;

    if ($posted->post_type === 'placement') {

        if(tr_posts_field('dateembaucheemploye', $posted->ID) && empty(tr_posts_field('datepassageemploye', $posted->ID))):

            update_post_meta( $post_id, 'datepassageemploye', tr_posts_field('dateembaucheemploye', $posted->ID) );
        endif;

        if(tr_posts_field('datepassageemploye', $posted->ID)):

            $next = tr_posts_field('datepassageemploye', $posted->ID);

            $next = str_replace('/', '-', $next);
            $next = date('d/m/Y', strtotime('+3 year', strtotime($next)));

            update_post_meta($post_id, 'datenextpassageemploye', $next);
            update_post_meta($post_id, 'datenextpassage_convert', strtotime('+3 year', strtotime($next)));
        endif;
    }

}
add_action( 'save_post', 'tutsplus_save_expiry_date_placement' );



