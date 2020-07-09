<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 15/03/2018
 * Time: 13:24
 */

//$setting =  array('capabilities' => array('create_posts' => 'do_not_allow'), 'map_meta_cap' => true);
$setting =  array('capabilities' => array('create_posts' => 'do_not_allow'), 'map_meta_cap' => true);
$mag = tr_post_type('Curriculum', 'Curriculums', $setting);

$mag->setIcon('folder');

$mag->setArgument('supports', ['title', 'editor'] );

$typeContrat = tr_taxonomy('Situation matrimoniale', 'Situations matrimoniale', array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$typeContrat->addPostType('curriculum');



$box = tr_meta_box('caracteristiqueCV')->setLabel('Caracteristique du CV');
$box->addPostType( $mag->getId() );



$box->setCallback(function (){
	$form = tr_form();

	$exp_pro = 'Non Définie par l\'administrateur';

	$terms_exp_pro = get_terms( array(
		'taxonomy' => 'niveau',
		'hide_empty' => false
	) );

	foreach ($terms_exp_pro as $exp){
	    $min = tr_taxonomies_field('nbre_min', 'niveau', $exp->term_id);
	    $max = tr_taxonomies_field('nbre_max', 'niveau', $exp->term_id);
	    if(tr_posts_field('duree_exp_pro') >= $min && tr_posts_field('duree_exp_pro') <=  $max ) {
	        $exp_pro = $exp->name;
        }
    }

	echo $form->text('exp_pro')->setLabel('Expérience professionnelle')->setAttributes(array('disabled' => 'disabled', 'value' => $exp_pro));

    echo $form->text('duree_exp_pro')->setLabel('Durée Expérience professionnelle')->setAttribute('disabled', 'disabled')->setHelp('Durée expérience en mois');

	$terms = get_terms( array(
		'taxonomy' => 'categorie_demploi',
		'hide_empty' => false,
		'parent' => 0
	) );



	$options = array('Choisir une catégorie d\'emploi' => null);

	foreach ($terms as $term){

		$options[$term->name] = array();
		$childs = get_terms( array(
			'taxonomy' => 'categorie_demploi',
			'hide_empty' => false,
			'parent' => $term->term_id
		));

		foreach ($childs as $child){

			$options[$term->name][$child->name] = $child->term_id;

		}
	}

	echo $form->select('cate_emploi_cv')->setLabel('Catégorie d\'emploi')->setOptions($options)->setAttribute('class', 'select');


	$categories = get_terms( array(
		'taxonomy' => 'competence',
		'hide_empty' => false
	) );

	$options = array('Selection des competences' => null);

	foreach ($categories as $cat){
		$options[$cat->name] = $cat->term_id;
	}

	echo $form->select('competencecv[]')->setLabel('Competences')->setOptions($options)->multiple()->setAttribute('class', 'select');

	$lang = get_terms( array(
		'taxonomy' => 'langue',
		'hide_empty' => false
	) );

	$options = array('Selection de la langue' => null);

	foreach ($lang as $lan){
		$options[$lan->name] = $lan->term_id;
	}

	echo $form->select('langue_cv[]')->setLabel('Langue')->setOptions($options)->multiple()->setAttribute('class', 'select');

});

$boxExp = tr_meta_box('Exp_profs')->setLabel('Experience Profesionnelle');
$boxExp->addPostType( $mag->getId() );


$boxExp->setCallback(function (){
	$form = tr_form();

	$repeater = $form->repeater('exp_prof')->setFields([
		$form->text('nom_entreprise')->setLabel('Nom de l\'entreprise'),
		$form->editor('description_poste')->setLabel('Detail de la mission'),
		$form->text('date_debut_emploi')->setLabel('Date de debut')->setAttribute('class', 'datepicker'),
		$form->text('date_fin_emploi')->setLabel('Date de fin')->setAttribute('class', 'datepicker')
	])->setLabel('Ajouter des expériences professionnelles');

	echo $repeater;

});

$boxExpAcc = tr_meta_box('Exp_Academics')->setLabel('Experience Academique');
$boxExpAcc->addPostType( $mag->getId() );


$boxExpAcc->setCallback(function (){
	$form = tr_form();

	$diplome = get_terms( array(
		'taxonomy' => 'diplome',
		'hide_empty' => false
	) );

	$options = array('Selection du diplome' => null);

	foreach ($diplome as $niv){
		$options[$niv->name] = $niv->term_id;

	}

	$repeater = $form->repeater('exp_academic')->setFields([
		$form->select('diplome_academic')->setLabel('Diplome')->setOptions($options),
		$form->text('ecole_academic')->setLabel('Nom de l\'ecole'),
		$form->text('annee_debut_academic')->setLabel('Année début'),
		$form->text('annee_fin_academic')->setLabel('Année fin'),

	])->setLabel('Ajouter des expérriences academiques');

	echo $repeater;

});

$boxCandidat = tr_meta_box('information_client', '', array('context' => 'side'))->setLabel('Information du candidat');
$boxCandidat->addPostType( $mag->getId() );

$boxCandidat->setCallback(function (){
	global $post;

	$current_user = tr_posts_field('user_cv', $post->ID);
	$current_user = get_user_by('id', $current_user);

	?>

	<p class="post-attributes-label-wrapper"><label class="post-attributes-label">Nom du candidat</label></p>
	<span><?= $current_user->last_name ?> <?= $current_user->first_name ?></span>
	<hr>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label">Adresse Email</label></p>
	<span><?= $current_user->user_email ?></span>
	<hr>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label">Date de naissance</label></p>
	<span><?= tr_users_field('user_birth', $current_user->ID) ?></span>
	<hr>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label">Situation Matriminiale</label></p>
	<span><?= get_term(tr_users_field('user_matrimonial', $current_user->ID))->name ?></span>
	<hr>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label">Nombre d'enfant</label></p>
	<span><?= tr_users_field('user_enfant', $current_user->ID) ?></span>
	<hr>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label">Ville de residence</label></p>
	<span><?= tr_users_field('user_ville', $current_user->ID) ?></span>
	<hr>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label">Numéro de téléphone</label></p>
	<span><?= tr_users_field('user_phone', $current_user->ID) ?></span>
    <hr>
    <div id="major-publishing-actions">
	<?php
	    if(tr_posts_field('file_url')):
    ?>
    <div id="publishing-action" style="text-align: center !important;">
        <a href="<?= tr_posts_field('file_url') ?>" class="button button-primary button-large" target="_blank" id="publish">Telechargez le CV associe</a>
    </div>
    <?php
        endif;
    ?>
    </div>
    <hr>
    <div id="major-publishing-actions">
        <div id="publishing-action" style="text-align: center !important;">
            <a href="" class="button button-primary button-large" target="_blank" id="publish">Imprimer le CV</a>
        </div>
    </div>



<?php
});


//function wpse_76815_remove_publish_box() {
//	remove_meta_box( 'submitdiv', 'curriculum', 'side' );
//}
//add_action( 'admin_menu', 'wpse_76815_remove_publish_box' );