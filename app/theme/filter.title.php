<?php

function assignPageTitle( $title ){
	global $post;

	return $post->post_title;
}
add_filter('wp_title', 'assignPageTitle');