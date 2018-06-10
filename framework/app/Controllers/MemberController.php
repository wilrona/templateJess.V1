<?php
namespace App\Controllers;

use Dompdf\Dompdf;
use Mpdf\Mpdf;
use \TypeRocket\Controllers\Controller;
use WP_Query;
use WP_User;

class MemberController extends Controller
{

	public function routing()
	{
		$this->setMiddleware('not_member', ['only' => ['login', 'logout', 'register', 'reset', 'pdfgenerated']]);
//		$this->setMiddleware('member', ['only' => ['profil', 'alerte', 'createcv', 'editcv']]);
	}

	private function dateToMySQL($date, $format){
		$tabDate = explode('/' , $date);
		$date  = $tabDate[2].'-'.$tabDate[1].'-'.$tabDate[0];

//		$date = date( 'Y-m-d H:i:s', strtotime($date) );
		$date = date( $format, strtotime($date) );


		return $date;
	}

	public  function login(){
		if(isset($_POST) && isset($_POST['_tr_nonce_form']) && wp_verify_nonce($_POST['_tr_nonce_form'], "form_seed_59cdf94920d34") ):

			$user = wp_signon($_POST);

		    $url = '';

		    if(isset($_POST['offerid']) && !empty($_POST['offerid'])):
                $url = '?offerid='.$_POST['offerid'];
            endif;

			if(is_wp_error($user)):
				$error = $user->get_error_message();
				flash('error_login', $error, 'uk-alert-danger');
				return tr_redirect()->toUrl(get_the_permalink(tr_options_field('pc_options.lien_page_candidat')).''.$url);
			else:
                if(get_user_meta( $user->ID, 'has_to_be_activated', true ) != false ):
	                wp_logout();
	                flash('error_login', 'Vous n\'avez pas encore confirmé votre adresse email', 'uk-alert-danger');
	                return tr_redirect()->toUrl(get_the_permalink(tr_options_field('pc_options.lien_page_candidat')).''.$url);
                else:

                    if(!empty($url)):
                        return tr_redirect()->toRoute('/candidat/candidature/create'.$url);
                    else:
                        return tr_redirect()->toRoute('/candidat/alerte');
			        endif;
			    endif;
			endif;
		endif;
	}

	public function confirmation(){
	    if(!empty($_GET['key']) && isset($_GET['key']) && !empty($_GET['user']) && isset($_GET['user'])):
		    delete_user_meta( $_GET['user'], 'has_to_be_activated' );
		    flash('success_register', 'Votre adresse email a été confirmé avec success', 'uk-alert-success');
        endif;
	    return tr_redirect()->toUrl(get_the_permalink(tr_options_field('pc_options.lien_page_candidat')));
    }

	public function register(){
		if(isset($_POST) && isset($_POST['_tr_nonce_form']) && wp_verify_nonce($_POST['_tr_nonce_form'], "form_seed_59cdf94920d34") ):

			if(
//			        empty($_POST['user_login']) ||
			   empty($_POST['user_first_name']) ||
			   empty($_POST['user_last_name']) ||
			   empty($_POST['user_pass']) ||
			   empty($_POST['user_email']) ||
			   empty($_POST['user_birth'])  ||
			   empty($_POST['user_ville']) ||
			   empty($_POST['user_sexe']) ||
			   intval($_POST['user_enfant']) < 0 ||
			   empty($_POST['user_phone']) ||
			   empty($_POST['user_matrimonial'])
			):
				flash('error_register', 'Des Champs n\'ont pas été renseignés.', 'uk-alert-danger');

			else:
				$numVerify = new \numVerify();
                $isValid = $numVerify->isValid($_POST['user_phone'], 'CM', false, true);

				if(!is_email($_POST['user_email'])):
					flash('error_register', 'Veuillez entrer un email valide.', 'uk-alert-danger');

				elseif(!$isValid):
					flash('error_register', 'Le format du numero n\'est pas correct.', 'uk-alert-danger');
				else:
					if(isset( $_POST['user_pass'] ) && $_POST['user_pass'] != $_POST['user_pass_2']):
						flash('error_register', 'Les deux mots de passe ne correspondent pas', 'uk-alert-danger');
					else:
						$user = wp_insert_user_customs(array(
							'user_login' => $_POST['user_email'],
							'first_name' => $_POST['user_first_name'],
							'last_name' => $_POST['user_last_name'],
							'user_pass' => $_POST['user_pass'],
							'user_email' => $_POST['user_email'],
							'user_registered' => date('Y-m-d H:i:s')
						));
						if(is_wp_error($user)):
							$error = $user->get_error_message();
							flash('error_register', $error, 'uk-alert-danger');
						else:

							add_user_meta( $user, 'user_ville', $_POST['user_ville'], true );
							add_user_meta( $user, 'user_sexe', $_POST['user_sexe'], true );
							add_user_meta( $user, 'user_enfant', $_POST['user_enfant'], true );
							add_user_meta( $user, 'user_matrimonial', $_POST['user_matrimonial'], true );
							add_user_meta( $user, 'user_birth', $_POST['user_birth'], true );
							add_user_meta( $user, 'user_phone', $_POST['user_phone'], true );

							$code = sha1( $user . time() );
							$activation_link = add_query_arg( array( 'key' => $code, 'user' => $user ), home_url('/candidat/email/confirmation'));
							add_user_meta( $user, 'has_to_be_activated', $code, true );

							$msg = "Nous vous remercions de vous être inscrit(e) sur notre site.</br>";
							$msg .= "Pour commencer a l'utiliser, merci de confirmer votre adresse email en cliquant sur le bouton ci-desous. </br></br>";
							$msg .= "Pour toutes questions, contactez nous au  <b>(+237) 243 38 47 60 ou (+237) 657 85 80 65</b>";

							$subtitle = "Bienvenue chez Jess Assistance";
							$this->wp_new_user_notification($user, $_POST, $msg, $subtitle, $subtitle, $activation_link);

							flash('success_register', 'Votre inscription a été enregistrée avec succès. <br/> Un mail de confirmation vous a été envoyé dans votre boite email. ', 'uk-alert-success');

							return tr_redirect()->toUrl(get_the_permalink(tr_options_field('pc_options.lien_page_candidat')));

						endif;
					endif;
				endif;
			endif;

			return tr_redirect()->with($_POST)->back();


		endif;
	}

