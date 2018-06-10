<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 06/03/2018
 * Time: 00:29
 */


$box = tr_meta_box('formcontact')->setLabel('Information page de contact');
$box->addScreen('page'); // updated
$box->setCallback(function(){
	$form = tr_form();

	echo $form->text('nomcompany')->setLabel('Nom Entreprise');
	echo $form->text('adressecompany')->setLabel('Adresse Entreprise');
	echo $form->text('phonecompany')->setLabel('Telephone Entreprise');
	echo $form->text('emailcompany')->setLabel('Email Entreprise');

//	echo $form->wpEditor('adresse')->setLabel('Adresse du siege social (Zone de gauche)');
	echo $form->wpEditor('content')->setLabel('Contenu de la page contact (shortcode page contact)');

});

$boxAgence = tr_meta_box('formcontactagcence')->setLabel('Liste des agences');
$boxAgence->addScreen('page'); // updated
$boxAgence->setCallback(function(){
	$form = tr_form();

	$repeater = $form->repeater('agences')->setFields([
		$form->text('texteagences')->setLabel('Nom de l\'agence'),
		$form->image('imgagences')->setLabel('Image de l\'agence'),
		$form->textarea('adresseagences')->setLabel('Adresse de l\'agence'),
	])->setLabel('Les Agences de l\'entreprise');

	echo $repeater;

});

add_action('admin_head', function () use ($box, $boxAgence){
	if(get_page_template_slug(get_the_ID()) !== 'pageContact.php'){

		remove_meta_box( $box->getId(), 'page', 'normal');
		remove_meta_box( $boxAgence->getId(), 'page', 'normal');
	}
});
