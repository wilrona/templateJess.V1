<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 06/12/2017
 * Time: 09:28
 */

$mag = tr_post_type('Emploi', 'Emplois');

$mag->setIcon('books');

$mag->setArgument('supports', ['title', 'editor'] );

$typeContrat = tr_taxonomy('type contrat', 'types contrat', array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$typeContrat->addPostType('emploi');

$niveauProf = tr_taxonomy('Niveau', 'Niveaux professionnels',  array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$niveauProf->addPostType('emploi');
$niveauProf->setMainForm(function(){
	$form = tr_form();
	echo $form->text('nbre_min')->setLabel('Nombre de mois min')->setType('number');
	echo $form->text('nbre_max')->setLabel('Nombre de mois max')->setType('number');
});


$categorieEmploi = tr_taxonomy('Categorie d\'emploi', 'Categories d\'emploi',  array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$categorieEmploi->addPostType('emploi');
$categorieEmploi->setHierarchical();

$langue = tr_taxonomy('Langue', 'Langues',  array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$langue->addPostType('emploi');


$diplome = tr_taxonomy('Diplome', 'Diplomes', array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$diplome->addPostType('emploi');


$ville = tr_taxonomy('Ville', 'Villes', array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$ville->addPostType('emploi');

$competence = tr_taxonomy('Competence', 'Competences', array('show_in_quick_edit' => false, 'meta_box_cb' => false));
$competence->addPostType('emploi');
$competence->setMainForm(function() {
	$form = tr_form();

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

	echo $form->select('cate_emploi_comp')->setLabel('Catégorie d\'emploi')->setOptions($options);
});


$box_karact = tr_meta_box('caracteristiques')->setLabel('Caracteristique de l\'annonce');
$box_karact->addPostType( $mag->getId() );
$box_karact->setCallback(function (){
	$form = tr_form();

	$car = 6;
	$string = "";
	$chaine = "1234567890";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}

	echo $form->text('numero_annonce')->setLabel('Numero de l\'annonce')->setAttribute('disabled', 'disabled');


	echo $form->text('datefin')->setLabel('Date de fin de l\'annonce')->setAttribute('class', 'datepicker');

	$villes = get_terms( array(
		'taxonomy' => 'ville',
		'hide_empty' => false
	) );

	$options = array('Selection de la ville' => null);

	foreach ($villes as $ville){
		$options[$ville->name] = $ville->term_id;
	}
	echo $form->select('ville')->setLabel('Ville de l\'annonce')->setOptions($options)->setAttribute('class', 'select');

	$types = get_terms( array(
		'taxonomy' => 'type_contrat',
		'hide_empty' => false
	) );

	$options = array('Selection du type de contrat' => null);

	foreach ($types as $type){
		$options[$type->name] = $type->term_id;
	}

	echo $form->select('type_contrat')->setLabel('Type de contrat')->setOptions($options)->setAttribute('class', 'select');

	$nivPro = get_terms( array(
		'taxonomy' => 'niveau',
		'hide_empty' => false
	) );

	$options = array('Selection un niveau professionnel' => null);

	foreach ($nivPro as $type){
		$options[$type->name] = $type->term_id;
	}

	echo $form->select('niveau_professionnel')->setLabel('Niveau professionnel')->setOptions($options)->setAttribute('class', 'select');

	$terms = get_terms( array(
		'taxonomy' => 'categorie_demploi',
		'hide_empty' => false,
		'parent' => 0
	));

	$options = array('Selection categorie d\'emploi' => null);

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


	echo $form->select('categorie_emploi')->setLabel('Categorie d\'emploi')->setOptions($options)->setAttribute('class', 'select');


	$lang = get_terms( array(
		'taxonomy' => 'langue',
		'hide_empty' => false
	) );

	$options = array('Selection de la langue' => null);

	foreach ($lang as $lan){
		$options[$lan->name] = $lan->term_id;
	}

	echo $form->select('langue_offre[]')->setLabel('Langue')->setOptions($options)->multiple()->setAttribute('class', 'select');


	$diplome = get_terms( array(
		'taxonomy' => 'diplome',
		'hide_empty' => false
	) );

	$options = array('Selection du diplome' => null);

	foreach ($diplome as $niv){
		$options[$niv->name] = $niv->term_id;
	}

	echo $form->select('diplome')->setLabel('Diplome')->setOptions($options)->setAttribute('class', 'select');

	$categories = get_terms( array(
		'taxonomy' => 'competence',
		'hide_empty' => false
	) );

	$options = array('Selection des competences' => null);

	foreach ($categories as $cat){
		$options[$cat->name] = $cat->term_id;
	}

	echo $form->select('competence[]')->setLabel('Competences')->setOptions($options)->multiple()->setAttribute('class', 'select');

	echo $form->hidden('numero_annonce')->setDefault('jess'.$string);
});


$box_entreprise = tr_meta_box('entreprise_contacts')->setLabel('Information de contact');
$box_entreprise->addPostType( $mag->getId() );
$box_entreprise->setCallback(function () {
	$form = tr_form();

	echo $form->text('nomcontact')->setLabel('Nom du contact')->required();

	echo $form->text('emailcontact')->setLabel('Email du contact')->required()->setType('email');

	echo $form->text('numerocontact')->setLabel('Numero du contact');

	echo $form->text('entreprisecontact')->setLabel('Nom de l\'entreprise')->required();

});


function new_user_notification($user_id, $data, $message, $subject, $title, $link="", $messageLink='Clickez ici') {

	$user = new WP_User( $user_id );

	$user_login = stripslashes( $user->user_login );
	$user_email = stripslashes( $user->user_email );

	$email_subject = $subject;

	ob_start();



//		echo $message;

	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Email Confirmation</title>
		<style type="text/css">

			@media screen and (max-width: 600px) {
				table[class="container"] {
					width: 95% !important;
				}
			}

			#outlook a {padding:0;}
			body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
			.ExternalClass {width:100%;}
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
			#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
			img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
			a img {border:none;}
			.image_fix {display:block;}
			p {margin: 1em 0;}
			h1, h2, h3, h4, h5, h6 {color: black !important;}

			h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

			h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
				color: red !important;
			}

			h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
				color: purple !important;
			}

			table td {border-collapse: collapse;}

			table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

			a {color: #000;}

			@media only screen and (max-device-width: 480px) {

				a[href^="tel"], a[href^="sms"] {
					text-decoration: none;
					color: black; /* or whatever your want */
					pointer-events: none;
					cursor: default;
				}

				.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
					text-decoration: default;
					color: orange !important; /* or whatever your want */
					pointer-events: auto;
					cursor: default;
				}
			}


			@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
				a[href^="tel"], a[href^="sms"] {
					text-decoration: none;
					color: blue; /* or whatever your want */
					pointer-events: none;
					cursor: default;
				}

				.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
					text-decoration: default;
					color: orange !important;
					pointer-events: auto;
					cursor: default;
				}
			}

			@media only screen and (-webkit-min-device-pixel-ratio: 2) {
				/* Put your iPhone 4g styles in here */
			}

			@media only screen and (-webkit-device-pixel-ratio:.75){
				/* Put CSS for low density (ldpi) Android layouts in here */
			}
			@media only screen and (-webkit-device-pixel-ratio:1){
				/* Put CSS for medium density (mdpi) Android layouts in here */
			}
			@media only screen and (-webkit-device-pixel-ratio:1.5){
				/* Put CSS for high density (hdpi) Android layouts in here */
			}
			/* end Android targeting */
			h2{
				color:#181818;
				font-family:Helvetica, Arial, sans-serif;
				font-size:22px;
				line-height: 22px;
				font-weight: normal;
			}
			a.link1{

			}
			a.link2{
				color:#fff;
				text-decoration:none;
				font-family:Helvetica, Arial, sans-serif;
				font-size:16px;
				color:#fff;border-radius:4px;
			}
			p{
				color:#555;
				font-family:Helvetica, Arial, sans-serif;
				font-size:16px;
				line-height:160%;
			}
		</style>

		<script type="colorScheme" class="swatch active">
  {
    "name":"Default",
    "bgBody":"ffffff",
    "link":"fff",
    "color":"555555",
    "bgItem":"ffffff",
    "title":"181818"
  }
</script>

	</head>
	<body>

	<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
	<table cellpadding="0" width="100%" cellspacing="0" border="0" id="backgroundTable" class='bgBody'>
		<tr>
			<td>
				<table cellpadding="0" width="620" class="container" align="center" cellspacing="0" border="0">
					<tr>
						<td>
							<!-- Tables are the most common way to format your email consistently. Set your table widths inside cells and in most cases reset cellpadding, cellspacing, and border to zero. Use nested tables as a way to space effectively in your message. -->


							<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
								<tr>
									<td class='movableContentContainer bgItem'>

										<div class='movableContent'>
											<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
												<tr height="100">
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
												</tr>
												<tr>
													<td width="200" valign="top">&nbsp;</td>
													<td width="200" valign="top" align="center">
														<div class="contentEditableContainer contentImageEditable">
															<div class="contentEditable" align='center' >
																<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png"  alt='Logo'  data-default="placeholder" />
															</div>
														</div>
													</td>
													<td width="200" valign="top">&nbsp;</td>
												</tr>
												<tr height="35">
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
													<td width="200">&nbsp;</td>
												</tr>
											</table>
										</div>

										<div class='movableContent'>
											<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
												<tr>
													<td width="100%" colspan="3" align="center" style="padding-bottom:10px;padding-top:25px;">
														<div class="contentEditableContainer contentTextEditable">
															<div class="contentEditable" align='center' >
																<h2 ><?= $title ?></h2>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td width="100">&nbsp;</td>
													<td width="400" align="center">
														<div class="contentEditableContainer contentTextEditable">
															<div class="contentEditable" align='left' >
																<p >Cher/Chere  <?= $data['user_last_name'] ?> <?= $data['user_first_name'] ?>,
																	<br/>
																	<br/>
																	<?= $message; ?></p>
															</div>
														</div>
													</td>
													<td width="100">&nbsp;</td>
												</tr>
											</table>
											<?php if(!empty($link)): ?>
												<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
													<tr>
														<td width="200">&nbsp;</td>
														<td width="200" align="center" style="padding-top:25px;">
															<table cellpadding="0" cellspacing="0" border="0" align="center" width="200" height="50">
																<tr>
																	<td bgcolor="#ED006F" align="center" style="border-radius:4px;" width="200" height="50">
																		<div class="contentEditableContainer contentTextEditable">
																			<div class="contentEditable" align='center' >
																				<a target='_blank' href="<?= $link ?>" class='link2'><?= $messageLink ?></a>
																			</div>
																		</div>
																	</td>
																</tr>
															</table>
														</td>
														<td width="200">&nbsp;</td>
													</tr>
												</table>
											<?php endif; ?>
										</div>


										<div class='movableContent'>
											<table cellpadding="0" cellspacing="0" border="0" align="center" width="600" class="container">
												<tr>
													<td width="100%" colspan="2" style="padding-top:65px;">
														<hr style="height:1px;border:none;color:#333;background-color:#ddd;" />
													</td>
												</tr>
												<tr>
													<td width="60%" height="70" valign="middle" style="padding-bottom:20px;">
														<div class="contentEditableContainer contentTextEditable">
															<div class="contentEditable" align='left' >
																<span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">Envoyez à <?= $user_email ?> par Jess Assistance</span>
																<!-- <br/>
																<span style="font-size:11px;color:#555;font-family:Helvetica, Arial, sans-serif;line-height:200%;">[CLIENTS.ADDRESS] | [CLIENTS.PHONE]</span> -->
																<!-- <br/>
																<span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">
																<a target='_blank' href="[FORWARD]" style="text-decoration:none;color:#555">Forward to a friend</a>
																</span>
																<br/>
																<span style="font-size:13px;color:#181818;font-family:Helvetica, Arial, sans-serif;line-height:200%;">
																<a target='_blank' href="[UNSUBSCRIBE]" style="text-decoration:none;color:#555">click here to unsubscribe</a></span> -->
															</div>
														</div>
													</td>
													<td width="40%" height="70" align="right" valign="top" align='right' style="padding-bottom:20px;">
														<!-- <table width="100%" border="0" cellspacing="0" cellpadding="0" align='right'>
															<tr>
																<td width='57%'></td>
																<td valign="top" width='34'>
																	<div class="contentEditableContainer contentFacebookEditable" style='display:inline;'>
																		<div class="contentEditable" >
																			<img src="images/facebook.png" data-default="placeholder" data-max-width='30' data-customIcon="true" width='30' height='30' alt='facebook' style='margin-right:40x;'>
																		</div>
																	</div>
																</td>
																<td valign="top" width='34'>
																	<div class="contentEditableContainer contentTwitterEditable" style='display:inline;'>
																	  <div class="contentEditable" >
																		<img src="images/twitter.png" data-default="placeholder" data-max-width='30' data-customIcon="true" width='30' height='30' alt='twitter' style='margin-right:40x;'>
																	  </div>
																	</div>
																</td>
																<td valign="top" width='34'>
																	<div class="contentEditableContainer contentImageEditable" style='display:inline;'>
																	  <div class="contentEditable" >
																		<a target='_blank' href="#" data-default="placeholder"  style="text-decoration:none;">
																			<img src="images/pinterest.png" width="30" height="30" data-max-width="30" alt='pinterest' style='margin-right:40x;' />
																		</a>
																	  </div>
																	</div>
																</td>
															</tr>
														</table> -->
													</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
							</table>




						</td></tr></table>

			</td>
		</tr>
	</table>
	<!-- End of wrapper table -->



	</body>
	</html>
	<?php
	include("".get_template_directory_uri()."/email/email_footer.php" );

	$message = ob_get_contents();
	ob_end_clean();

	wp_mail( $user_email, $email_subject, $message );
}


function tutsplus_save_expiry_date_meta( $post_id ) {

	$posted = get_post($post_id);

	// Check if the current user has permission to edit the post. */
	if ( !current_user_can( 'edit_post', $posted->ID ) ):
		return;
	endif;

	if ($posted->post_type === 'emploi') {
		$new_expiry_date = tr_posts_field('datefin', $posted->ID);
		$new_expiry_date = str_replace('/', '-', $new_expiry_date);
		update_post_meta( $post_id, 'datefinconvert', strtotime($new_expiry_date) );

		if(!tr_posts_field('send_email_to_user', $posted->ID)){
			$senders = count_recommandee($posted->ID, false);
			while ($senders->have_posts()):
				$senders->posts;
				$user = get_user_by('id', tr_posts_field('user_cv', get_the_ID()));

				$userR = array();
				$userR['user_last_name'] = $user->user_lastname;
				$userR['user_first_name'] = $user->user_firstname;

				$subtitle = "Jess Assistance : Emploi disponible pour vous";

				$msg = "<h2>$posted->post_title</h2>";
				$msg .= "<p>$posted->post_content</p>";
				$msg .= "<p><a href='".get_the_permalink($posted->ID)."'>Postuler</a></p>";
				$msg .= "Pour toutes questions, contactez nous au  <b>(+237) 243 38 47 60 ou (+237) 657 85 80 65</b>";

				new_user_notification($user->ID,$userR,$msg, $subtitle, $subtitle, "" , "");

			endwhile;
			update_post_meta($post_id, "send_email_to_user", true);
		}
	}

}
add_action( 'save_post', 'tutsplus_save_expiry_date_meta' );

function add_table_columns_emploi($columns){
	$news_columns = array(
		'numero_annonce' => 'Numero annonce',
		'entreprise' => 'Entreprise',
		'date_fin' => 'Date de fin de l\'offre'
	);

	unset($columns['date']);

	$filtered_columns = array_merge($columns, $news_columns);

	return $filtered_columns;
}
add_filter('manage_emploi_posts_columns', 'add_table_columns_emploi');


function show_table_columns_content_emploi($columns){
	global $post;

	switch ($columns){
		case 'numero_annonce':
			$nom = tr_posts_field('numero_annonce', $post->ID);
			echo $nom;
			break;

		case 'entreprise':

			echo tr_posts_field('entreprisecontact', $post->ID);
			break;

		case 'date_fin':
			$date = tr_posts_field('datefinconvert', $post->ID);
			echo (! empty($date) ? date('d/m/Y', $date): '');
			break;
	}
}
add_filter('manage_emploi_posts_custom_column', 'show_table_columns_content_emploi');
//
function sortable_table_columns_emploi($columns){

	$columns['numero_annonce'] = 'numero_annonce';
	$columns['entreprise'] = 'entreprise';
	$columns['date_fin'] = 'date_fin';

	return $columns;

}
add_filter('manage_edit-emploi_sortable_columns', 'sortable_table_columns_emploi');
