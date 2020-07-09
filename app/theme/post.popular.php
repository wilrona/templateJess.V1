<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 09/10/2017
 * Time: 15:35
 */

function SetPostViews($postID) {
	$meta_key = 'post_views_count'; //La clef, ou slug, de la méta-donnée
	$count = get_post_meta($postID, $meta_key, true); //Extraction de la valeur, qui est finalement un compteur
	if($count==''): //Si le compte est nul, la méta-donné n'existe pas, on va donc la créer
		$count = 0; //Initialisation à 0
		delete_post_meta($postID, $meta_key); //Simple précaution : si la méta-donnée existait déjà pour un autre usage exotique
		add_post_meta($postID, $meta_key, '1'); //On ajoute la méta-donné
	else:
		$count++; // Si la méta-donnée existe, on l'incrémente
		update_post_meta($postID, $meta_key, $count); //Et on met à jour
	endif;
}

function displayMontant($val) {
	if( $val < 1000 ) return $val;
	$val = $val/1000;
	if( $val < 1000 ) return "${val} K";
	$val = $val/1000;
	return "${val} M";
}

remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);