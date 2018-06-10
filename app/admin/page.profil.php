<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 07/12/2017
 * Time: 16:33
 */


$boxUne = tr_meta_box('Image de la page profil');
$boxUne->addScreen('page'); // updated
$boxUne->setCallback(function(){
    $form = tr_form();

    $repeater = $form->repeater('profilslider')->setFields([
        $form->image('imageslider')->setLabel('Image du slider')
    ])->setLabel('Profil Slider');

    echo $repeater;

});

$boxvaleur = tr_meta_box('BlockProfil')->setLabel('Valeur de l\'entreprise');
$boxvaleur->addScreen('page'); // updated
$boxvaleur->setCallback(function(){
	$form = tr_form();
	$repeater = $form->repeater('profil')->setFields([
		$form->text('titrevaleur')->setLabel('Titre'),
		$form->editor('textevaleur')->setLabel('Contenu'),
		$form->image('imgvaleur')->setLabel('Image')
	])->setLabel('Liste des profils disponible');

	echo $repeater;

});



add_action('admin_head', function () use ($boxvaleur, $boxUne){
	if(get_page_template_slug(get_the_ID()) !== 'pageProfil.php'){
		remove_meta_box( $boxvaleur->getId(), 'page', 'normal');
		remove_meta_box( $boxUne->getId(), 'page', 'normal');
	}
});


