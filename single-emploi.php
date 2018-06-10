<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 13/03/2018
 * Time: 03:50
 */
?>

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
				<h1 class="uk-margin-medium-top uk-margin-small-bottom uk-text-jess">Offre d'emploi</h1>
			</div>
			<div class="uk-width-auto@l uk-width-1-1@s uk-right-menu uk-position-relative uk-bgcolor-2 uk-flex uk-flex-bottom uk-flex-center">
				<div class="uk-padding-small">
					<?php get_template_part( 'menu-right' ); ?>
				</div>
			</div>
		</div>
	</div>

<div class="uk-header">
	<div class="uk-grid-small uk-flex" uk-grid>
		<div class="uk-width-1-1 uk-section uk-section-small">
			<div class="uk-container">
				<div class="uk-grid-small" uk-grid>
					<div class="uk-width-1-2">
						<a href="<?= home_url('/offre-demploi') ?>" class="uk-button uk-button-jess-inverse">Retour à la liste</a>
					</div>
					<div class="uk-width-1-2 uk-flex uk-flex-right">
						<a href="<?= home_url('/candidat/candidature/create?offerid='.get_the_ID()) ?>" class="uk-button uk-button-jess">Postuler</a>
					</div>
					<div class="uk-width-1-1@l job-list">
                        <?php
                            flash('success_candidature');
                            flash('error_candidature');
                        ?>
						<table class="uk-table uk-table-small uk-table-divider">
							<tbody>
								<tr>
									<td class="uk-width-1-5 logo">
<!--                                        --><?php
//                                            if(get_the_post_thumbnail_url(get_the_ID(), 'full')):
//                                        ?>
<!--										<img src="--><?//= get_the_post_thumbnail_url(get_the_ID(), 'full')?><!--" alt="">-->
<!---->
<!--                                        --><?php
//                                            else:
//                                        ?>

                                                <div class="uk-background-default uk-padding-small uk-margin-right">
                                                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="" class="uk-responsive-height">
                                                </div>



<!--                                        --><?php
//                                        endif;
//                                        ?>
									</td>
									<td class="uk-width-auto content">
										<h1 class="title uk-h2"><?php the_title() ?></h1>
										<div class="span"><span uk-icon="icon: file-edit" class="uk-margin-small-right"></span><?= get_term(tr_posts_field('type_contrat'))->name ?></div>
										<div class="span"><span uk-icon="icon: location" class="uk-margin-small-right"></span><?= get_term(tr_posts_field('ville'))->name ?></div>
										<div class="span"><span uk-icon="icon: calendar" class="uk-margin-small-right"></span><?php sky_date_french('d F Y', get_post_time('U', true), 1); ?></div>
<!--										<div class="span"><span uk-icon="icon: home" class="uk-margin-small-right"></span>--><?//= tr_posts_field('entreprisecontact') ?><!--</div>-->
									</td>
								</tr>

							</tbody>
						</table>
						<div class="uk-card uk-card-body uk-background-muted uk-card-small">
							<h2 class="uk-margin-right uk-h3">No Annonce : <strong><?= tr_posts_field('numero_annonce') ?></strong></h2>


						</div>
						<div class="uk-card uk-card-body uk-card-large uk-background-default job-list">
							<div class="uk-grid-large" uk-grid uk-height-match="">
								<div class="uk-width-1-2@l">
									<div class="uk-header-job">
										Information de l'annonce
									</div>
									<div class="uk-content-job">
										<ul class="uk-list uk-margin-top">
											<li><strong class="uk-h4">Ville</strong> : <?= get_term(tr_posts_field('ville'))->name ?></li>
											<li><strong class="uk-h4">Type de contrat</strong> : <?= get_term(tr_posts_field('type_contrat'))->name ?></li>
											<li><strong class="uk-h4">Catégorie d'emploi</strong> : <?= get_term(tr_posts_field('categorie_emploi'))->name ?></li>
										</ul>
									</div>
								</div>
								<div class="uk-width-1-2@l">
									<div class="uk-header-job">
										Candidature préférée
									</div>
									<div class="uk-content-job">
										<ul class="uk-list uk-margin-top">
											<li><strong class="uk-h4">Niveau professionnel</strong> : <?= get_term(tr_posts_field('niveau_professionnel'))->name ?></li>
											<li><strong class="uk-h4">Diplôme minimum</strong> : <?= get_term(tr_posts_field('diplome'))->name ?></li>
											<li><strong class="uk-h4">Langue</strong> : <?php foreach (tr_posts_field('langue_offre') as $comp): echo get_term($comp)->name.','; endforeach;?></li>
<!--											<li><strong class="uk-h4">Compétence</strong> : --><?php //foreach (tr_posts_field('competence') as $comp): echo get_term($comp)->name.','; endforeach;?><!--</li>-->
										</ul>
									</div>
								</div>
								<div class="uk-width-1-1@l uk-margin-small-top">
									<div class="uk-header-job">
										Compétence professionnelle
									</div>
									<div class="uk-content-job">
										<ul class="uk-list uk-margin-top">
											<li><?php foreach (tr_posts_field('competence') as $comp): echo get_term($comp)->name.' - '; endforeach;?></li>
										</ul>
									</div>
								</div>
                                <?php
                                    if(get_the_content()):
                                ?>
								<div class="uk-width-1-1@l uk-margin-small-top">
									<div class="uk-header-job">
										Description
									</div>
									<div class="uk-content-job">
										<div class="uk-margin-top">
											<?php the_content(); ?>
										</div>
									</div>
								</div>
                                <?php
                                 endif;
                                ?>

								<div class="uk-width-3-5@l uk-margin-large-top uk-text-left">
									<a href="<?= home_url('/candidat/candidature/create?offerid='.get_the_ID()) ?>" class="uk-button uk-button-jess">Postuler à cette offre</a>
									<p>
										Même si votre profil ne correspond pas à votre mission de l’offre, déposez tout de même votre CV pour recevoir les offres correspondant à votre profil

									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>




<?php endwhile; ?>
<?php get_footer() ?>