	public function logout(){
		wp_logout();
		return tr_redirect()->toRoute('/');
	}

	public function reset(){

		global $wpdb;

		// check if we're in reset form
		if( isset($_POST['_tr_nonce_form']) && wp_verify_nonce($_POST['_tr_nonce_form'], "form_seed_59cdf94920d34")  )
		{
			$email = trim($_POST['user_login']);

			if( empty( $email ) ) {
				flash('error_email', 'Entrez une adresse email', 'uk-alert-danger');

			} else if( ! is_email( $email )) {
				flash('error_email', 'Adresse Email invalide', 'uk-alert-danger');

			} else if( ! email_exists( $email ) ) {
				flash('error_email', 'Nous n\'avons pas d\'utilisateur correspondant a cette adresse email', 'uk-alert-danger');
			} else {

				$random_password = wp_generate_password( 12, false );
				$user = get_user_by( 'email', $email );

				$update_user = wp_update_user( array (
						'ID' => $user->ID,
						'user_pass' => $random_password
					)
				);

				// if  update user return true then lets send user an email containing the new password
				if( $update_user ) {
					$to = $email;
					$subject = 'Votre nouveau mot de passe sur Jess Assistance';
					$sender = get_option('name');

					$message = 'Votre nouveau mot de passe est : '.$random_password;

					$headers[] = 'MIME-Version: 1.0' . "\r\n";
					$headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers[] = "X-Mailer: PHP \r\n";
					$headers[] = 'From: '.$sender.' < '.$email.'>' . "\r\n";

					$mail = wp_mail( $to, $subject, $message, $headers );
					if( $mail )
						flash('success_send', 'Vous avez recu un email avec votre nouveau mot de passe', 'uk-alert-success');

				} else {
					flash('error_send', 'Desole nous n\'avons pas pu modifier le mot de passe de votre compte');

				}

			}

			return tr_redirect()->toRoute('/candidat/reset');
		}

		return tr_view('reset');
	}

