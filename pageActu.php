<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 23/02/2018
 * Time: 11:51
 */

?>

<?php /* Template Name: Page actualites */ ?>

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
                    global $wp;
                    $categorie = get_categories(
	                    array(
		                    'orderby' => 'name',
		                    'parent'  => 0,
		                    'hide_empty' => 1
	                    )
                    );

                    if($categorie):
                ?>
                <div class="uk-width-1-1 uk-margin-bottom">
                    <div class="uk-container">
                        <div class="uk-background-default uk-padding-small">
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-1-6@l">
                                    <div class="uk-h2 uk-margin-small-left"> Filtre</div>
                                </div>
                                <div class="uk-width-5-6@l uk-flex uk-flex-middle">
                                    <ul class="uk-subnav uk-subnav-divider uk-subnav-pill uk-margin-remove">
                                        <li <?php if(!isset($_GET['categorie'])): ?> class="uk-active" <?php endif; ?>><a href="<?= home_url( $wp->request ) ?>" style="font-size: 12px;">Tous</a></li>
                                        <?php

                                            foreach ($categorie as $cat):
                                        ?>
                                           <li <?php if($_GET['categorie'] == $cat->term_id): ?> class="uk-active" <?php endif; ?>><a href="<?= home_url( $wp->request ) ?>?categorie=<?= $cat->term_id ?>"  style="font-size: 12px;"><?= $cat->name ?></a></li>
                                        <?php endforeach; ?>

                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                    <?php
                        endif;
                    ?>

                <?php

                    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

                    if(!isset($_GET['categorie'])):
                        $arg = array(
                            'post_type' => 'post',
                            'posts_per_page' => 12,
                            'order' => 'desc',
                            'paged' => $paged
                        );
                    else:
                        $arg = array(
                            'post_type' => 'post',
                            'posts_per_page' => 12,
                            'order' => 'desc',
                            'category' => $_GET['categorie'],
                            'paged' => $paged
                        );
                    endif;

                    $actualite = new WP_query($arg);
                ?>
                <div class="uk-width-1-1">
                    <div class="uk-container">
                        <div class="uk-child-width-1-4@l uk-grid-small uk-jess-article uk-flex uk-flex-center" uk-grid>
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

                        <div class="uk-margin-large-top">
	                        <?php
	                        if (function_exists(kriesi_pagination)) {
		                        kriesi_pagination($actualite->max_num_pages);
	                        }
	                        ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>




    <?php endwhile; ?>
<?php get_footer() ?>
