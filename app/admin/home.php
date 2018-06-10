<?php

//$home = (int) get_option('page_on_front');

$boxPages = tr_meta_box('Message Slogan');
$boxPages->addScreen('page'); // updated
$boxPages->setCallback(function(){
	$form = tr_form();

	echo $form->text('slogan')->setLabel('Texte du slogan');
});

$boxUne = tr_meta_box('Element du slider de la page d\'accueil');
$boxUne->addScreen('page'); // updated
$boxUne->setCallback(function(){
	$form = tr_form();

	$repeater = $form->repeater('homeslider')->setFields([
		$form->image('imageslider')->setLabel('Image du slider'),
        $form->editor('texteslider')->setLabel('Texte accompagnant l\'image'),
        $form->select('positiontexteslider')->setLabel('Position du texte accompagnant l\'image')->setOptions([
        	'Position en bas' => 'bottom',
        	'Position a gauche' => 'left',
        	'Position a droite' => 'right',
        ]),
	])->setLabel('Home Slider');

	echo $repeater;

});

$boxService = tr_meta_box('Ajouter les services');
$boxService->addScreen('page'); // updated
$boxService->setCallback(function(){
	$form = tr_form();
	echo $form->search('pageservice')->setLabel('Ajoute de la page de service')->setPostType('page');
	$repeater = $form->repeater('homeservices')->setFields([
		$form->search('serviceitem')->setLabel('Ajouter un service')->setPostType('service'),
		$form->text('servicetagscroll')->setLabel('Id du menu')->setHelp('Cette ID va permettre le scroll de la page sur la zone du service')
	])->setLabel('Selection des services');

	echo $repeater;

});

$boxAction = tr_meta_box('Traitement du block pour candidat');
$boxAction->addScreen('page'); // updated
$boxAction->setCallback(function(){
	$form = tr_form();
	echo $form->text('hometextblock')->setLabel('Texte du block');
	echo $form->search('linkpostuler')->setLabel('Lien de la page postuler à une offre')->setPostType('page');
	echo $form->search('linkoffre')->setLabel('Lien de la page des offres d\'emploi')->setPostType('page');
	echo $form->image('homeimageblock')->setLabel('Image de fond du block');
	echo $form->text('newsletterimageblock')->setLabel('Texte au dessus de la newsletter');
});




add_action('admin_head', function () use ($boxPages, $boxUne, $boxService, $boxAction) {
    if(get_page_template_slug(get_the_ID()) === 'home.php'):
        remove_post_type_support('page', 'editor');
	else:
		remove_meta_box( $boxPages->getId(), 'page', 'normal');
		remove_meta_box( $boxUne->getId(), 'page', 'normal');
		remove_meta_box( $boxService->getId(), 'page', 'normal');
		remove_meta_box( $boxAction->getId(), 'page', 'normal');
	endif;
});