	public function annonce(){


		if(isset($_POST['_tr_nonce_form']) && wp_verify_nonce($_POST['_tr_nonce_form'], "form_seed_59cdf94920d34") ):

			$options = [

				'ville_emploi'  => 'required',
				'titre_annonce' => 'required',
				'date_fin_annonce' => 'required',
				'type_contrat' => 'required',
				'categorie_demploi' => 'required',
				'niveau_professionnel' => 'required',
				'diplome' => 'required',
				'langue' => 'required',
				'competence' => 'required',
				'nom_de_contact' => 'required',
				'nom_entreprise' => 'required',
				'email_de_contact' => 'required|email',
				'telephone_contact' => 'required|numeric'

			];

			$validator = tr_validator($options, $_POST);

			if($validator->getErrors()):

				foreach ($validator->getErrors() as $key => $error){
					flash( $key, $error, 'uk-alert-danger');
				}

				return tr_redirect()->with($_POST)->toUrl(get_the_permalink(tr_options_field('pc_options.lien_page_recruteur')));

			else:

				$title     = $_POST['titre_annonce'];
				$content   = $_POST['description'];
				$post_type = 'emploi';

				$new_post = array(
					'post_title'    => $title,
					'post_content'  => $content,
					'post_status'   => 'pending',
					'post_type'     => $post_type
				);

				$pid = wp_insert_post($new_post);

				$ville_emploi  = $_POST['ville_emploi'];
				$date_fin_annonce = $_POST['date_fin_annonce'];
				$type_contrat = $_POST['type_contrat'];
				$categorie_demploi = $_POST['categorie_demploi'];
				$niveau_professionnel = $_POST['niveau_professionnel'];
				$diplome = $_POST['diplome'];
				$langue = $_POST['langue'];
				$competence = $_POST['competence'];
				$nom_de_contact = $_POST['nom_de_contact'];
				$nom_entreprise = $_POST['nom_entreprise'];
				$email_de_contact = $_POST['email_de_contact'];
				$telephone_contact = $_POST['telephone_contact'];

				$car = 6;
				$string = "";
				$chaine = "1234567890";
				srand((double)microtime()*1000000);
				for($i=0; $i<$car; $i++) {
					$string .= $chaine[rand()%strlen($chaine)];
				}


				add_post_meta($pid, 'datefin', strtotime($date_fin_annonce), true);
				add_post_meta($pid, 'ville', $ville_emploi, true);
				add_post_meta($pid, 'type_contrat', $type_contrat, true);
				add_post_meta($pid, 'niveau_professionnel', $niveau_professionnel, true);
				add_post_meta($pid, 'categorie_emploi', $categorie_demploi, true);
				add_post_meta($pid, 'langue_offre', $langue, true);
				add_post_meta($pid, 'diplome', $diplome, true);
				add_post_meta($pid, 'competence', $competence, true);
				add_post_meta($pid, 'numero_annonce', 'jessa'.$string, true);
				add_post_meta($pid, 'nomcontact', $nom_de_contact, true);
				add_post_meta($pid, 'emailcontact', $email_de_contact, true);
				add_post_meta($pid, 'numerocontact', $telephone_contact, true);
				add_post_meta($pid, 'entreprisecontact', $nom_entreprise, true);
				add_post_meta($pid, 'send_email_to_user', false, true);

				flash( 'success', 'Votre annonce a été enregistré avec succès.', 'uk-alert-success');

				$this->wp_notification($pid);

				return tr_redirect()->toUrl(get_the_permalink(tr_options_field('pc_options.lien_page_recruteur')));

			endif;

		endif;

	}

