<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 14/03/2018
 * Time: 16:39
 */

$box = tr_meta_box('BlockConnexion')->setLabel('titre secondaire');
$box->addScreen('page'); // updated
$box->setCallback(function(){
	$form = tr_form();
	echo $form->text('secondtitle')->setLabel('Titre secondaire');

});

$boxvaleur = tr_meta_box('InfosInscription')->setLabel('Message pour inscription');
$boxvaleur->addScreen('page'); // updated
$boxvaleur->setCallback(function(){
	$form = tr_form();
	echo $form->editor('inscription_message')->setLabel('Message d\'inscription');
	echo $form->search('lien_inscription')->setPostType('page')->setLabel('Lien de la page inscription');


});


add_action('admin_head', function () use ($box, $boxvaleur){
	if(get_page_template_slug(get_the_ID()) !== 'pageConnexion.php'){
		remove_meta_box( $box->getId(), 'page', 'normal');
		remove_meta_box( $boxvaleur->getId(), 'page', 'normal');
	}
});