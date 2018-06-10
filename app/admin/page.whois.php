<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 07/12/2017
 * Time: 16:33
 */


$boxUne = tr_meta_box('Image de la page');
$boxUne->addScreen('page'); // updated
$boxUne->setCallback(function(){
    $form = tr_form();

    $repeater = $form->repeater('aboutslider')->setFields([
        $form->image('imageslider')->setLabel('Image du slider')
    ])->setLabel('About Slider');

    echo $repeater;

});

$box = tr_meta_box('Block')->setLabel('titre secondaire');
$box->addScreen('page'); // updated
$box->setCallback(function(){
	$form = tr_form();
	echo $form->text('secondtitle')->setLabel('Titre secondaire');

});

$boxbienvenu = tr_meta_box('BlockBienvenue')->setLabel('Information de l\'entreprise');
$boxbienvenu->addScreen('page'); // updated
$boxbienvenu->setCallback(function(){
	$form = tr_form();
	echo $form->text('titrebienvenu')->setLabel('Titre');
	echo $form->editor('textebienvenu')->setLabel('Contenu');
	echo $form->image('imgbienvenu')->setLabel('Image');

});

$boxvaleur = tr_meta_box('BlockValeur')->setLabel('Valeur de l\'entreprise');
$boxvaleur->addScreen('page'); // updated
$boxvaleur->setCallback(function(){
	$form = tr_form();
	$repeater = $form->repeater('valeurs')->setFields([
		$form->text('titrevaleur')->setLabel('Titre'),
		$form->editor('textevaleur')->setLabel('Contenu'),
		$form->image('imgvaleur')->setLabel('Image')
	])->setLabel('Liste des Valeurs');

	echo $repeater;

});



add_action('admin_head', function () use ($box, $boxvaleur, $boxbienvenu, $boxUne){
	if(get_page_template_slug(get_the_ID()) !== 'pageWhois.php'){
		remove_meta_box( $box->getId(), 'page', 'normal');
		remove_meta_box( $boxvaleur->getId(), 'page', 'normal');
		remove_meta_box( $boxbienvenu->getId(), 'page', 'normal');
		remove_meta_box( $boxUne->getId(), 'page', 'normal');
	}
});


