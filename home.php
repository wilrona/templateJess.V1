<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 22/02/2018
 * Time: 11:34
 */
?>

<?php /* Template Name: Page accueil */ ?>

<?php get_header() ?>
<?php while ( have_posts() ) : the_post(); ?>
<div class="uk-header">
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
    <div class="uk-grid-small" uk-grid">

        <?php

        $slider = tr_posts_field('homeslider');

        ?>

		<?php if($slider):  ?>

        <div class="uk-width-1-1 uk-padding uk-padding-remove-horizontal" uk-height-viewport="offset-top: true">
            <div class="uk-position-relative uk-visible-toggle uk-light" uk-slider="clsActivated: uk-transition-active; center: true; autoplay: true; pause-on-hover: false; autoplay-interval: 3000; velocity: 0.5">

                <ul class="uk-slider-items uk-grid">
				<?php foreach ($slider as $it) : ?>
                    <li class="uk-width-3-4@m">
                        <div class="uk-panel">
                            <img src="<?php echo wp_get_attachment_image_src($it['imageslider'], 'full')[0] ?>" alt="" class="uk-width-1-1 uk-height-1-1" style="height: 100%">
                            <div class="uk-overlay uk-overlay-primary <?php if($it['positiontexteslider']): ?> uk-position-<?= $it['positiontexteslider'] ?> uk-transition-slide-<?= $it['positiontexteslider'] ?> <?php endif; ?> <?php if($it['positiontexteslider'] && $it['positiontexteslider'] === 'bottom'): ?> uk-height-small uk-padding-large uk-padding-remove-vertical <?php else: ?> uk-width-large <?php endif; ?> uk-text-center  uk-flex uk-flex-middle uk-flex-center">
                                <div>
                                    <h3 class="uk-margin-remove"><?= $it['texteslider']; ?></h3>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
                </ul>

                <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>

            </div>
        </div>

        <?php endif; ?>
        <div class="uk-width-1-1 uk-banner uk-bgcolor-3 uk-section uk-section-small ">
            <div class="uk-container">
                <h2 class="uk-margin-remove uk-text-jess-inverse uk-text-center"><?= tr_posts_field('slogan'); ?></h2>

                <form action="<?= home_url('/offre-demploi/')?>" method="get">
                <div class="uk-grid-small uk-margin-medium-top" uk-grid uk-margin>
                        <div class="uk-width-5-6@l">
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-2-3@l">
                                    <input class="uk-input uk-input-search" name="keywords" type="text" placeholder="offre d'emploi">
                                </div>
                                <div class="uk-width-1-3@l">
                                    <input class="uk-input uk-input-search" name="location" type="text" placeholder="localisation">
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-1-6@l uk-flex uk-flex-center">
                            <button type="submit" class="uk-button uk-button-jess-inverse uk-width-1-1">Recherche</button>
                        </div>

                </div>
                </form>
            </div>
        </div>
        <?php
            $homeservice = tr_posts_field('homeservices');

            if($homeservice):
        ?>
        <div class="uk-section uk-width-1-1">
           <div class="uk-container">
               <div class="uk-text-center uk-margin-large-bottom">
                   <h2 class="uk-h1 uk-text-jess uk-text-uppercase">Solutions</h2>
               </div>

               <div class="uk-grid-collapse uk-child-width-1-3@l uk-flex-center uk-flex" uk-grid uk-margin>
                   <?php
                        foreach ($homeservice as $service):

                            $itemService = get_post($service['serviceitem']);
                   ?>
                   <div class="uk-home-service">
                       <a href="<?= get_the_permalink(get_post(tr_posts_field('pageservice'))->ID).'#'.$service['servicetagscroll'] ?>" class="uk-display-block">
                           <div class="uk-cover-container uk-transition-toggle">
                               <img src="<?= get_the_post_thumbnail_url($itemService->ID); ?>" alt="" class="uk-transition-scale-up uk-transition-opaque" uk-cover>
                               <canvas width="600" height="400"></canvas>
                               <div class="uk-position-cover uk-overlay uk-flex uk-flex-center uk-flex-middle">
                                   <span class="uk-h3" style="color:#fff;"><?= $itemService->post_title ?></span>
                               </div>
                           </div>
                       </a>
                   </div>
                    <?php
                        endforeach;
                    ?>
               </div>
           </div>


        </div>
        <?php
            endif;
        ?>
        <div class="uk-width-1-1 uk-section-small uk-section uk-background-norepeat uk-background-cover uk-background-center-center uk-background-fixed" style="min-height: 400px;  background-image:url('<?php echo wp_get_attachment_image_src(tr_posts_field('homeimageblock'), 'full')[0] ?>');">
            <div class="uk-container">
                <div class="uk-padding uk-flex uk-flex-middle uk-flex-center" uk-grid uk-margin>
                    <div class="uk-width-1-2@l">
                        <span class="uk-display-block uk-h1 uk-margin-medium-bottom" style="color: #ffffff;"><?= tr_posts_field('hometextblock'); ?></span>
                        <div class="uk-grid-small uk-child-width-1-2@l" uk-grid>
                            <div>
                                <a href="<?= get_the_permalink(tr_posts_field('linkpostuler')) ?>" class="uk-button uk-button-jess uk-width-1-1">Postuler</a>
                            </div>
                            <div>
                                <a href="<?= get_the_permalink(tr_posts_field('linkoffre')) ?>" class="uk-button uk-button-jess-inverse-white uk-width-1-1">Decouvrir nos offres</a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-1-2@l uk-flex uk-flex-right uk-flex-middle">
	                    <div class="uk-background-default uk-padding uk-width-4-5@l">
                            <h2 class="uk-text-center uk-margin-bottom uk-h1"><?= tr_posts_field('newsletterimageblock') ?></h2>
		                    <?php es_subbox($namefield = "YES", $desc = "", $group = "Public"); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php
            $arg = array(
                'post_type' => 'post',
                'posts_per_page' => 8,
                'order' => 'desc'
            );

            $actualite = new WP_query($arg);

            if($actualite->have_posts()):
        ?>
        <div class="uk-width-1-1 uk-section uk-section-small">
            <div class="uk-container">
                <div class="uk-text-center uk-margin-large-bottom">
                    <h2 class="uk-h1 uk-text-jess uk-text-uppercase">Une structure qui bouge</h2>
                </div>
                <div class="uk-child-width-1-4@l uk-grid-small uk-jess-article uk-flex uk-flex-center" uk-grid uk-height-match=".uk-card">

                    <?php
                    while ($actualite->have_posts()) :
                        $actualite->the_post();
                        global $post;

		            ?>
                    <div>
                        <div class="uk-card uk-card-small uk-card-default">
                            <div class="uk-card-body">
                                <h3 class="uk-card-title uk-margin-medium-top truncate"><a href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a></h3>
                            </div>
                            <div class="uk-card-media-top">
                                <img src="<?= get_the_post_thumbnail_url(get_the_ID()) ?>" alt="" class="uk-height-small uk-width-1-1">
                            </div>
                            <div class="uk-card-body">
                                <div class="uk-card-badge uk-label"><?= get_the_category(get_the_ID())[0]->name; ?></div>
                                <div class="dotdo uk-text-middle" style="height: 100px"><?= get_the_excerpt() ?> </div>
                            </div>
                        </div>
                    </div>
		            <?php
                        endwhile;
                    ?>
                </div>
            </div>
        </div>
        <?php
            endif;
        ?>

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
                                <div class="uk-grid-small uk-child-width-auto@l" uk-grid>
                                    <div>
                                        <a href="<?= get_the_permalink(tr_options_field('pc_options.lien_page_recruteur')) ?>" class="uk-button uk-button-jess uk-width-1-1">Déposez votre annonce</a>
                                    </div>
                                </div>
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
