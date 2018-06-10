<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 22/02/2018
 * Time: 12:26
 */
?>

<?php /* Template Name: Page profil disponible */ ?>

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
        <?php

        $slider = tr_posts_field('profilslider');

        ?>

        <?php if($slider):  ?>
        <div class="uk-grid-small uk-flex" uk-grid>
            <div class="uk-width-1-1">
                <div class="uk-container">
                    <div uk-slider>


                        <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m uk-light">
            <?php foreach ($slider as $it) : ?>
                            <li class="uk-transition-toggle">
                                <img src="<?php echo wp_get_attachment_image_src($it['imageslider'], 'full')[0] ?>" alt="">
                            </li>
            <?php endforeach; ?>
                        </ul>

                    </div>

                </div>
            </div>
        </div>
        <?php
            endif;
        ?>
        <div class="uk-grid-small uk-flex" uk-grid>

            <div class="uk-width-1-1">
                <div class="uk-container">
                    <div class="uk-padding uk-background-default">
                        <div class="" uk-grid>
                            <div class="uk-width-1-1@l">
                                <div>
                                    <?php wpautop(the_content()) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                $valeurs = tr_posts_field('profil');
                if($valeurs):
            ?>

            <div class="uk-width-1-1 uk-section uk-section-small">
                <div class="uk-container">

                    <div class="uk-child-width-1-2@l uk-flex uk-flex-center" uk-grid uk-height-match="target: > div > .uk-card">
                        <?php
                            foreach ($valeurs as $valeur):
                        ?>
                        <div>
                            <div class="uk-card uk-card-default uk-card-small uk-card-overlay">
                                <div class="uk-card-media-top uk-inline">
                                    <img src="<?php echo wp_get_attachment_image_src($valeur['imgvaleur'], 'full')[0] ?>" alt="" class="uk-width-1-1">

                                </div>
                                <div class="uk-card-body">
                                    <div class="uk-margin-top uk-margin-bottom">
                                        <h3 class="uk-card-title uk-text-center"><?= $valeur['titrevaleur'] ?></h3>
                                        <div>
                                            <?=
                                                wpautop($valeur['textevaleur']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

        </div>
    </div>



<?php endwhile; ?>
<?php get_footer() ?>
