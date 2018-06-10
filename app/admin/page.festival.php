<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 12/12/2017
 * Time: 10:57
 */


$boxHeader = tr_meta_box('header')->setLabel('Personnalisation de l\'entête de page');
$boxHeader->addScreen('page'); // updated
$boxHeader->setCallback(function(){
	$form = tr_form();

	echo $form->color('fondcolorheader')->setLabel('Choix de la couleur du fond')->setHelp('Elle s\'applique seulement quand l\'image de fond n\'a pas été défini');
	echo $form->image('fondimageheader')->setLabel('Choix de l\'image du fond');
	echo $form->color('colorTexte')->setLabel('Choix de la couleur du text')->setHelp('Couleur de texte du menu');
	echo $form->color('colorBorderTexte')->setLabel('Choix de la couleur de la bordure du text');



});

$boxFooter = tr_meta_box('footer')->setLabel('Personnalisation du pied de page');
$boxFooter->addScreen('page'); // updated
$boxFooter->setCallback(function(){
	$form = tr_form();

	echo $form->color('fondcolorfooter')->setLabel('Choix de la couleur du fond')->setHelp('Elle s\'applique seulement quand l\'image de fond n\'a pas été défini');
	echo $form->image('fondimagefooter')->setLabel('Choix de l\'image du fond');



});


$boxRubrique = tr_meta_box('rubriques')->setLabel('Rubrique du festival / Menu');
$boxRubrique->addScreen('page'); // updated
$boxRubrique->setCallback(function(){
	$form = tr_form();

	$repeater = $form->repeater('rubrique')->setFields([
		$form->text('titreMenu')->setLabel('Nom du menu'),
		$form->text('lienRubrique')->setLabel('lien de la rubrique')->setHelp('Le lien doit est sous la forme : <b>'.get_the_permalink(). '#id_du_block</b> pour activer le scroll')->setAttribute('placeholder', '#id_du_block / un lien vers une page')

	])->setLabel('Ajouter des rubriques à votre page')->setHelp('');
	echo $repeater;



});

add_action('admin_head', function () use ($boxHeader, $boxFooter, $boxRubrique){
	if(get_page_template_slug(get_the_ID()) !== 'pageFestival.php'){
		remove_meta_box( $boxHeader->getId(), 'page', 'normal');
		remove_meta_box( $boxFooter->getId(), 'page', 'normal');
		remove_meta_box( $boxRubrique->getId(), 'page', 'normal');
	}else{
		remove_post_type_support('page', 'editor');
	}
});