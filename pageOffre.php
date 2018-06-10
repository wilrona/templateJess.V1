<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 05/03/2018
 * Time: 05:46
 */
?>

<?php /* Template Name: Page offres */ ?>

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
			<div class="uk-width-1-1 uk-banner uk-bgcolor-3 uk-section uk-section-small ">
				<div class="uk-container">
                    <form action="<?= home_url('/offre-demploi/')?>" method="get">

                        <div class="uk-grid-collapse uk-margin-medium-top" uk-grid>
                            <div class="uk-width-5-6@l">
                                <div class="uk-grid-small" uk-grid>
                                    <div class="uk-width-2-3@l">
                                        <input class="uk-input uk-input-search" name="keywords" value="<?= $_GET['keywords'] ?>" type="text" placeholder="offre d'emploi">
                                    </div>
                                    <div class="uk-width-1-3@l">
                                        <input class="uk-input uk-input-search" name="location" value="<?= $_GET['location'] ?>" type="text" placeholder="localisation">
                                    </div>
                                </div>
                            </div>
                            <div class="uk-width-1-6@l uk-flex uk-flex-center">
                                <button type="submit" class="uk-button uk-button-jess-inverse">Recherche</button>
                            </div>
                        </div>
                    </form>
				</div>
			</div>

            <?php

            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

            if($_GET['keywords'] || $_GET['location']){

                $terms_unique2 = array();
                $terms_unique = array();

                $get_keywords = null;
                $get_location = null;

                if($_GET['keywords'] && !empty($_GET['keywords'])){

	                $get_keywords = $_GET['keywords'];

	                $args = array(
		                'taxonomy'      => 'categorie_demploi', // taxonomy name
		                'hide_empty'    => false,
		                'fields'        => 'all',
		                'name__like'    => $_GET['keywords']
	                );

	                $terms_categorie = get_terms( $args );

	                $terms_cat = array();
	                foreach ($terms_categorie as $terms){
	                    array_push($terms_cat, ''.$terms->term_id.'');
                    }

                    if(!sizeof($terms_cat)){
	                    array_push($terms_cat, '0');
                    }

	                $args = array(
		                'taxonomy'      => 'competence', // taxonomy name
		                'hide_empty'    => false,
		                'fields'        => 'all',
		                'name__like'    => $_GET['keywords']
	                );

	                $terms_compo = get_terms( $args );


	                $terms_comp = array();
	                foreach ($terms_compo as $terms){
		                array_push($terms_comp, ''.$terms->term_id.'');
	                }

	                $custom_args_q1 = array(
		                'post_type' => 'emploi',
		                'posts_per_page' => 10,
		                'paged' => $paged,
		                'meta_query' => array(
                            'relation' => 'OR',
			                array(
				                'key'     => 'categorie_emploi',
				                'value'   => $terms_cat,
				                'compare' => 'IN',
			                ),
		                )
	                );


	                if(sizeof($terms_comp)){
	                    $compet = array(
	                            'relation' => 'OR'
                        );
	                    foreach ($terms_comp as $comp){
	                        $value = array(
		                        'key'     => 'competence',
		                        'value'   =>  $comp,
		                        'compare' => 'LIKE',
                            );

		                    array_push($compet, $value);
                        }

                        array_push($custom_args_q1['meta_query'], $compet);

                    }


	                $q1 = new WP_Query( $custom_args_q1 );



                    $custom_args_q2 = array(
		                'post_type' => 'emploi',
		                's' => $_GET['keywords'],
		                'posts_per_page' => 10,
		                'paged' => $paged
	                );

	                $q2 = new WP_Query($custom_args_q2);

	                $unique =  array_unique(array_merge( $q1->posts, $q2->posts  ));

	                $terms_unique = array();
	                foreach ($unique as $unik){
		                array_push($terms_unique, $unik->ID);
	                }

	                if(!sizeof($terms_unique)){
		                array_push($terms_unique, 0);
	                }


                }

                if ($_GET['location'] && !empty($_GET['location'])){

                    $get_location = $_GET['location'];

                    $args = array(
                        'taxonomy'      => 'ville', // taxonomy name
                        'hide_empty'    => false,
                        'fields'        => 'all',
                        'name__like'    => $_GET['keywords']
                    );

	                $terms_ville = get_terms( $args );

                    $terms_vil = array();
                    foreach ($terms_ville as $terms){
                        array_push($terms_vil, ''.$terms->term_id.'');
                    }

                    if(!sizeof($terms_vil)){
                        array_push($terms_vil, '0');
                    }

	                $custom_args_q1 = array(
		                'post_type' => 'emploi',
		                'posts_per_page' => 10,
		                'paged' => $paged,
		                'meta_query' => array(
			                'relation' => 'OR',
			                array(
                                'key'     => 'ville',
                                'value'   => $terms_vil,
                                'compare' => 'IN',
			                )
		                )
	                );

	                $q1 = new WP_Query( $custom_args_q1 );

	                $custom_args_q2 = array(
		                'post_type' => 'emploi',
		                's' => $_GET['location'],
		                'posts_per_page' => 10,
		                'paged' => $paged
	                );

	                $q2 = new WP_Query($custom_args_q2);

	                $unique2 =  array_unique(array_merge( $q1->posts, $q2->posts  ));

	                $terms_unique2 = array();
	                foreach ($unique2 as $unik){
		                array_push($terms_unique2, $unik->ID);
	                }

	                if(!sizeof($terms_unique2)){
		                array_push($terms_unique2, '0');
	                }
                }

                $terms_global = array();

                if($get_keywords && $get_location){

                    $unique_array = array_intersect($terms_unique2, $terms_unique);

	                $terms_global = $unique_array;
                }

                if($get_keywords && !$get_location){
                    $terms_global = $terms_unique;
                }
                if(!$get_keywords && $get_location){
                    $terms_global = $terms_unique2;
                }


	            if(!sizeof($terms_global)){
		            array_push($terms_global, '0');
	            }

	            $today = time();
	            $custom_args = array(
		            'post_type' => 'emploi',
		            'post__in' => $terms_global,
		            'posts_per_page' => 10,
		            'paged' => $paged,
		            'meta_query' => array(
			            array(
				            'key'     => 'datefinconvert',
				            'compare' => '>=',
				            'value' => $today
			            )
		            ),
		            'orderby' => 'key',
		            'order' => 'ASC'
	            );

	            $custom_query = new WP_Query( $custom_args );



            }else{
	            $today = time();
	            $custom_args = array(
		            'post_type' => 'emploi',
		            'posts_per_page' => 10,
		            'paged' => $paged,
		            'meta_query' => array(
			            array(
				            'key'     => 'datefinconvert',
				            'compare' => '>=',
				            'value' => $today
			            )
		            ),
		            'orderby' => 'key',
		            'order' => 'ASC'
	            );

	            $custom_query = new WP_Query( $custom_args );
            }

		?>

			<div class="uk-width-1-1 uk-section uk-section-small">
				<div class="uk-container">
					<div class="uk-grid-small" uk-grid>
						<div class="uk-width-1-1">
							<span class="uk-text-bold">
								<?= $custom_query->post_count;  ?> offre(s) d'emploi
							</span> correspondant à votre recherche
						</div>

						<div class="uk-width-1-1">
							<div class="uk-card uk-card-body uk-background-muted uk-card-small">
								<span class="uk-margin-right">Filtre</span>

								<select name="filtre" id="filtre-search" class="uk-input-search">
									<option value="type_soumission" >par date de soumission</option>
									<option value="type_contrat" >par type de contrat</option>
								</select>
							</div>
							<div class="uk-card uk-card-body uk-background-default job-list uk-card-small">
                                <div class="uk-overflow-auto">
								<table class="uk-table uk-table-small uk-table-divider">
									<tbody>
                                    <?php
                                            if ( $custom_query->have_posts() ) :
                                    ?>
	                                            <?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
                                        <tr>
                                            <td class="uk-width-1-5 logo">
