<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 13/03/2018
 * Time: 12:41
 */

$box = tr_meta_box('Block_second')->setLabel('Titre secondaire');
$box->addScreen('page'); // updated
$box->setCallback(function(){
	$form = tr_form();
	echo $form->text('secondtitle')->setLabel('Titre secondaire');

});

add_action('admin_head', function () use ($box){
	if(get_page_template_slug(get_the_ID()) !== 'pageRecruteur.php'){
		remove_meta_box( $box->getId(), 'page', 'normal');
	}
});