<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 07/12/2017
 * Time: 17:48
 */

$box = tr_meta_box('Form')->setLabel('Liste des services');
$box->addScreen('page'); // updated
$box->setCallback(function(){
	$form = tr_form();

    $repeater = $form->repeater('services')->setFields([
	    $form->text('tagscroll')->setLabel('Id du menu')->setHelp('Cette ID va permettre le scroll de la page sur la zone du service'),
        $form->search('serviceid')->setPostType('service')->setLabel('Recherche du service')
    ])->setLabel('Les services');

	echo $repeater;

});

add_action('admin_head', function () use ($box){
	if(get_page_template_slug(get_the_ID()) === 'pageServices.php'){
        remove_post_type_support('page', 'editor');
	}else{
        remove_meta_box( $box->getId(), 'page', 'normal');
    }
});
