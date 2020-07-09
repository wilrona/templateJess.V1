<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 02/04/2018
 * Time: 01:30
 */


$settings = ['capability' => 'administrator', 'position' => '25'];

$seat_index = tr_page('Candidature', 'index', 'Candidatures', $settings);

$seat_index->setIcon('book')->useController();

$seat_show = tr_page('Candidature', 'show', 'Candidatures de l\'offre')->removeMenu()->removeTitle();
$seat_show->useController();

$seat_index->addPage($seat_show);

$seat_recom = tr_page('Candidature', 'edit', 'Candidatures recommandees de l\'offre')->removeMenu()->removeTitle();
$seat_recom->useController();

$seat_index->addPage($seat_recom);



function count_recommandee($id, $count = true){

	$terms_cat = array();
	$terms_comp = array();
	$terms_lang = array();

	array_push($terms_cat, intval(tr_posts_field('categorie_emploi', $id)));

	foreach (tr_posts_field('competence', $id) as $comp){
		array_push($terms_comp, intval($comp));
	}

	foreach (tr_posts_field('langue_offre', $id) as $lang){
		array_push($terms_lang, intval($lang));
	}

	$terms_comp =  array_unique($terms_comp);
	$terms_lang =  array_unique($terms_lang);
	$terms_cat =  array_unique($terms_cat);


	if(!sizeof($terms_cat)){
		array_push($terms_cat, '0');
	}

	$custom_args_q1 = array(
		'post_type' => 'curriculum',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => 'cate_emploi_cv',
				'value'   => $terms_cat,
				'compare' => 'IN',
			),
		)
	);

	if(sizeof($terms_comp)){
		$compet = array(
			'relation' => 'OR'
		);
		foreach ($terms_comp as $comp){
			$value = array(
				'key'     => 'competencecv',
				'value'   =>  $comp,
				'compare' => 'LIKE',
			);

			array_push($compet, $value);
		}

		array_push($custom_args_q1['meta_query'], $compet);

	}

	if(sizeof($terms_lang)){
		$compet = array(
			'relation' => 'OR'
		);
		foreach ($terms_lang as $lang){
			$value = array(
				'key'     => 'langue_cv',
				'value'   =>  $lang,
				'compare' => 'LIKE',
			);

			array_push($compet, $value);
		}

		array_push($custom_args_q1['meta_query'], $compet);

	}

	$q1 = new WP_Query( $custom_args_q1 );


	$terms_unique = array();
	while ($q1->have_posts()): $q1->the_post();
		array_push($terms_unique, get_the_ID());
	endwhile;

	if(!sizeof($terms_unique)){
		array_push($terms_unique, 0);
	}

	$custom_args = array(
		'post_type' => 'curriculum',
		'post__in' => $terms_unique
	);

	$data = new WP_Query( $custom_args );

	if($count){
		return $data->post_count;
	}else{
		return $data;
	}


}