	public function alerte(){

		$currentUser = wp_get_current_user();

		if($currentUser->ID == 0){

			$this->response->setError( 'auth', false );
			$this->response->flashNow( "Vous n'avez pas les droits d'accès à cette page. <a href='".get_the_permalink(tr_options_field('pc_options.lien_page_candidat'))."'>Connectez-vous</a>.", 'error' );
			$this->response->exitAny(401);
		}

		$custom_args = array(
			'post_type' => 'curriculum',
			'meta_query' => array(
				array(
					'key'     => 'user_cv',
					'compare' => '=',
					'value' => $currentUser->ID
				)
			),
			'orderby' => 'key',
			'order' => 'ASC'
		);

		$listeCV = new WP_Query( $custom_args );

		if(!$listeCV->have_posts()){
		    return tr_redirect()->toRoute('/candidat/curriculum/create');
        }

		$terms_cat = array();
		$terms_comp = array();
		$terms_lang = array();

		$idCV = array();

		while ( $listeCV->have_posts() ) : $listeCV->the_post();
			array_push($terms_cat, intval(tr_posts_field('cate_emploi_cv', get_the_ID())));
            foreach (tr_posts_field('competencecv', get_the_ID()) as $comp){
	            array_push($terms_comp, intval($comp));
            }
			foreach (tr_posts_field('langue_cv', get_the_ID()) as $lang){
				array_push($terms_lang, intval($lang));
			}

			array_push($idCV, get_the_ID());
        endwhile;

		$terms_comp =  array_unique($terms_comp);
		$terms_lang =  array_unique($terms_lang);
		$terms_cat =  array_unique($terms_cat);

		if(!sizeof($terms_cat)){
			array_push($terms_cat, '0');
		}

		$custom_args_q1 = array(
			'post_type' => 'emploi',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key'     => 'categorie_emploi',
					'value'   => $terms_cat,
					'compare' => 'IN',
				),
			)
		);


		if(sizeof($terms_comp)){
			$compet = array(
				'relation' => 'OR'
			);
			foreach ($terms_comp as $comp){
				$value = array(
					'key'     => 'competence',
					'value'   =>  $comp,
					'compare' => 'LIKE',
				);

				array_push($compet, $value);
			}

			array_push($custom_args_q1['meta_query'], $compet);

		}

		if(sizeof($terms_lang)){
			$compet = array(
				'relation' => 'OR'
			);
			foreach ($terms_lang as $lang){
				$value = array(
					'key'     => 'langue',
					'value'   =>  $lang,
					'compare' => 'LIKE',
				);

				array_push($compet, $value);
			}

			array_push($custom_args_q1['meta_query'], $compet);

		}



		$q1 = new WP_Query( $custom_args_q1 );

		$terms_unique = array();
		while ($q1->have_posts()): $q1->the_post();
			array_push($terms_unique, get_the_ID());
		endwhile;

		if(!sizeof($terms_unique)){
			array_push($terms_unique, 0);
		}

		$today = time();
		$custom_args = array(
			'post_type' => 'emploi',
			'post__in' => $terms_unique,
			'meta_query' => array(
				array(
					'key'     => 'datefinconvert',
					'compare' => '>=',
					'value' => $today
				)
			),
			'orderby' => 'key',
			'order' => 'ASC'
		);

		$data = new WP_Query( $custom_args );

		return tr_view('profil/index', ['data' => $data, 'cv' => $idCV]);

	}

	public function profil(){

		$currentUser = wp_get_current_user();

		if($currentUser->ID == 0){

			$this->response->setError( 'auth', false );
			$this->response->flashNow( "Vous n'avez pas les droits d'accès à cette page. <a href='".get_the_permalink(tr_options_field('pc_options.lien_page_candidat'))."'>Connectez-vous</a>.", 'error' );
			$this->response->exitAny(401);
		}

		if(isset($_POST) && isset($_POST['_tr_nonce_form']) && wp_verify_nonce($_POST['_tr_nonce_form'], "form_seed_59cdf94920d34") ):

			if(empty($_POST['user_first_name']) ||
			   empty($_POST['user_last_name']) ||
//			   empty($_POST['user_email']) ||
			   empty($_POST['user_birth'])  ||
			   empty($_POST['user_ville']) ||
			   empty($_POST['user_sexe']) ||
			   intval($_POST['user_enfant']) < 0 ||
			   empty($_POST['user_phone']) ||
			   empty($_POST['user_matrimonial'])
			):
				flash('error_register', 'Des Champs n\'ont pas été renseignés.', 'uk-alert-danger');

			else:
//				if(!is_email($_POST['user_email'])):
//					flash('error_register', 'Veuillez entrer un email valide.', 'uk-alert-danger');
//
//				else:
					if(isset( $_POST['user_pass'] ) && $_POST['user_pass'] != $_POST['user_pass_2']):
						flash('error_register', 'Les deux mots de passe ne correspondent pas', 'uk-alert-danger');
					else:
						$user = wp_update_user(array(
							'ID' => $currentUser->ID,
							'first_name' => $_POST['user_first_name'],
							'last_name' => $_POST['user_last_name'],
							'user_email' => $_POST['user_email']
						));
						if(is_wp_error($user)):
							$error = $user->get_error_message();
							flash('error_register', $error, 'uk-alert-danger');
						else:

							update_user_meta( $user, 'user_ville', $_POST['user_ville']);
							update_user_meta( $user, 'user_sexe', $_POST['user_sexe']);
							update_user_meta( $user, 'user_enfant', $_POST['user_enfant']);
							update_user_meta( $user, 'user_matrimonial', $_POST['user_matrimonial']);
							update_user_meta( $user, 'user_birth', $_POST['user_birth']);
							update_user_meta( $user, 'user_phone', $_POST['user_phone']);

							flash('success_register', 'Vos modifications ont été enregistrées avec success', 'uk-alert-success');

							return tr_redirect()->toRoute('candidat/profil');

						endif;
					endif;
//				endif;
			endif;

			return tr_redirect()->with($_POST)->toRoute('candidat/profil');


		endif;

		$user_by_id = get_user_by('id', $currentUser->ID);

		return tr_view('profil/profil', ['current' => $user_by_id->data]);

	}

	public function createcv(){

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		$currentUser = wp_get_current_user();

		if($currentUser->ID == 0){

			$this->response->setError( 'auth', false );
			$this->response->flashNow( "Vous n'avez pas les droits d'accès à cette page. <a href='".get_the_permalink(tr_options_field('pc_options.lien_page_candidat'))."'>Connectez-vous</a>.", 'error' );
			$this->response->exitAny(401);
		}

		if(isset($_POST['_tr_nonce_form']) && wp_verify_nonce($_POST['_tr_nonce_form'], "form_seed_59cdf94920d34") ):

			$url = '';

			if(isset($_GET['offerid']) && !empty($_GET['offerid'])):
				$url = '?offerid='.$_GET['offerid'];
			endif;

			$options = [

				'titre_du_cv'  => 'required',
				'categorie_emploi' => 'required',
				'competence' => 'required',
				'langue' => 'required',
				'experience_academic.*.diplome_academic' => 'required',
				'experience_academic.*.ecole_academic' => 'required',
				'experience_academic.*.annee_debut_academic' => 'required',
				'experience_academic.*.annee_fin_academic' => 'required',
				'experience_professionnel.*.nom_entreprise' => 'required',
//				'experience_professionnel.*.description_poste' => 'required',
				'experience_professionnel.*.date_debut_emploi' => 'required',
				'experience_professionnel.*.date_fin_emploi' => 'required',
			];

			$validator = tr_validator($options, $_POST);

			if($validator->getErrors()):

				foreach ($validator->getErrors() as $key => $error){
					flash( $key, $error, 'uk-alert-danger');
				}

				return tr_redirect()->with($_POST)->toRoute('/candidat/curriculum/create');

			else:


				$type_file = array('pdf', 'jpg', 'jpeg', 'doc', 'docx');
				$movefile_upload = '';

				if($_FILES["file_cv"]["size"]){

					$car = 6;
					$string = "";
					$chaine = "1234567890";
					srand((double)microtime()*1000000);
					for($i=0; $i<$car; $i++) {
						$string .= $chaine[rand()%strlen($chaine)];
					}

					$temp = explode(".", $_FILES["file_cv"]["name"]);

					if(in_array($temp[1], $type_file)){

					    $uploadedfile = $_FILES['file_cv'];


						$uploadedfile["name"] = $temp[0].'-'.$string.'.'.$temp[1];

					    $upload_overrides = array( 'test_form' => false );
					    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

					    if ( $movefile && ! isset( $movefile['error'] ) ) {

						    $movefile_upload = $movefile;
					    }

                    }else{
					    flash( 'file', 'Le fichier n\'est pas dans le bon format (pdf/jpg/jpeg/doc/docx)', 'uk-alert-danger');
					    return tr_redirect()->with($_POST)->toRoute('/candidat/curriculum/create');
                    }

				}

				if($movefile_upload):

                    $title     = $_POST['titre_du_cv'];
                    $content   = $_POST['description'];
                    $post_type = 'curriculum';

                    $new_post = array(
                        'post_title'    => $title,
                        'post_content'  => $content,
                        'post_status'   => 'publish',
                        'post_type'     => $post_type
                    );

                    $pid = wp_insert_post($new_post);

                    $competence = $_POST['competence'];
                    $langue = $_POST['langue'];
                    $categorie_emploi = $_POST['categorie_emploi'];

                    $exp_prof = $_POST['experience_professionnel'];
                    $exp_acad = $_POST['experience_academic'];

                    add_post_meta($pid, 'competencecv', $competence, true);
                    add_post_meta($pid, 'langue_cv', $langue, true);
                    add_post_meta($pid, 'cate_emploi_cv', $categorie_emploi, true);
                    add_post_meta($pid, 'exp_prof', $exp_prof, true);
                    add_post_meta($pid, 'exp_academic', $exp_acad, true);

                    add_post_meta($pid, 'file_path', $movefile_upload['file'], true);
                    add_post_meta($pid, 'file_url', $movefile_upload['url'], true);


                    //// calcul année d'expérience
                    ///
                    $experience_pro = 0;
                    foreach ($exp_prof as $prof){

                        $year1 = $this->dateToMySQL($prof['date_debut_emploi'], 'Y');
                        $year2 = $this->dateToMySQL($prof['date_fin_emploi'], 'Y');


                        $month1 = $this->dateToMySQL($prof['date_debut_emploi'], 'm');
                        $month2 = $this->dateToMySQL($prof['date_fin_emploi'], 'm');

                        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

                        $experience_pro = $diff + $experience_pro;

                    }

                    add_post_meta($pid, 'duree_exp_pro', $experience_pro, true);
                    add_post_meta($pid, 'user_cv', $currentUser->ID, true);

                    flash( 'success', 'Votre CV a été enregistré avec succès.', 'uk-alert-success');

                    if($url){
                        return tr_redirect()->toRoute('/candidat/candidature/create'.$url);
                    }else{
                        return tr_redirect()->toRoute('/candidat/curriculum');
                    }
                else:
                    flash('file', 'Les fichiers CV est obligatoires', 'uk-alert-danger');
	                return tr_redirect()->with($_POST)->toRoute('/candidat/curriculum/create');

			    endif;
			endif;


		endif;

		return tr_view('curriculum/create');
	}

	public function editcv(){

		$current_cv_id = $_GET['id'];

		if(!isset($_GET['id']) || empty($current_cv_id)){
			return tr_redirect()->toRoute('/candidat/curriculum');
		}


		$currentUser = wp_get_current_user();

		if($currentUser->ID == 0){

			$this->response->setError( 'auth', false );
			$this->response->flashNow( "Vous n'avez pas les droits d'accès à cette page. <a href='".get_the_permalink(tr_options_field('pc_options.lien_page_candidat'))."'>Connectez-vous</a>.", 'error' );
			$this->response->exitAny(401);
		}


		if(isset($_POST['_tr_nonce_form']) && wp_verify_nonce($_POST['_tr_nonce_form'], "form_seed_59cdf94920d34") ):

			$options = [

				'titre_du_cv'  => 'required',
				'categorie_emploi' => 'required',
				'competence' => 'required',
				'langue' => 'required',
				'experience_academic.*.diplome_academic' => 'required',
				'experience_academic.*.ecole_academic' => 'required',
				'experience_academic.*.annee_debut_academic' => 'required',
				'experience_academic.*.annee_fin_academic' => 'required',
				'experience_professionnel.*.nom_entreprise' => 'required',
//				'experience_professionnel.*.description_poste' => 'required',
				'experience_professionnel.*.date_debut_emploi' => 'required',
				'experience_professionnel.*.date_fin_emploi' => 'required',
			];

			$validator = tr_validator($options, $_POST);

			if($validator->getErrors()):

				foreach ($validator->getErrors() as $key => $error){
					flash( $key, $error, 'uk-alert-danger');
				}

				return tr_redirect()->with($_POST)->toRoute('/candidat/curriculum/edit?id='.$current_cv_id);

			else:

				$type_file = array('pdf', 'jpg', 'jpeg', 'doc', 'docx');
				$movefile_upload = '';

				if($_FILES["file_cv"]["size"]){

					$car = 6;
					$string = "";
					$chaine = "1234567890";
					srand((double)microtime()*1000000);
					for($i=0; $i<$car; $i++) {
						$string .= $chaine[rand()%strlen($chaine)];
					}

					$temp = explode(".", $_FILES["file_cv"]["name"]);

					if(in_array($temp[1], $type_file)){

						$uploadedfile = $_FILES['file_cv'];


						$uploadedfile["name"] = $temp[0].'-'.$string.'.'.$temp[1];

						$upload_overrides = array( 'test_form' => false );
						$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

						if ( $movefile && ! isset( $movefile['error'] ) ) {

							$movefile_upload = $movefile;
						}

					}else{
						flash( 'file', 'Le fichier n\'est pas dans le bon format (pdf/jpg/jpeg/doc/docx)', 'uk-alert-danger');
						return tr_redirect()->with($_POST)->toRoute('/candidat/curriculum/edit?id='.$current_cv_id);
					}

				}


				$title     = $_POST['titre_du_cv'];
				$content   = $_POST['description'];
				$post_type = 'curriculum';

				$new_post = array(
					'ID' => $current_cv_id,
					'post_title'    => $title,
					'post_content'  => $content,
					'post_status'   => 'publish',
					'post_type'     => $post_type
				);

				$pid = wp_update_post($new_post);

				$competence = $_POST['competence'];
				$langue = $_POST['langue'];
				$categorie_emploi = $_POST['categorie_emploi'];

				$exp_prof = $_POST['experience_professionnel'];
				$exp_acad = $_POST['experience_academic'];


				update_post_meta($pid, 'competencecv', $competence);
				update_post_meta($pid, 'langue_cv', $langue);
				update_post_meta($pid, 'cate_emploi_cv', $categorie_emploi);
				update_post_meta($pid, 'exp_prof', $exp_prof);
				update_post_meta($pid, 'exp_academic', $exp_acad);
				update_post_meta($pid, 'file_path', $movefile_upload['file'], true);
				update_post_meta($pid, 'file_url', $movefile_upload['url'], true);


				//// calcul année d'expérience
				///
				$experience_pro = 0;
				foreach ($exp_prof as $prof){

					$year1 = $this->dateToMySQL($prof['date_debut_emploi'], 'Y');
					$year2 = $this->dateToMySQL($prof['date_fin_emploi'], 'Y');


					$month1 = $this->dateToMySQL($prof['date_debut_emploi'], 'm');
					$month2 = $this->dateToMySQL($prof['date_fin_emploi'], 'm');

					$diff = (($year2 - $year1) * 12) + ($month2 - $month1);

					$experience_pro = $diff + $experience_pro;

				}

				update_post_meta($pid, 'duree_exp_pro', $experience_pro);
				update_post_meta($pid, 'user_cv', $currentUser->ID);

				flash( 'success', 'Votre CV a été modifié avec succès.', 'uk-alert-success');

				return tr_redirect()->toRoute('/candidat/curriculum');

			endif;


		endif;

		$current_cv = get_post($current_cv_id);


		return tr_view('curriculum/edit', ['current' => $current_cv]);
	}

	public function curriculum(){

		return tr_view('profil/curriculum');

	}

    public function wp_notification($annonce_id){

	    $annonce = get_post($annonce_id);

	    ob_start();

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
                                                                    <h2 >Nouvelle Annonce sur le site web</h2>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100">&nbsp;</td>
                                                        <td width="400" align="center">
                                                            <div class="contentEditableContainer contentTextEditable">
                                                                <div class="contentEditable" align='left' >
                                                                    <p >Bonjour Administrateur d'annonce,
                                                                        <br/>
                                                                        <br/>
                                                                        Vous avez une nouvelle annonce "<?= $annonce->post_title ?>" postuler par <strong><?= tr_posts_field('nomcontact', $annonce->ID )?></strong>
                                                                        pour le compte de la société <strong><?= tr_posts_field('entreprisecontact', $annonce->ID )?></strong>.
                                                                        <br>
                                                                        Connectez-vous pour avoir plus d'informations sur l'annonce.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td width="100">&nbsp;</td>
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


	    $message = ob_get_contents();
	    ob_end_clean();

	    if(tr_options_field('pc_options.email_admin')):

            foreach (tr_options_field('pc_options.email_admin') as $email){
                wp_mail( $email['email'], '[Nouvelle Annonce] '.$annonce->post_title, $message );
            }

        endif;



    }

	function wp_new_user_notification($user_id, $data, $message, $subject, $title, $link="", $messageLink='Clickez ici') {

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

		$message = ob_get_contents();
		ob_end_clean();

		wp_mail( $user_email, $email_subject, $message );
	}

	public function candudatureCreated(){

		$currentUser = wp_get_current_user();

		if($currentUser->ID == 0){
            return tr_redirect()->toRoute('/candidat?offerid='.$_GET['offerid']);
		}

		if(!isset($_GET['offerid']) || empty($_GET['offerid'])){
			return tr_redirect()->toRoute('/');
        }

        if(isset($_GET['cvid']) && !empty($_GET['cvid']) && isset($_GET['offerid'])){
	        $result_candidat = array();
	        $result_emploi = array();


	        $currentOffer = get_post($_GET['offerid']);

	        $activation_link = get_the_permalink(tr_options_field('pc_options.lien_page_annonce'));

	        $user = array();
	        $user['user_last_name'] = $currentUser->user_lastname;
	        $user['user_first_name'] = $currentUser->user_firstname;

	        $msg = "Votre candidature au poste de ".$currentOffer->post_title." a été prise en compte.</br>";
	        $msg .= "De ce fait, nous pourrons vous contacter pour passer a l'etape suivante. Bonne Chance </br></br>";
	        $msg .= "Pour toutes questions, contactez nous au  <b>(+237) 243 38 47 60 ou (+237) 657 85 80 65</b>";

	        $subtitle = "Candidature au poste de ".$currentOffer->post_title;



	        $foundCandidat = get_post_meta($_GET['offerid'], 'candidatureEmploi', true);
	        if($foundCandidat){

		        $exist = false;
		        foreach($foundCandidat as $recrut){
			        if($currentUser->ID === $recrut['idcandidat']  && $recrut['idcv'] === $_GET['cvid']){
				        $exist = true;
			        }
		        }

		        if(!$exist){
			        $candidat = array('idcandidat' => $currentUser->ID, 'idcv' => $_GET['cvid'], 'dataCandidature' => date('Y-m-d H:i:s'));
			        $emploi = array('idemploi' => $_GET['offerid'], 'idcv' => $_GET['cvid'], 'dataCandidature' => date('Y-m-d H:i:s'));

			        array_push($result_candidat, $candidat);
			        array_push($result_emploi, $emploi);

			        update_post_meta($_GET['offerid'], 'candidatureEmploi', $result_candidat);

			        update_user_meta($currentUser->ID, 'candidatureEmploi', $result_emploi);

			        flash('success_candidature', 'Votre candidature a été prise en compte ', 'uk-alert-success');
			        $this->wp_new_user_notification($currentUser->ID, $user, $msg, $subtitle, $subtitle, $activation_link, 'Consulter nos offres');
		        }else{
			        flash('error_candidature', 'Votre candidature a déja été prise en compte ', 'uk-alert-warning');

		        }
	        }else{
		        $candidat = array('idcandidat' => $currentUser->ID, 'idcv' => $_GET['cvid'], 'dataCandidature' => date('Y-m-d H:i:s'));
		        $emploi = array('idemploi' => $_GET['offerid'], 'idcv' => $_GET['cvid'], 'dataCandidature' => date('Y-m-d H:i:s'));

		        array_push($result_candidat, $candidat);
		        array_push($result_emploi, $emploi);

		        update_post_meta($_GET['offerid'], 'candidatureEmploi', $result_candidat);

		        update_user_meta($currentUser->ID, 'candidatureEmploi', $result_emploi);

		        flash('success_candidature', 'Votre candidature a été prise en compte ', 'uk-alert-success');
		        $this->wp_new_user_notification($currentUser->ID, $user, $msg, $subtitle, $subtitle, $activation_link, 'Consulter nos offres');

	        }
	        return tr_redirect()->toUrl(get_the_permalink($_GET['offerid']));
        }

		$custom_args = array(
			'post_type' => 'curriculum',
			'meta_query' => array(
				array(
					'key'     => 'user_cv',
					'compare' => '=',
					'value' => $currentUser->ID
				)
			),
			'orderby' => 'key',
			'order' => 'ASC'
		);

		$custom_query = new WP_Query( $custom_args );

		if ( !$custom_query->have_posts() ) {
			flash('message', 'Créez un CV pour pouvoir soumettre une candidature ', 'uk-alert-success');
		    return tr_redirect()->toRoute('/candidat/curriculum/create?offerid='.$_GET['offerid']);
        }

        return tr_view('annonceur/curriculum');

    }

    public function candudature(){

	    $currentUser = wp_get_current_user();

	    if($currentUser->ID == 0){

		    $this->response->setError( 'auth', false );
		    $this->response->flashNow( "Vous n'avez pas les droits d'accès à cette page. <a href='".get_the_permalink(tr_options_field('pc_options.lien_page_candidat'))."'>Connectez-vous</a>.", 'error' );
		    $this->response->exitAny(401);
	    }

	    $candidature = tr_users_field('candidatureEmploi', $currentUser->ID);

	    return tr_view('profil/candidature', ['data' => $candidature]);
    }

    public function pdfgenerated($id){



            $current_cv = get_post($id);

            $current_user = tr_posts_field('user_cv', $id);
            $current_user = get_user_by('id', $current_user);

            // instantiate and use the dompdf class
            ob_start();
            ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html>
        <head>

            <title>Jonathan Doe | Web Designer, Director | name@yourdomain.com</title>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />

            <meta name="keywords" content="" />
            <meta name="description" content="" />

            <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/cv/reset-fonts-grids.css" media="all" />
            <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/cv/resume.css" media="all" />
            <style>
                @page{
                    margin: 0cm 0cm;
                }

                body{
                    margin-top: 2cm;
                    margin-left: 2cm;
                    margin-right: 2cm;
                    margin-bottom: 2cm;
                }
            </style>
        </head>
        <body>
        <main>
            <div id="doc1" class="yui-t7" style="width: 100% !important;">
                <div id="inner" style="margin: 0 !important;">

                    <div id="hd">
                        <div class="yui-gc">
                            <div class="yui-u first">
                                <h1><?= $current_cv->post_title ?></h1>
                                <?php
                                $exp_pro = '';

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
                                ?>
                                <h2><?php if(!empty($exp_pro)): ?> <?= $exp_pro.", " ?> <?php endif; ?> <?= tr_users_field('user_ville', $current_user->ID) ?></h2>
                            </div>

                            <div class="yui-u">
                                <div class="contact-info" style="text-align: right">
                                    <h2>NO <?= $id ?></h2>
                                </div><!--// .contact-info -->
                            </div>
                        </div><!--// .yui-gc -->
                    </div><!--// hd -->

                    <div id="bd">
                        <div id="yui-main">
                            <div class="yui-b">

                                <div class="yui-gf">
                                    <div class="yui-u first">
                                        <h2>Profil</h2>
                                    </div>
                                    <div class="yui-u">
                                        <div class="contact-info">
                                            <h3>Né le <?= tr_users_field('user_birth', $current_user->ID) ?></h3>
                                            <h3><?= get_term(tr_users_field('user_matrimonial', $current_user->ID))->name ?>, <?= tr_users_field('user_enfant', $current_user->ID) ?> enfant(s)</h3>
                                        </div><!--// .contact-info -->
                                        <p class="enlarge" style="padding: 0 !important;">
                                            <?= $current_cv->post_content; ?>
                                        </p>
                                    </div>
                                </div><!--// .yui-gf -->

                                <div class="yui-gf">
                                    <div class="yui-u first">
                                        <h2>Compétence</h2>
                                    </div>
                                    <div class="yui-u">

                                        <?php
                                        foreach (tr_posts_field('competencecv', $id) as $compet):
                                            ?>
                                            <div class="" style="display: inline-block;">
                                                <h2><?= get_term($compet, 'competence')->name ?></h2>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div><!--// .yui-gf -->

                                <div class="yui-gf">

                                    <div class="yui-u first">
                                        <h2>Experience Prof.</h2>
                                    </div><!--// .yui-u -->

                                    <div class="yui-u">


                                        <?php

                                            foreach (tr_posts_field('exp_prof', $id) as $exp_prof):
                                        ?>

                                        <div class="job last">
                                            <h2><?= $exp_prof['nom_entreprise'] ? $exp_prof['nom_entreprise'] : ""; ?></h2>
                                            <h3><?= $exp_prof['date_debut_emploi'] ? $exp_prof['date_debut_emploi'] : ""; ?>-<?= $exp_prof['date_fin_emploi'] ? $exp_prof['date_fin_emploi'] : ""; ?></h3>
<!--                                            <h4></h4>-->
                                            <p>
                                                <?= $exp_prof['description_poste'] ? $exp_prof['description_poste'] : ""; ?>
                                            </p>
                                        </div>

                                        <?php
                                            endforeach;
                                        ?>

                                    </div><!--// .yui-u -->
                                </div><!--// .yui-gf -->


                                <div class="yui-gf last">
                                    <div class="yui-u first">
                                        <h2>Expérience Academique</h2>
                                    </div>
                                    <?php
                                        foreach (tr_posts_field('exp_academic', $id) as $exp_academic):
                                    ?>
                                    <div class="yui-u">
                                        <h2><?= get_term($exp_academic['diplome_academic'])->name ?> - <?= $exp_academic['annee_debut_academic'] ? $exp_academic['annee_debut_academic'] : ""; ?>/<?= $exp_academic['annee_fin_academic'] ? $exp_academic['annee_fin_academic'] : ""; ?></h2>
                                        <h3><?= $exp_academic['ecole_academic'] ? $exp_academic['ecole_academic'] : ""; ?></h3>
                                    </div>
                                    <?php
                                        endforeach;
                                    ?>
                                </div><!--// .yui-gf -->


                            </div><!--// .yui-b -->
                        </div><!--// yui-main -->
                    </div><!--// bd -->

                    <div id="ft">
                        <p>CV en provenance de Jess Assistance SARL &mdash; Contactez-nous pour plus d'information </p>
                    </div><!--// footer -->

                </div><!-- // inner -->


            </div><!--// doc -->
        </main>



        </body>
        </html>
            <?php
            $html = ob_get_clean();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream('cv-'.$id.'.pdf', array("Attachment"=>1, 'compress' => 0));

    }

}