<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 06/03/2018
 * Time: 00:01
 */

?>

<?php /* Template Name: Page Contact */ ?>

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


			<div class="uk-grid-small uk-flex" uk-grid>

				<div class="uk-width-1-1">
					<div class="uk-container">
						<div class="uk-grid-collapse uk-child-width-1-2@l" uk-grid>
							<div>
								<?php the_content(); ?>
							</div>
							<div class="uk-background-default-jess uk-padding uk-flex-middle uk-flex-center uk-flex">
								<div class="uk-text-jess-inverse">
                                    <ul class="uk-list">
                                        <li><span uk-icon="icon: home" class="uk-margin-small-right"></span><?= tr_posts_field('nomcompany') ?></li>
                                        <li><span uk-icon="icon: location" class="uk-margin-small-right"></span><?= tr_posts_field('adressecompany') ?></li>
                                        <li><span uk-icon="icon: receiver" class="uk-margin-small-right"></span><?= tr_posts_field('phonecompany') ?></li>
                                        <li><span uk-icon="icon: mail" class="uk-margin-small-right"></span><?= tr_posts_field('emailcompany') ?></li>
                                    </ul>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
					$agences = tr_posts_field('agences');

					if($agences):
				?>
				<div class="uk-width-1-1 uk-section uk-section-small">
					<div class="uk-container">
						<div class="uk-padding uk-background-default">
							<div class="uk-grid-small" uk-grid>
								<div class="uk-text-center uk-margin-small-bottom">
									<h2 class="uk-h3 uk-text-jess uk-text-uppercase">Nos agences</h2>
								</div>
								<div class="uk-width-3-3">
									<div class="uk-grid-small uk-child-width-1-3@l" uk-grid uk-height-match=".uk-card">
										<?php foreach ($agences as $agence): ?>
										<div>
											<ul uk-accordion>
												<li>
													<a class="uk-accordion-title" href="#"><?= $agence['texteagences'] ?></a>
													<div class="uk-accordion-content uk-margin-remove">
														<div class="uk-card uk-card-default uk-card-small">
                                                            <?php
                                                                if($agence['imgagences']):
                                                            ?>
															<div class="uk-card-media-top uk-cover-container">
																<img src="<?php echo wp_get_attachment_image_src($agence['imgagences'], 'full')[0] ?>" alt="" uk-cover>
																<canvas width="400" height="200"></canvas>

															</div>
                                                            <?php
                                                                endif;
                                                            ?>
															<div class="uk-card-body">
																<div><?= $agence['adresseagences']; ?></div>
															</div>
														</div>
													</div>
												</li>
											</ul>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<div class="uk-width-1-1 uk-section-small uk-section">
					<div class="uk-container">
						<dv class="uk-grid-small uk-flex uk-flex-center" uk-grid>
							<div class="uk-width-5-6@l">
								<div class="uk-card uk-card-jess uk-card-body">
									<?php echo do_shortcode(tr_posts_field('content')); ?>
								</div>
							</div>
						</dv>
					</div>
				</div>


			</div>

		</div>


	<?php endwhile; ?>
<?php get_footer('contact') ?>

