<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 14/03/2018
 * Time: 16:38
 */

$user = _wp_get_current_user();

if($user->ID):
	wp_redirect(home_url('/candidat/alerte'));
endif;


?>

<?php /* Template Name: Page connexion */ ?>

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
		<?php
		if(tr_posts_field('secondtitle')):
		?>

		<div class="uk-width-1-1 uk-section uk-section-small">
			<div class="uk-container">
				<div class="uk-grid-small" uk-grid>

					<div class="uk-width-1-1">

							<div class="uk-card uk-card-body uk-background-muted uk-card-small">
								<h1 class="uk-h2 uk-text-center"><?= tr_posts_field('secondtitle') ?></h1>
							</div>

					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<div class="uk-grid-small" uk-grid>

		<div class="uk-width-1-1 uk-section uk-section-small">
			<div class="uk-container">
				<div class="uk-grid-small" uk-grid uk-height-match="target: > div > .uk-card">

					<div class="uk-width-1-2@l">
							<div class="uk-card uk-card-body uk-card-default">

								<form method="post" action="<?= home_url('/candidat/login') ?>">
									<fieldset class="uk-fieldset">
                                        <?php
                                            wp_nonce_field("form_seed_59cdf94920d34", "_tr_nonce_form");
                                            flash('error_login');
                                            flash('success_register');
                                        ?>

										<legend class="uk-legend uk-text-center uk-h1">Connexion</legend>

										<div class="uk-margin-medium">
											<input class="uk-input" type="text" name="user_login" placeholder="Adresse Email" required>
										</div>
										<div class="uk-margin-medium">
											<input class="uk-input" type="password" placeholder="Mot de passe" name="user_password" required>
										</div>
										<div class="uk-margin-medium uk-text-center">
                                            <input type="hidden" name="offerid" value="<?= $_GET['offerid'] ?>">
											<button type="submit" class="uk-button uk-button-jess">Validez</button>
										</div>

									</fieldset>
								</form>
							</div>
					</div>
					<div class="uk-width-1-2@l">
						<div class="uk-card uk-card-body uk-card-default uk-flex uk-flex-center">

							<fieldset class="uk-fieldset">

								<legend class="uk-legend uk-text-center uk-h1">Inscription</legend>

								<div class="uk-text-center">
									<p class="uk-h2 ">
										Vous n'avez pas de compte ? <br>
										Inscrivez vous
									</p>
                                    <div class="uk-margin-medium-top">

                                    </div>
									<a href="<?= get_the_permalink(tr_posts_field('lien_inscription')) ?>" class="uk-button uk-button-jess-inverse">Inscription</a>
								</div>

                                <div class="uk-margin-medium-top uk-text-justify">
                                    <p>
                                        <?= tr_posts_field('inscription_message') ?>
                                    </p>
                                </div>

							</fieldset>

						</div>
					</div>
				</div>
			</div>
		</div>
        <?php
            if(get_the_content()):
        ?>
        <div class="uk-width-1-1 uk-section uk-section-small">
            <div class="uk-container">
                <div class="uk-grid-small" uk-grid>

                    <div class="uk-width-1-1@l">
                        <div class="uk-card uk-card-body uk-card-default">

                            <div class="uk-h3">
                                <?php the_content() ?>
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

</div>


<?php endwhile; ?>
<?php get_footer() ?>
