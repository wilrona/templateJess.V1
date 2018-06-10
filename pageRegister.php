<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 15/03/2018
 * Time: 03:38
 */

$user = _wp_get_current_user();

if($user->ID):
	wp_redirect(home_url('/candidat/alerte'));
endif;
?>

<?php /* Template Name: Page inscription */ ?>

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
                    <div class="uk-grid-small uk-flex uk-flex-center" uk-grid uk-height-match="target: > div > .uk-card">

                        <div class="uk-width-1-2@l">
                            <div class="uk-card uk-card-body uk-card-default">

                                <form method="post" action="<?= home_url('/candidat/register') ?>" id="form_register">
                                    <fieldset class="uk-fieldset">
										<?php
										wp_nonce_field("form_seed_59cdf94920d34", "_tr_nonce_form");
										flash('error_register');

										$data_redirect = tr_cookie()->getTransient('tr_redirect_data');
										?>

                                        <legend class="uk-legend uk-text-center uk-h1">Inscrivez-vous</legend>

                                        <div class="uk-margin-medium">
                                            <input class="uk-input" type="text" name="user_last_name" placeholder="Votre nom" required value="<?= $data_redirect['user_last_name'] ?>">
                                        </div>
                                        <div class="uk-margin-medium">
                                            <input class="uk-input" type="text" name="user_first_name" placeholder="Votre prenom" required value="<?= $data_redirect['user_first_name'] ?>">
                                        </div>
                                        <div class="uk-margin-medium">
                                            <input class="uk-input datepicker_birth" type="text" name="user_birth" placeholder="Date de naissance" required value="<?= $data_redirect['user_birth'] ?>" readonly="true">
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
                                                    <option value="<?= $type->term_id ?>" <?php if($data_redirect['user_ville'] === $type->term_id): ?> selected <?php endif; ?>> <?= $type->name ?></option>
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
                                                    <option value="<?= $type->term_id ?>" <?php if($type->term_id == $data_redirect['user_matrimonial']): ?> selected <?php endif; ?>><?= $type->name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="uk-margin-medium">
                                            <select name="user_sexe" id="" class="uk-select" required>
                                                <option value="">Selection du sexe</option>
                                                <option value="m" <?php if('m'== $data_redirect['user_sexe']): ?> selected <?php endif; ?>>Homme</option>
                                                <option value="f" <?php if('f'== $data_redirect['user_sexe']): ?> selected <?php endif; ?>>Femme</option>
                                            </select>
                                        </div>


                                        <div class="uk-margin-medium">
                                            <input class="uk-input" type="number" name="user_enfant" min="0" id="number" placeholder="Nombre d'enfant" required value="<?= $data_redirect['user_enfant'] ?>">
                                        </div>

                                        <div class="uk-margin-medium">
                                            <input class="uk-input" type="email" name="user_email" placeholder="Adresse Email" required value="<?= $data_redirect['user_email'] ?>">
                                        </div>

                                        <div class="uk-margin-medium">
                                            <input class="uk-input" type="text" name="user_phone" placeholder="Numéro de téléphone valide" required value="<?= $data_redirect['user_phone'] ?>">
                                        </div>

<!--                                        <div class="uk-margin-medium">-->
<!--                                            <input class="uk-input" type="text" name="user_login" placeholder="Login ou Identifiant" required value="--><?//= $data_redirect['user_login'] ?><!--">-->
<!--                                        </div>-->
                                        <div class="uk-margin-medium">
                                            <input class="uk-input" type="password" placeholder="Mot de passe" name="user_pass" required>
                                        </div>
                                        <div class="uk-margin-medium">
                                            <input class="uk-input" type="password" placeholder="Confirmation de mot de passe" name="user_pass_2" required>
                                        </div>
                                        <div class="uk-margin-medium uk-text-center">
                                            <button type="submit" class="uk-button uk-button-jess">Validez</button>
                                        </div>

                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <?php
                            if(get_the_content()):
                        ?>
                        <div class="uk-width-1-2@l">
                            <div class="uk-card uk-card-body uk-card-default">
                                <div class="uk-h4">
	                                <?php the_content() ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>

<?php endwhile; ?>
<?php get_footer() ?>
