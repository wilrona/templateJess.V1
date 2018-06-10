<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 22/03/2018
 * Time: 11:10
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

                        <?php

                            flash('success');
                        ?>

						<div class="uk-card uk-card-body uk-background-muted uk-card-small">
							<h2>Mes CVs</h2>
						</div>
						<div class="uk-card uk-card-body uk-background-default job-list uk-card-small">
                            <div class="uk-overflow-auto">
                            <table class="uk-table uk-table-small uk-table-divider uk-table-stripped">
                                <tbody>
								<?php

								$currentUser = wp_get_current_user();

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


								if ( $custom_query->have_posts() ) :
									?>
									<?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
                                    <tr>
                                        <td class="uk-width-auto content" style="padding: 15px">
                                            <p class="title"><a href="<?= home_url('/candidat/curriculum/edit?id='.get_the_ID()) ?>"><?php the_title() ?></a></p>
                                            <div class="span">
                                                <ul class="uk-subnav uk-subnav-divider uk-margin-remove-bottom">
                                                    <li><span class="uk-text-jess">Competente : </span></li>
                                                    <?php
                                                        foreach (tr_posts_field('competencecv', get_the_ID()) as $compet):
                                                    ?>
                                                            <li><?= get_term($compet, 'competence')->name ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

								<?php endwhile; ?>

								<?php else: ?>

                                    <tr>
                                        <td rowspan="3" class="uk-width-1-1">
                                            <h3 class="uk-text-center">Aucun cv enregistré</h3>
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
