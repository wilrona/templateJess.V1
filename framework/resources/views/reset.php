<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 15/03/2018
 * Time: 02:41
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
            <h1 class="uk-margin-medium-top uk-margin-small-bottom uk-text-jess">Mot de passe perdu</h1>
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
			<div class="uk-container ">
				<div class="uk-grid-small uk-flex uk-flex-center" uk-grid>
					<div class="uk-width-1-2@l">
						<div class="uk-card uk-card-body uk-card-default uk-card-small">
							<?php
								flash('error_email');
								flash('success_send');
								flash('error_send');
							?>
							<form method="post">
								<?php
								wp_nonce_field("form_seed_59cdf94920d34", "_tr_nonce_form");
								?>
								<fieldset class="uk-fieldset">
									<div class="uk-h3 uk-margin-medium-bottom">Entrez votre adresse email. Vous allez recevoir votre nouveau mot de passe par email.</div>
									<p>
										<label for="user_login">Adresse E-mail:</label>
										<?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
										<input type="text" name="user_login" class="uk-input" id="user_login" value="<?php echo $user_login; ?>" />
									</p>
									<p>
										<input type="hidden" name="action" value="reset" />
										<input type="submit" value="Recevoir ton nouveau mot de passe" class="uk-button uk-button-jess" id="submit" />
									</p>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>


<?php get_footer() ?>



