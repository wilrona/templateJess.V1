<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 02/04/2018
 * Time: 06:56
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
			<h1 class="uk-margin-medium-top uk-margin-small-bottom uk-text-jess">Actualités</h1>
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
							<a href="<?= home_url('/nosactualites') ?>" class="uk-button uk-button-jess-inverse">Retour à l'actualité</a>
						</div>
						<div class="uk-width-1-1@l job-list">
							<div class="uk-card uk-card-body uk-background-muted uk-card-small">
								<h2 class="uk-margin-right uk-h1"><?= get_the_title() ?></h2>
								<div class="uk-text-bold">Publié le <?php sky_date_french('d F Y', get_post_time('U', true), 1); ?></div>
							</div>
							<div class="uk-card uk-card-body uk-card-large uk-background-default job-list">
								<div class="uk-grid-large" uk-grid uk-height-match="">
									<div class="uk-width-1-1@l">
										<?= wpautop(get_the_content()) ?>

										<?php
											$term_Post = get_the_terms( get_the_ID(), 'category' );
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="uk-child-width-1-2@l" uk-grid>
							<div>
								<?php
								$prev_post = get_adjacent_post(true, '', true, $term_Post[0]->slug);
								if(!empty($prev_post)) {
									?>
									<div class="td-post-next-prev-content">
										<span class="uk-display-block">Precedent article</span>
										<a href="<?= get_permalink($prev_post->ID) ?>"><?= $prev_post->post_title ?>
										</a>
									</div>
								<?php } ?>
							</div>

							<div>
								<?php
								$next_post = get_adjacent_post(true, '', false, $term_Post[0]->slug);
								if(!empty($next_post)) {
									?>
									<div class="td-post-next-prev-content">
										<span class="uk-display-block">Article suivant</span>
										<a href="<?= get_permalink($next_post->ID) ?>"><?= $next_post->post_title ?>
										</a>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>




<?php endwhile; ?>
<?php get_footer() ?>