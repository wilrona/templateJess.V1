<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 13/03/2018
 * Time: 12:44
 */

?>

<?php /* Template Name: Page Recruteur */ ?>

<?php get_header() ?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="uk-header uk-margin-medium-bottom">
		<div class="uk-grid-small" uk-grid>
			<div class="uk-width-expand@l uk-width-1-1@s uk-left-menu uk-padding-small uk-padding-remove-bottom">
				<div class="uk-bgcolor-2 uk-padding-small uk-flex uk-flex-center uk-hidden@s">
					<div>
						<a href="#" uk-icon="icon: menu;" uk-toggle="target: #offcanvas-overlay"></a>
						<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="">
					</div>
				</div>
				<h1 class="uk-margin-medium-top uk-margin-small-bottom uk-text-jess"><?php the_title() ?></h1>
			</div>
			<div class="uk-width-auto@l uk-width-1-1@s uk-right-menu uk-position-relative uk-bgcolor-2 uk-flex uk-flex-bottom uk-flex-center">
				<div class="uk-padding-small">
					<?php get_template_part( 'menu-right' ); ?>
				</div>
			</div>
		</div>
	</div>


	<div class="uk-header">

		<div class="uk-grid-small" uk-grid>

			<div class="uk-width-1-1 uk-section uk-section-small">
				<div class="uk-container">
					<div class="uk-grid-small" uk-grid>

						<div class="uk-width-1-1">
							<?php
								if(tr_posts_field('secondtitle')):
							?>
							<div class="uk-card uk-card-body uk-background-muted uk-card-small">
								<h1 class="uk-h2 uk-text-center"><?= tr_posts_field('secondtitle') ?></h1>
							</div>
							<?php endif; ?>
							<?php
								if(get_the_content()):
							?>
							<div class="uk-card uk-card-body uk-background-default job-list uk-card-small">
								<?php the_content(); ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
        </div>
			<form action="<?= home_url('/annonce/send') ?>" method="post" id="formulaire_annonce" style="padding: 0 !important;">

				<?php

				wp_nonce_field("form_seed_59cdf94920d34", "_tr_nonce_form");

				$data_redirect = tr_cookie()->getTransient('tr_redirect_data');

				?>


        <div class="uk-grid-small" uk-grid>
			<div class="uk-width-1-1 uk-section uk-section-small">
				<div class="uk-container">
                    <?php
                        flash('titre_annonce');
                        flash('ville_emploi' );
                        flash('date_fin_annonce');
                        flash('type_contrat');
                        flash('categorie_demploi');
                        flash('niveau_professionnel');
                        flash('diplome');
                        flash('langue');
                        flash('competence');
                        flash('description');
                        flash('nom_de_contact');
                        flash('nom_entreprise');
                        flash('email_de_contact');
                        flash('telephone_contact');
                        flash('success');
                    ?>
					<div class="uk-grid-small" uk-grid>
						<div class="uk-width-1-1">
							<div class="uk-card uk-card-body uk-background-default uk-card-small">
									<div class="uk-grid-large" uk-grid>

										<div class="uk-width-1-2@l">
											<div class="uk-header-job">
												Information de l'annonce
											</div>
											<div class="uk-content-job">
												<ul class="uk-list uk-margin-top uk-list-divider">
                                                    <li>
                                                        <input type="text" name="titre_annonce" class="uk-input uk-input-search" placeholder="Titre de l'annonce" value="<?= $data_redirect['titre_annonce'] ?>">
                                                    </li>
													<li>
														<?php
														$villes = get_terms( array(
															'taxonomy' => 'ville',
															'hide_empty' => false
														) );
														?>
                                                        <select name="ville_emploi" id="" class="uk-select uk-input-search" >
                                                            <option value="">Sélectionnez la ville</option>
															<?php
															foreach ($villes as $type):
																?>
                                                                <option value="<?= $type->term_id ?>" <?php if($data_redirect['ville_emploi']): ?> selected <?php endif; ?>> <?= $type->name ?></option>
															<?php endforeach; ?>

                                                        </select>
                                                    </li>
                                                    <li>
                                                        <input type="text" name="date_fin_annonce" class="uk-input uk-input-search datepicker" placeholder="dd/mm/yyyy" readonly='true' value="<?= $data_redirect['date_fin_annonce'] ?>">
                                                    </li>
													<li>
														<?php
															$types = get_terms( array(
																'taxonomy' => 'type_contrat',
																'hide_empty' => false
															) );
														?>
														<select name="type_contrat" id="" class="uk-select uk-input-search" >
															<option value="">Sélectionnez le type de contrat</option>
															<?php
																foreach ($types as $type):
															?>
															<option value="<?= $type->term_id ?>" <?php if($data_redirect['type_contrat']): ?> selected <?php endif; ?>> <?= $type->name ?></option>
															<?php endforeach; ?>

														</select>
													</li>

												</ul>
											</div>
										</div>
										<div class="uk-width-1-2@l">
											<div class="uk-header-job">
												Candidature préférée
											</div>
											<div class="uk-content-job">
												<ul class="uk-list uk-margin-top uk-list-divider">
                                                    <li>
														<?php
														$terms = get_terms( array(
															'taxonomy' => 'categorie_demploi',
															'hide_empty' => false,
															'parent' => 0
														));
														?>
                                                        <select name="categorie_demploi" id="" class="uk-select uk-input-search" >
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

                                                                        <option value="<?= $child->term_id ?>" <?php if($data_redirect['categorie_demploi']): ?> selected <?php endif; ?>> <?= $child->name ?></option>

																		<?php
																	endforeach;
																	?>

                                                                </optgroup>

															<?php endforeach; ?>


                                                        </select>
                                                    </li>
													<li>
														<?php
															$types = get_terms( array(
																'taxonomy' => 'niveau_professionnel',
																'hide_empty' => false
															) );
														?>
														<select name="niveau_professionnel" id="" class="uk-select uk-input-search" >
															<option value="">Sélectionnez un niveau professionnel</option>
															<?php
															foreach ($types as $type):
																?>
																<option value="<?= $type->term_id ?>" <?php if($data_redirect['niveau_professionnel']): ?> selected <?php endif; ?>> <?= $type->name ?></option>
															<?php endforeach; ?>

														</select>
													</li>
													<li>
														<?php
														$types = get_terms( array(
															'taxonomy' => 'diplome',
															'hide_empty' => false
														) );
														?>
														<select name="diplome" id="" class="uk-select uk-input-search" >
															<option value="">Sélectionnez le diplome minimum du candidat</option>
															<?php
															foreach ($types as $type):
																?>
																<option value="<?= $type->term_id ?>" <?php if($data_redirect['diplome']): ?> selected <?php endif; ?>> <?= $type->name ?></option>
															<?php endforeach; ?>

														</select>

													</li>
													<li>

                                                        <input type="hidden" name="langue" value="" />

														<?php
														$types = get_terms( array(
															'taxonomy' => 'langue',
															'hide_empty' => false
														) );
														?>
														<select name="langue[]" id="" class="uk-select uk-input-search selected" multiple data-placeholder="Sélectionnez des langues" >
															<option value="">Sélectionnez des langues</option>
															<?php
															foreach ($types as $type):
																?>
																<option value="<?= $type->term_id ?>" <?php if(in_array($type->term_id, $data_redirect['langue'])): ?> selected <?php endif; ?>> <?= $type->name ?></option>
															<?php endforeach; ?>

														</select>

													</li>

												</ul>
											</div>
										</div>
										<div class="uk-width-1-1@l uk-margin-small-top">
											<div class="uk-header-job">
												Domaines de competénce
											</div>
											<div class="uk-content-job">
												<ul class="uk-list uk-margin-top">
													<li>
                                                        <input type="hidden" name="competence" value="" />
														<?php
														$types = get_terms( array(
															'taxonomy' => 'competence',
															'hide_empty' => false
														) );
														?>
														<select name="competence[]" id="" class="uk-select uk-input-search selected" multiple data-placeholder="Sélectionnez des domaines de competénce" >
															<option value="">Sélectionnez des domaines de competénce</option>
															<?php
															foreach ($types as $type):
																?>
																<option value="<?= $type->term_id ?>" <?php if(in_array($type->term_id, $data_redirect['competence'])): ?> selected <?php endif; ?>> <?= $type->name ?></option>
															<?php endforeach; ?>

														</select>
													</li>
												</ul>
											</div>
										</div>
										<div class="uk-width-1-1@l uk-margin-small-top">
											<div class="uk-header-job">
												Description
											</div>
											<div class="uk-content-job">
												<div class="uk-margin-top">
													<?php
													$content = $data_redirect['description'] ? $data_redirect['description'] : '';
													$editor_id = 'description';
													$settings = array( 'media_buttons' => false );


													wp_editor( $content, $editor_id, $settings );
													?>

												</div>
											</div>
										</div>
									</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="uk-width-1-1 uk-section uk-section-small">
				<div class="uk-container">
					<div class="uk-grid-small" uk-grid>
						<div class="uk-width-1-1">
							<div class="uk-card uk-card-body uk-background-default uk-card-small">
								<div class="uk-grid-large" uk-grid>
									<div class="uk-width-1-1@l">
										<div class="uk-header-job">Contact de l'entreprise</div>
										<div class="uk-content-job">
											<div class="uk-grid-small uk-child-width-1-2@l" uk-grid>
												<div>
													<ul class="uk-list uk-margin-top uk-list-divider">
														<li>
                                                            <input type="text"  name="nom_de_contact" class="uk-input uk-input-search" placeholder="Nom du contact" value="<?= $data_redirect['nom_de_contact'] ?>">
                                                        </li>
														<li><input type="text"  name="nom_entreprise" class="uk-input uk-input-search" placeholder="Nom de l'entreprise" value="<?= $data_redirect['nom_entreprise'] ?>"></li>
													</ul>
												</div>
												<div>
													<ul class="uk-list uk-margin-top uk-list-divider">
														<li><input type="email"  name="email_de_contact" class="uk-input uk-input-search" placeholder="Email du contact" value="<?= $data_redirect['email_de_contact'] ?>"></li>
														<li><input type="text"  name="telephone_contact" class="uk-input uk-input-search" placeholder="Numéro téléphone du contact" value="<?= $data_redirect['telephone_contact'] ?>"></li>
													</ul>
												</div>
												<div class="uk-margin-large-top">
													<button type="submit" class="uk-button uk-button-jess">Déposez votre annonce</button>
												</div>
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			</form>
        </div>

	<?php
	$sliderpartenaire = tr_options_field('pc_options.partenaireslide');

	if($sliderpartenaire):
		?>
        <div class="uk-width-1-1 uk-section uk-section-small">
            <div class="uk-container">
                <div class="uk-padding uk-background-default">
                    <div class="uk-child-width-1-2@l" uk-grid>
                        <div class="uk-flex-last@l">
                            <div class="owl-carousel owl-partenaire owl-theme">
								<?php foreach ($sliderpartenaire as $item): ?>
                                    <div class="item">
                                        <img src="<?php echo wp_get_attachment_image_src($item['partenaireslider'], 'small')[0] ?>" alt="" class="uk-height-small">
                                    </div>
									<?php
								endforeach;
								?>
                            </div>
                        </div>
                        <div class="uk-flex uk-flex-middle uk-flex-first@l">
                            <div>
                                <span class="uk-display-block uk-h1 uk-margin-medium-bottom">Ils nous font confiance et pourquoi pas vous ?</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

		<?php
	endif;
	?>

	</div>





<?php endwhile; ?>
<?php get_footer() ?>
