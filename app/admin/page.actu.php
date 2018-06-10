<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 23/02/2018
 * Time: 11:53
 */

$box = tr_meta_box('paramactu')->setLabel('Parametrage de la page');
$box->addScreen('page'); // updated
$box->setCallback(function(){
    $form = tr_form();

    echo $form->text('numberarticle')->setType('number')->setLabel('Nombre d\'article')->setAttributes(array('min' => 0))->setDefault(9);

});

add_action('admin_head', function () use ($box){
    if(get_page_template_slug(get_the_ID()) === 'pageActu.php'){
//        remove_post_type_support('page', 'editor');
    }else{
        remove_meta_box( $box->getId(), 'page', 'normal');
    }
});