<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 19/03/2018
 * Time: 14:33
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
                        <div class="uk-width-1-1 uk-margin">
                            <a href="<?= home_url('/candidat/curriculum') ?>" class="uk-button uk-button-jess-inverse">Retour à la liste des CVs</a>
                        </div>

                        <?php
                            flash('titre_du_cv');
                            flash('categorie_emploi');
                            flash('competence');
                            flash('langue');
                            flash('message');
                            flash('file');
                        ?>

						<div class="uk-card uk-card-body uk-background-muted uk-card-small">
							<h2>Modifier le CV</h2>
						</div>
						<div class="uk-card uk-card-body uk-background-default uk-card-small">
							<form method="post" action="" id="form_cv">
								<fieldset class="uk-fieldset">
									<?php

									wp_nonce_field("form_seed_59cdf94920d34", "_tr_nonce_form");
									//
									$data_redirect = tr_cookie()->getTransient('tr_redirect_data');
									?>

									<div class="uk-margin-medium">
										<input class="uk-input uk-input uk-input-search" type="text" name="titre_du_cv" placeholder="Titre du CV" value="<?= $data_redirect['titre_du_cv'] ? $data_redirect['titre_du_cv'] : $current->post_title; ?>">
										<hr>
									</div>
									<div class="uk-margin-medium">
                                        <label class="uk-h4" for="form-stacked-text">Description</label>
                                        <div class="uk-form-controls">
										<?php
											$content = $data_redirect['description'] ? $data_redirect['description'] : $current->post_content;
											$editor_id = 'description';
											$settings = array( 'media_buttons' => false );


											wp_editor( $content, $editor_id, $settings );
										?>
									    </div>
									</div>
									<div class="uk-margin-medium">
										<?php
										$terms = get_terms( array(
											'taxonomy' => 'categorie_demploi',
											'hide_empty' => false,
											'parent' => 0
										));
										?>
										<select name="categorie_emploi" id="categorie_demploi" class="uk-select uk-input-search" >
											<option value="">Sélectionnez la catégorie d'emploi</option>
											<?php foreach ($terms as $term): ?>
												<?php
												$childs = get_terms( array(
													'taxonomy' => 'categorie_demploi',
													'hide_empty' => false,
													'parent' => $term->term_id
												));
												?>
												<optgroup label="<?= $term->name ?>">
													<?php
													foreach ($childs as $child):
														?>

														<option value="<?= $child->term_id ?>" <?php if(intval($data_redirect['categorie_emploi']) == intval($child->term_id)  || intval(tr_posts_field('cate_emploi_cv', $current->ID)) == intval($child->term_id)): ?> selected <?php endif; ?>> <?= $child->name ?></option>

														<?php
													endforeach;
													?>

												</optgroup>

											<?php endforeach; ?>
										</select>
										<hr>
									</div>



									<div class="uk-margin-medium uk-width-1-1">

                                        <input type="hidden" name="competence" value="" />
										<?php
										$types = get_terms( array(
											'taxonomy' => 'competence',
											'hide_empty' => false
										) );
										?>
										<select name="competence[]" id="competence" class="uk-select uk-input-search selectedComp" multiple data-placeholder="Sélectionnez des compétences" >
											<option value="">Sélectionnez des compétences</option>
											<?php
											foreach ($types as $type):
												?>
												<option value="<?= $type->term_id ?>" <?php if(in_array($type->term_id, $data_redirect['competence']) || in_array($type->term_id, tr_posts_field('competencecv', $current->ID))): ?> selected <?php endif; ?>> <?= $type->name ?></option>
											<?php endforeach; ?>

										</select>
										<hr>
									</div>

									<div class="uk-margin-medium">
                                        <input type="hidden" name="langue" value="" />
										<?php
										$types = get_terms( array(
											'taxonomy' => 'langue',
											'hide_empty' => false
										) );
										?>
										<select name="langue[]" id="langue" class="uk-select uk-input-search selected" multiple data-placeholder="Sélectionnez des langues" >
											<option value="">Sélectionnez des langues</option>
											<?php
											foreach ($types as $type):
												?>
												<option value="<?= $type->term_id ?>" <?php if(in_array($type->term_id, $data_redirect['langue']) || in_array($type->term_id, tr_posts_field('langue_cv', $current->ID))): ?> selected <?php endif; ?>> <?= $type->name ?></option>
											<?php endforeach; ?>

										</select>
										<hr>
									</div>

									<div class="uk-margin-medium repeater">
                                        <label class="uk-h3 uk-display-block uk-position-relative" for="form-stacked-text">Education <small style="font-size: 11px" class="uk-text-danger">( Une éducation obligatoire )</small>
                                            <button type="button" class="uk-button uk-button-jess uk-button-small uk-position-center-right" data-repeater-create>Ajouter</button>

                                        </label>
                                        <hr>

                                        <div data-repeater-list="experience_academic">

                                            <?php
                                                if($data_redirect['experience_academic']):

                                                    foreach ($data_redirect['experience_academic'] as $experience_academic):

                                            ?>
                                                        <div data-repeater-item>
                                                            <table class="uk-table uk-table-small uk-table-divider">
                                                                <tr>
                                                                    <td class="uk-width-expand uk-background-muted uk-height-1-1">
                                                                        <span class="uk-sortable-handle uk-margin-small-right" uk-icon="icon: table"></span>
                                                                        <button data-repeater-delete type="button" uk-icon="icon: close"></button>
                                                                    </td>
                                                                    <td class="uk-width-auto">
                                                                        <div class="uk-form-controls">
						                                                    <?php
						                                                    $types = get_terms( array(
							                                                    'taxonomy' => 'diplome',
							                                                    'hide_empty' => false
						                                                    ) );
						                                                    ?>
                                                                            <select name="diplome_academic" id="" class="uk-select uk-input-search" >
                                                                                <option value="">Sélectionnez un diplome</option>
							                                                    <?php
							                                                    foreach ($types as $type):
								                                                    ?>
                                                                                    <option value="<?= $type->term_id ?>" <?php if(intval($experience_academic['diplome_academic']) == intval($type->term_id)): ?> selected <?php endif; ?>> <?= $type->name ?></option>
							                                                    <?php endforeach; ?>

                                                                            </select>
                                                                            <hr>
                                                                        </div>
                                                                        <div class="uk-form-controls">
                                                                            <input type="text" name="ecole_academic" class="uk-input uk-input-search" placeholder="Nom ecole" value="<?= $experience_academic['ecole_academic'] ? $experience_academic['ecole_academic'] : ""; ?>">
                                                                            <hr>
                                                                        </div>
                                                                        <div class="uk-form-controls">
                                                                            <input type="text" name="annee_debut_academic" class="uk-input uk-input-search datepicker_year_start" placeholder="Année début" readonly="true" value="<?= $experience_academic['annee_debut_academic'] ? $experience_academic['annee_debut_academic'] : ""; ?>">
                                                                            <hr>
                                                                        </div>
                                                                        <div class="uk-form-controls">
                                                                            <input type="text" name="annee_fin_academic" class="uk-input uk-input-search datepicker_year_end" placeholder="Annéé fin" readonly="true" value="<?= $experience_academic['annee_fin_academic'] ? $experience_academic['annee_fin_academic'] : ""; ?>">
                                                                            <hr>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>



                                            <?php

                                                    endforeach;
                                                else:
                                                    foreach (tr_posts_field('exp_academic', $current->ID) as $exp_academic):
                                            ?>
                                            <div data-repeater-item>
                                                <table class="uk-table uk-table-small uk-table-divider">
                                                    <tr>
                                                        <td class="uk-width-expand uk-background-muted uk-height-1-1">
                                                            <span class="uk-sortable-handle uk-margin-small-right" uk-icon="icon: table"></span>
                                                            <button data-repeater-delete type="button" uk-icon="icon: close"></button>
                                                        </td>
                                                        <td class="uk-width-auto">
                                                            <div class="uk-form-controls">
	                                                            <?php
	                                                            $types = get_terms( array(
		                                                            'taxonomy' => 'diplome',
		                                                            'hide_empty' => false
	                                                            ) );
	                                                            ?>
                                                                <select name="diplome_academic" id="" class="uk-select uk-input-search" >
                                                                    <option value="">Sélectionnez un diplome</option>
		                                                            <?php
		                                                            foreach ($types as $type):
			                                                            ?>
                                                                        <option value="<?= $type->term_id ?>" <?php if(intval($exp_academic['diplome_academic']) == intval($type->term_id)): ?> selected <?php endif; ?>> <?= $type->name ?></option>
		                                                            <?php endforeach; ?>

                                                                </select>
                                                                <hr>
                                                            </div>
                                                            <div class="uk-form-controls">
                                                                <input type="text" name="ecole_academic" class="uk-input uk-input-search" placeholder="Nom ecole" value="<?= $exp_academic['ecole_academic'] ? $exp_academic['ecole_academic'] : ""; ?>">
                                                                <hr>
                                                            </div>
                                                            <div class="uk-form-controls">
                                                                <input type="text" name="annee_debut_academic" class="uk-input uk-input-search datepicker_year_start" placeholder="Année début" readonly="true" value="<?= $exp_academic['annee_debut_academic'] ? $exp_academic['annee_debut_academic'] : ""; ?>">
                                                                <hr>
                                                            </div>
                                                            <div class="uk-form-controls">
                                                                <input type="text" name="annee_fin_academic" class="uk-input uk-input-search datepicker_year_end" placeholder="Annéé fin" readonly="true" value="<?= $exp_academic['annee_fin_academic'] ? $exp_academic['annee_fin_academic'] : ""; ?>">
                                                                <hr>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>


									</div>
                                    <div class="uk-margin-medium repeater">
                                        <label class="uk-h3 uk-display-block uk-position-relative" for="form-stacked-text">Expérience professionnelle <small style="font-size: 11px" class="uk-text-danger">( Une expérience obligatoire )</small>
                                            <button type="button" class="uk-button uk-button-jess uk-button-small uk-position-center-right" data-repeater-create>Ajouter</button>

                                        </label>
                                        <hr>

                                        <div data-repeater-list="experience_professionnel">
	                                        <?php
	                                        if($data_redirect['experience_professionnel']):

	                                            foreach ($data_redirect['experience_professionnel'] as $experience_professionnel):

	                                        ?>
                                                    <div data-repeater-item>
                                                        <table class="uk-table uk-table-small uk-table-divider">
                                                            <tr>
                                                                <td class="uk-width-expand uk-background-muted uk-height-1-1">
                                                                    <span class="uk-sortable-handle uk-margin-small-right" uk-icon="icon: table"></span>
                                                                    <button data-repeater-delete type="button" uk-icon="icon: close"></button>
                                                                </td>
                                                                <td class="uk-width-auto">
                                                                    <div class="uk-form-controls">
                                                                        <input type="text" name="nom_entreprise" class="uk-input uk-input-search" placeholder="Nom de l'entreprise" value="<?= $experience_professionnel['nom_entreprise'] ? $experience_professionnel['nom_entreprise'] : ""; ?>"/>
                                                                        <hr>
                                                                    </div>
                                                                    <div class="uk-form-controls">
                                                                        <textarea name="description_poste" class="uk-textarea uk-input-search" style="height: auto !important;" cols="30" rows="5" placeholder="Description du poste"><?= $experience_professionnel['description_poste'] ? $experience_professionnel['description_poste'] : ""; ?></textarea>
                                                                        <hr>
                                                                    </div>
                                                                    <div class="uk-form-controls">
                                                                        <input type="text" name="date_debut_emploi" class="uk-input uk-input-search datepicker_start" placeholder="Date debut : dd/mm/yyyy" readonly="true" value="<?= $experience_professionnel['date_debut_emploi'] ? $experience_professionnel['date_debut_emploi'] : ""; ?>">
                                                                        <hr>
                                                                    </div>
                                                                    <div class="uk-form-controls">
                                                                        <input type="text" name="date_fin_emploi" class="uk-input uk-input-search datepicker_end" placeholder="Date fin : dd/mm/yyyy" readonly="true" value="<?= $experience_professionnel['date_fin_emploi'] ? $experience_professionnel['date_fin_emploi'] : ""; ?>">
                                                                        <hr>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                            <?php

	                                            endforeach;
	                                        else:
	                                            foreach (tr_posts_field('exp_prof', $current->ID) as $exp_prof):
	                                        ?>
                                                <div data-repeater-item>
                                                    <table class="uk-table uk-table-small uk-table-divider">
                                                        <tr>
                                                            <td class="uk-width-expand uk-background-muted uk-height-1-1">
                                                                <span class="uk-sortable-handle uk-margin-small-right" uk-icon="icon: table"></span>
                                                                <button data-repeater-delete type="button" uk-icon="icon: close"></button>
                                                            </td>
                                                            <td class="uk-width-auto">
                                                                <div class="uk-form-controls">
                                                                    <input type="text" name="nom_entreprise" class="uk-input uk-input-search" placeholder="Nom de l'entreprise" value="<?= $exp_prof['nom_entreprise'] ? $exp_prof['nom_entreprise'] : ""; ?>"/>
                                                                    <hr>
                                                                </div>
                                                                <div class="uk-form-controls">
                                                                    <textarea name="description_poste" class="uk-textarea uk-input-search" style="height: auto !important;" cols="30" rows="5" placeholder="Description du poste"> <?= $exp_prof['description_poste'] ? $exp_prof['description_poste'] : ""; ?></textarea>
                                                                    <hr>
                                                                </div>
                                                                <div class="uk-form-controls">
                                                                    <input type="text" name="date_debut_emploi" class="uk-input uk-input-search datepicker_start" placeholder="Date debut : dd/mm/yyyy" readonly="true" value="<?= $exp_prof['date_debut_emploi'] ? $exp_prof['date_debut_emploi'] : ""; ?>">
                                                                    <hr>
                                                                </div>
                                                                <div class="uk-form-controls">
                                                                    <input type="text" name="date_fin_emploi" class="uk-input uk-input-search datepicker_end" placeholder="Date fin : dd/mm/yyyy" readonly="true" value="<?= $exp_prof['date_fin_emploi'] ? $exp_prof['date_fin_emploi'] : ""; ?>">
                                                                    <hr>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
	                                        <?php  endforeach; ?>
	                                        <?php endif; ?>
                                        </div>

                                    </div>
                                    <div class="uk-margin-medium">
                                        <label class="uk-h3 uk-display-block uk-position-relative" for="form-stacked-text">Completez avec votre CV en pdf <small style="font-size: 11px" class="uk-text-danger">( Obligatoire )</small></label>
                                        <hr>
                                        <?php
                                            if(tr_posts_field('file_url', $current->ID)):
                                        ?>
                                                <a target="_blank" href="<?= tr_posts_field('file_url', $current->ID) ?>" class="uk-button uk-button-primary uk-margin">Un cv a été associé. Cliquez pour télécharger.</a>
                                        <?php
                                            endif;
                                        ?>
                                        <div uk-form-custom="target: true" class="uk-width-1-1">
                                            <input type="file" name="file_cv">
                                            <input class="uk-input uk-width-1-1 uk-input-search" type="text" placeholder="Ajouter votre CV" disabled>
                                        </div>
                                    </div>
									<div class="uk-margin-medium uk-text-center">
										<button type="submit" class="uk-button uk-button-jess">Validez les modifications</button>
									</div>

								</fieldset>
							</form>
						</div>

					</div>
					<div class="uk-width-1-3@l">
						<a href="<?= home_url('/candidat/curriculum/create') ?>" class="uk-button uk-button-jess-inverse uk-width-1-1 uk-margin">Creer un CV maintenant</a>

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
