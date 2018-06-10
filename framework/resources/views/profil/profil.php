<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 19/03/2018
 * Time: 12:19
 */

?>

<?php get_header() ?>

<div class="uk-header uk-margin-medium-bottom">
	<div class="uk-grid-small" uk-grid>
		<div class="uk-width-expand@l uk-width-1-1@s uk-left-menu uk-padding-small uk-padding-remove-bottom">
			<div class="uk-bgcolor-2 uk-padding-small uk-flex uk-flex-center uk-hidden@s">
				<div>
					<a href="#" uk-icon="icon: menu;" uk-toggle="target: #offcanvas-overlay"></a>
					<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="">
				</div>
			</div>
			<h1 class="uk-margin-medium-top uk-margin-small-bottom uk-text-jess">Espace Candidat</h1>
		</div>
		<div class="uk-width-auto@l uk-width-1-1@s uk-right-menu uk-position-relative uk-bgcolor-2 uk-flex uk-flex-bottom uk-flex-center">
			<div class="uk-padding-small">
				<?php get_template_part( 'menu-right' ); ?>
			</div>
		</div>
	</div>
</div>

<div class="uk-header" style="min-height: 70vh">

	<div class="uk-grid-small" uk-grid>

		<div class="uk-width-1-1 uk-section uk-section-small">
			<div class="uk-container">
				<div class="" uk-grid>
					<div class="uk-width-2-3@l">

						<div class="uk-card uk-card-body uk-background-muted uk-card-small">
							<h2>Mon profil</h2>
						</div>
						<div class="uk-card uk-card-body uk-background-default job-list uk-card-small">
							<form method="post" action="" id="form_register">
								<fieldset class="uk-fieldset">
									<?php

									wp_nonce_field("form_seed_59cdf94920d34", "_tr_nonce_form");
									flash('error_register');
									flash('success_register');
									//
									$data_redirect = tr_cookie()->getTransient('tr_redirect_data');
									?>

									<div class="uk-margin-medium">
										<input class="uk-input" type="text" name="user_last_name" placeholder="Votre nom" required value="<?= $data_redirect['user_last_name'] ?  $data_redirect['user_last_name'] :  tr_users_field('last_name', $current->ID) ?>">
									</div>
									<div class="uk-margin-medium">
										<input class="uk-input" type="text" name="user_first_name" placeholder="Votre prenom" required value="<?= $data_redirect['user_first_name'] ? $data_redirect['user_first_name'] : tr_users_field('first_name', $current->ID) ?>">
									</div>
									<div class="uk-margin-medium">
										<input class="uk-input datepicker_birth" type="text" name="user_birth" placeholder="Date de naissance" required value="<?= $data_redirect['user_birth'] ? $data_redirect['user_birth'] : tr_users_field('user_birth', $current->ID) ?>" readonly="true">
									</div>

									<div class="uk-margin-medium">
                                        <?php
                                        $villes = get_terms( array(
                                            'taxonomy' => 'ville',
                                            'hide_empty' => false
                                        ) );
                                        ?>
                                        <select name="user_ville" id="" class="uk-select" >
                                            <option value="">Sélectionnez la ville de residence</option>
                                            <?php
                                            foreach ($villes as $type):
                                                ?>
                                                <option value="<?= $type->term_id ?>" <?php if($type->term_id == $data_redirect['user_ville'] || $type->term_id == tr_users_field('user_ville', $current->ID)): ?> selected <?php endif; ?>> <?= $type->name ?></option>
                                            <?php endforeach; ?>

                                        </select>

									</div>

									<div class="uk-margin-medium">
										<select name="user_matrimonial" id="" class="uk-select" required>
											<option value="">Situation matrimoniale</option>
											<?php
											$types = get_terms( array(
												'taxonomy' => 'situation_matrimoniale',
												'hide_empty' => false
											) );

											foreach ($types as $type):
												?>
												<option value="<?= $type->term_id ?>" <?php if($type->term_id == $data_redirect['user_matrimonial'] || $type->term_id == tr_users_field('user_matrimonial', $current->ID)): ?> selected <?php endif; ?>><?= $type->name ?></option>
											<?php endforeach; ?>
										</select>
									</div>

                                    <div class="uk-margin-medium">
                                        <select name="user_sexe" id="" class="uk-select" <?php if(!empty(tr_users_field('user_sexe', $current->ID))): ?> disabled <?php endif; ?>>
                                            <option value="">Selection du sexe</option>
                                            <option value="m" <?php if('m'== $data_redirect['user_sexe'] || 'm' == tr_users_field('user_sexe', $current->ID)): ?> selected <?php endif; ?>>Homme</option>
                                            <option value="f" <?php if('f'== $data_redirect['user_sexe'] || 'f' == tr_users_field('user_sexe', $current->ID)): ?> selected <?php endif; ?>>Femme</option>
                                        </select>
                                        <?php if(!empty(tr_users_field('user_sexe', $current->ID))): ?>
                                            <input type="hidden" name="user_sexe" value="<?= tr_users_field('user_sexe', $current->ID) ?>">
                                        <?php endif; ?>
                                    </div>

									<div class="uk-margin-medium">
										<input class="uk-input" type="number" name="user_enfant" min="0" id="number" placeholder="Nombre d'enfant" required value="<?= $data_redirect['user_enfant'] ? $data_redirect['user_enfant'] : tr_users_field('user_enfant', $current->ID) ?>">
									</div>

									<div class="uk-margin-medium">
										<input class="uk-input" type="email" placeholder="Adresse Email" disabled value="<?= $data_redirect['user_email'] ? $data_redirect['user_email'] : $current->user_email ?>">
										<input type="hidden" name="user_email" value="<?= $data_redirect['user_email'] ? $data_redirect['user_email'] : $current->user_email ?>">
									</div>

									<div class="uk-margin-medium">
										<input class="uk-input" type="text" name="user_phone" placeholder="Numéro de téléphone valide" required value="<?= $data_redirect['user_phone'] ? $data_redirect['user_phone'] : tr_users_field('user_phone', $current->ID) ?>">
									</div>
									<div class="uk-margin-medium uk-text-center">
										<button type="submit" class="uk-button uk-button-jess">Validez</button>
									</div>

								</fieldset>
							</form>
						</div>

					</div>
					<div class="uk-width-1-3@l">
						<a href="<?= home_url('/candidat/curriculum/create') ?>" class="uk-button uk-button-jess-inverse uk-width-1-1 uk-margin">Creer un CV</a>

						<div class="uk-card uk-card-default uk-card-small uk-card-body">
							<ul class="uk-list uk-list-divider">
								<li><a href="<?= home_url('candidat/alerte') ?>">Alerte Emploi</a></li>
								<li><a href="<?= home_url('candidat/candidatures') ?>">Mes candidatures</a></li>
								<li><a href="<?= home_url('candidat/curriculum') ?>">Mes CVs</a></li>
								<li><a href="<?= home_url('candidat/profil') ?>">Mon profil</a></li>
								<li><a href="<?= home_url('candidat/reset') ?>">Changer le mot de passe</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>


<?php get_footer() ?>

