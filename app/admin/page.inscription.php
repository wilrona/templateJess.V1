<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 15/03/2018
 * Time: 04:15
 */

$box = tr_meta_box('BlockInscription')->setLabel('titre secondaire');
$box->addScreen('page'); // updated
$box->setCallback(function(){
	$form = tr_form();
	echo $form->text('secondtitle')->setLabel('Titre secondaire');

});

add_action('admin_head', function () use ($box){
	if(get_page_template_slug(get_the_ID()) !== 'pageRegister.php'){
		remove_meta_box( $box->getId(), 'page', 'normal');
	}
});