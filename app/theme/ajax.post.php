<?php

add_action('wp_ajax_load_posts_by_ajax', 'load_posts_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'load_posts_by_ajax_callback');



function load_posts_by_ajax_callback() {
	check_ajax_referer('load_more_posts', 'security');
	$paged = $_POST['page'];

	$type = $_POST['type'];

	if($type === 'category' || $type === 'subcategory'):

		$args2 = array(
			'posts_per_page' => 8,
			'post__not_in' => $_POST['post__not_in'],
			'paged' => $paged,
			'tax_query' => [
				[
					'taxonomy' => 'category',
					'terms' => $_POST['cat'],
					'include_children' => true // Remove if you need posts from term 7 child terms
				],
			]
		);

		$posts2 = get_posts($args2);

        if($posts2):

            foreach ($posts2 as $post):
?>
            <div class="the_post">

                <div class="uk-card uk-card-default uk-card-small uk-card-article">
                    <div class="uk-card-media-top">
                        <?=  get_the_post_thumbnail( $post->ID, 'full');?>
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-article-meta uk-categorie"><a href="<?= get_category_link(get_the_category($post->ID)[0]->term_id);?>" class="uk-text-uppercase uk-text-bold uk-animation-shake" style="color: <?=  tr_taxonomies_field('couleur_cat', 'category', get_the_category($post->ID)[0]->term_id); ?>;"><?= get_the_category($post->ID)[0]->name; ?></a></div>
                        <h2 class="uk-article-titre uk-text-uppercase uk-h5 uk-text-truncate uk-margin-remove"><a href="<?php the_permalink($post->ID) ?>"><?php the_title() ?></a> </h2>
                        <div class="uk-article-meta uk-categorie"><?= get_the_date('d/m/Y', $post->ID) ?></div>

                    </div>
                </div>
            </div>

<?php

            endforeach;
        endif;


    endif;

    /// Debut des fournitures des resultats sur la page d'article
    if($type == 'single'):
        $current_post = $_POST['current_post'];

        $args = array(

            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => '4',
            'post__not_in' => [$current_post],
            'paged' => $paged,
            'cat' => $_POST['cat']

        );

        $similraire = new WP_Query( $args );

        if ( $similraire->have_posts() ) :


?>
	        <?php while ( $similraire->have_posts() ) : $similraire->the_post(); ?>


                <div class="the_post">

                    <div class="uk-card uk-card-default uk-card-small uk-card-article">
                        <div class="uk-card-media-top">
                            <?=  get_the_post_thumbnail( $similraire->ID, 'full');?>
                        </div>
                        <div class="uk-card-body">
                            <div class="uk-article-meta uk-categorie"><a href="<?= get_category_link(get_the_category($similraire->ID)[0]->term_id);?>" class="uk-text-uppercase uk-text-bold uk-animation-shake" style="color: <?=  tr_taxonomies_field('couleur_cat', 'category', get_the_category($similraire->ID)[0]->term_id); ?>;"><?= get_the_category($similraire->ID)[0]->name; ?></a></div>
                            <h2 class="uk-article-titre uk-text-uppercase uk-h5 uk-text-truncate uk-margin-remove"><a href="<?php the_permalink($similraire->ID) ?>"><?php the_title() ?></a> </h2>
                            <div class="uk-article-meta uk-categorie"><?= get_the_date('d/m/Y', $similraire->ID) ?></div>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

<?php
        endif;

    endif;



    //// debut des données des commentaires d'un article
    ///
    if($type == 'comment'):

        $current_post = $_POST['current_post'];

        $args_comment = array(
            'post_id' => $current_post,
            'order' => 'DESC',
            'hierarchical' => 'threaded',
            'number' => 3,
            'offset'=> $paged,
            'status' => 'approve'
        );
        $commentsArray = get_comments($args_comment);

        if($commentsArray) :
            foreach ($commentsArray as $e) :

?>

                    <article class="uk-comment uk-margin the_post_comment">
                        <header class="uk-comment-header uk-grid-medium uk-flex-middle" uk-grid>
                            <div class="uk-width-expand uk-first-column">
                                <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                                    <li>Le <?= date_i18n(get_option('date_format'), strtotime($e->comment_date)) ?></li>
                                </ul>
                            </div>
                            <div class="uk-width-1-1 uk-margin-small uk-text-bold"><?= $e->comment_author ?></div>
                        </header>
                        <div class="uk-comment-body">
                            <p>
				                <?= $e->comment_content; ?>
                            </p>
                        </div>
		                <?php
		                $args_comment_child = array(
			                'order' => 'DESC',
			                'parent' => $e->comment_ID,
			                'status' => 'approve'
		                );
		                $commentsArray_child = get_comments($args_comment_child);

		                foreach ($commentsArray_child as $e_child):
			                ?>
                            <hr>
                            <article class="uk-comment uk-margin uk-margin-medium-left" style="border: none">
                                <header class="uk-comment-header uk-grid-medium uk-flex-middle" uk-grid>
                                    <div class="uk-width-expand uk-first-column">
                                        <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">
                                            <li>Le <?= date_i18n(get_option('date_format'), strtotime($e_child->comment_date)) ?></li>
                                        </ul>
                                    </div>
                                    <div class="uk-width-1-1 uk-margin-small uk-text-bold"><?= $e_child->comment_author ?></div>
                                </header>
                                <div class="uk-comment-body">
                                    <p>
						                <?= $e_child->comment_content; ?>
                                    </p>
                                </div>
                            </article>
		                <?php endforeach; ?>
                    </article>


<?php
            endforeach;
        endif;
    endif;



    /// debut resultat de recherche en Ajax
    if($type == 'search'):

        $current_post = $_POST['query'];

        $args = array(

            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => '10',
            's' => $current_post,
            'paged' => $paged

        );

        $search = new WP_Query($args);

        if($search->have_posts()):

?>
            <?php while ( $search->have_posts() ) : $search->the_post() ?>


            <div class="uk-card uk-card-default uk-card-small uk-card-body uk-margin the_post">
                <article class="uk-article">
                    <div class="uk-grid-collapse" uk-grid>
                        <div class="uk-width-5-6">
                            <h3 class="uk-article-titre uk-text-uppercase uk-h4 uk-text-truncate uk-margin-remove"><a href="<?= get_the_permalink(get_the_ID()) ?>"><?= get_the_title(); ?></a> </h3>
                        </div>
                        <div class="uk-width-1-6">
                            <div class="uk-article-titre-categorie uk-float-right" style="background: <?=  tr_taxonomies_field('couleur_cat', 'category', get_the_category(get_the_ID())[0]->term_id); ?>;">

                            </div>
                        </div>
                    </div>
			        <?php $auteur_id = get_the_author_ID(); ?>
                    <div class="uk-article-meta" style="font-size: 12px">par <?php the_author_meta( 'display_name' , $auteur_id ); ?> le <?= get_the_date('d/m/Y', get_the_ID()) ?> .</div>

                    <div class="uk-margin-small uk-text-small dotdot uk-height-content uk-height-content-article">
				        <?= get_the_excerpt(); ?>
                    </div>

                    <div class="uk-grid-small uk-child-width-auto uk-text-small" uk-grid>
                        <div style="font-style: italic; font-size: 12px" >
                            <div class="uk-background-secondary uk-padding-small uk-padding-remove-vertical" style="color: #ffffff"><?= displayMontant(get_post_meta(get_the_ID(), 'post_views_count')[0]) ?> Vue(s)</div>
                        </div>
                    </div>

                </article>
            </div>

<?php
            endwhile;
        endif;
    endif;
    wp_die();
}