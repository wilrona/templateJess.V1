<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 19/03/2018
 * Time: 12:15
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
								<h2>Mes Candidatures</h2>
							</div>
							<div class="uk-card uk-card-body uk-background-default job-list uk-card-small">
                                <div class="uk-overflow-auto">
                                <table class="uk-table uk-table-small uk-table-divider">
                                    <tbody>
									<?php
									if ( $data ) :
										?>
										<?php foreach ($data as $candidature): $current = get_post($candidature['idemploi']); ?>
                                        <tr>
                                            <td class="uk-width-expand content">
                                                <p class="title"><a href="<?php the_permalink($current->ID) ?>" target="_blank"><?= $current->post_title ?></a></p>
                                                <div class="span"><span uk-icon="icon: file-edit" class="uk-margin-small-right"></span><?= get_term(tr_posts_field('type_contrat', $current->ID))->name ?></div>
                                                <div class="span"><span uk-icon="icon: location" class="uk-margin-small-right"></span><?= tr_posts_field('ville', $current->ID) ?></div>
                                                <div class="span"><span uk-icon="icon: calendar" class="uk-margin-small-right"></span><?php sky_date_french('d F Y', get_post_time('U', true, $current->ID), 1); ?></div>
                                                <div class="span"><span uk-icon="icon: hashtag" class="uk-margin-small-right"></span><?= get_term(tr_posts_field('niveau_professionnel', $current->ID))->name; ?></div>
                                            </td>
                                            <td class="uk-width-1-6">
	                                            <?php

	                                            $idcvChoice = null;
	                                            $idcvChoiceMax = false;

                                                $expAnnonce = get_term(tr_posts_field('niveau_professionnel', $current->ID));

                                                $min = tr_taxonomies_field('nbre_min', 'niveau', $expAnnonce->term_id);
                                                $max = tr_taxonomies_field('nbre_max', 'niveau', $expAnnonce->term_id);
                                                if(tr_posts_field('duree_exp_pro', $candidature['idcv']) >= $min && tr_posts_field('duree_exp_pro', $candidature['idcv']) <=  $max ) {
                                                    $idcvChoice = $candidature['idcv'];
                                                }

                                                if(tr_posts_field('duree_exp_pro', $candidature['idcv']) >  $max ) {
                                                    $idcvChoice = $candidature['idcv'];
                                                    $idcvChoiceMax = true;
                                                }

	                                            if($idcvChoice):
		                                            if($idcvChoiceMax):
			                                            ?>
                                                        <span class="uk-text-success uk-text-center uk-display-block">Très bonne expérience</span>
			                                            <?php
		                                            else:
			                                            ?>
                                                        <span class="uk-text-success uk-text-center uk-display-block">Expérience demandée</span>
			                                            <?php
		                                            endif;
		                                            ?>
		                                            <?php

	                                            else:
		                                            ?>
                                                    <span class="uk-text-danger">Aucun de vos CVs n'a assez d'expérience pour cette annonce</span>
		                                            <?php

	                                            endif;
	                                            ?>
                                            </td>
                                        </tr>

									<?php endforeach; ?>

									<?php else: ?>

                                        <tr>
                                            <td rowspan="3" class="uk-width-1-1">
                                                <h2 class="uk-text-center uk-margin-remove">Aucune candidature enregistrée</h2>
                                            </td>
                                        </tr>

									<?php endif; ?>

                                    </tbody>
                                </table>
							    </div>
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