<!--	                                            --><?php
//	                                            if(get_the_post_thumbnail_url(get_the_ID(), 'full')):
//		                                            ?>
<!--                                                    <img src="--><?//= get_the_post_thumbnail_url(get_the_ID(), 'full')?><!--" alt="">-->
<!---->
<!--		                                            --><?php
//	                                            else:
//		                                            ?>

                                                    <div class="uk-background-default uk-padding-small uk-margin-right">
                                                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="" class="uk-responsive-height">
                                                    </div>

<!--		                                            --><?php
//	                                            endif;
//	                                            ?>
                                            </td>
                                            <td class="uk-width-auto content">
                                                <p class="title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></p>
                                                <div class="span"><span uk-icon="icon: file-edit" class="uk-margin-small-right"></span><?= get_term(tr_posts_field('type_contrat'))->name ?></div>
                                                <div class="span"><span uk-icon="icon: location" class="uk-margin-small-right"></span><?= get_term(tr_posts_field('ville'))->name ?></div>
                                                <div class="span"><span uk-icon="icon: calendar" class="uk-margin-small-right"></span><?php sky_date_french('d F Y', get_post_time('U', true), 1); ?></div>
                                            </td>
                                            <td class="uk-width-1-6">
                                                <a href="<?php the_permalink() ?>" class="uk-button uk-button-jess" target="_blank">Voir l'offres</a>
                                            </td>
                                        </tr>

                                                <?php endwhile; ?>

                                                <?php else: ?>

                                    <tr>
                                        <td rowspan="3" class="uk-width-1-1">
                                            <h2 class="uk-text-center uk-margin-remove">Aucune offre disponible pour le moment</h2>
                                        </td>
                                    </tr>

                                    <?php endif; ?>

									</tbody>
								</table>

                                </div>

								<div class="uk-margin-large-top">
									<?php
                                        if (function_exists(kriesi_pagination)) {
                                            kriesi_pagination($custom_query->max_num_pages);
                                        }
									?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php endwhile; ?>
<?php get_footer() ?>
