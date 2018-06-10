<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 23/02/2018
 * Time: 10:39
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
                <div class="uk-width-2-3@l">
                    <div class="uk-card uk-card-jess uk-card-default uk-width-1-1">
                        <div class="uk-card uk-grid-collapse uk-child-width-1-2 uk-margin uk-margin-large-bottom" uk-grid>
                            <div class="uk-card-media-left uk-cover-container">
                                <img src="<?= get_the_post_thumbnail_url(get_the_ID()) ?>" alt="" uk-cover>
                                <canvas width="400" height="200"></canvas>
                            </div>
                            <div class="uk-background-default-jess">
                                <div class="uk-card-body uk-flex uk-flex-middle uk-flex-center uk-height-1-1">
                                    <h3 class="uk-card-title uk-text-jess-inverse"><?php the_title() ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="uk-card-body">
                            <?php wpautop(the_content()) ?>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-3@l uk-padding-right uk-sticky-left">
                    <div uk-sticky="offset: 20; bottom: true">
                        <?php get_template_part( 'sidebar' ); ?>
                    </div>
                </div>
            </div>
        </div>


    <?php endwhile; ?>
<?php get_footer() ?>
