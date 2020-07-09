<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 03/10/2017
 * Time: 12:02
 */

add_theme_support( 'post-thumbnails' );

/* suppression de la barre d'administration sur le template */
add_filter('show_admin_bar','__return_false');


// action a faire pour activer une page d'option de theme
add_filter('tr_theme_options_page', function() {
	return get_template_directory() . '/app/theme/theme.options.php';
});

add_filter('tr_theme_options_name', function() {
	return 'pc_options';
});

// modifier la taille des excepts dans wordpress
add_filter( 'excerpt_length', 'your_prefix_excerpt_length' );
function your_prefix_excerpt_length() {
    return 30;
}

add_filter( 'lostpassword_url', 'my_lost_password_page', 10, 2 );
function my_lost_password_page( $lostpassword_url, $redirect ) {
	return home_url( '/candidat/reset' );
}


// active l'utilisation d'une page pour les sous categorie


//function wpd_subcategory_template( $template ) {
//	$cat = get_queried_object();
//	if( 0 < $cat->category_parent )
//		$template = locate_template( 'subcategory.php' );
//	return $template;
//}
//add_filter( 'category_template', 'wpd_subcategory_template' );

// Délimitation du resultat du moteur de recherche

add_action( 'pre_get_posts', function( $query ) {

	// Check that it is the query we want to change: front-end search query
	if( $query->is_main_query() && ! is_admin() && $query->is_search() ) {

		// Change the query parameters
		$query->set( 'posts_per_page', 10 );
		$query->set( 'post_type', 'post' );
		$query->set( 'paged', 1 );
		$query->set( 'post_status', 'publish' );

	}

} );


function sky_date_french($format, $timestamp = null, $echo = null) {
	$param_D = array('', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim');
	$param_l = array('', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
	$param_F = array('', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	$param_M = array('', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc');
	$return = '';
	if(is_null($timestamp)) { $timestamp = mktime(); }
	for($i = 0, $len = strlen($format); $i < $len; $i++) {
		switch($format[$i]) {
			case '\\' : // fix.slashes
				$i++;
				$return .= isset($format[$i]) ? $format[$i] : '';
				break;
			case 'D' :
				$return .= $param_D[date('N', $timestamp)];
				break;
			case 'l' :
				$return .= $param_l[date('N', $timestamp)];
				break;
			case 'F' :
				$return .= $param_F[date('n', $timestamp)];
				break;
			case 'M' :
				$return .= $param_M[date('n', $timestamp)];
				break;
			default :
				$return .= date($format[$i], $timestamp);
				break;
		}
	}
	if(is_null($echo)) { return $return;} else { echo $return;}
}


function wpse66093_no_admin_access()
{
	$redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
	if (current_user_can( 'subscriber' ))
		exit( wp_redirect( $redirect ) );
}
add_action( 'admin_init', 'wpse66093_no_admin_access', 100 );

add_filter ("wp_mail_content_type", "my_awesome_mail_content_type");
function my_awesome_mail_content_type() {
	return "text/html";
}

add_filter ("wp_mail_from", "my_awesome_mail_from");
function my_awesome_mail_from() {
	return "no_reply@jessassistance.com";
}

add_filter ("wp_mail_from_name", "my_awesome_mail_from_name");
function my_awesome_email_from_name() {
	return "Jess Assistance";
}


add_action( 'admin_menu', 'remove_menus' );
function remove_menus(){

//	remove_menu_page( 'index.php' );                  //Dashboard
//	remove_menu_page( 'jetpack' );                    //Jetpack*
//	remove_menu_page( 'edit.php' );                   //Posts
	remove_menu_page( 'upload.php' );                 //Media
//	remove_menu_page( 'edit.php?post_type=page' );    //Pages
	remove_menu_page( 'edit-comments.php' );          //Comments
//	remove_menu_page( 'themes.php' );                 //Appearance
//	remove_menu_page( 'plugins.php' );                //Plugins
//	remove_menu_page( 'users.php' );                  //Users
//	remove_menu_page( 'tools.php' );                  //Tools
//	remove_menu_page( 'options-general.php' );        //Settings

}