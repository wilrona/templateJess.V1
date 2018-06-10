<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 21/02/2018
 * Time: 12:27
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title>
        <?php if ( is_category() ) {
            single_cat_title(); echo ' | '; bloginfo( 'name' ); echo ' - '; bloginfo( 'description' );
//	    } elseif ( is_tag() ) {
//		    echo 'Tag Archive for &quot;'; single_tag_title(); echo '&quot; | '; bloginfo( 'name' );
//	    } elseif ( is_archive() ) {
//		    wp_title(''); echo ' Archive | '; bloginfo( 'name' );
        } elseif ( is_search() ) {
            echo 'Recherche pour &quot;'.wp_specialchars($s).'&quot; | '; bloginfo( 'name' ); echo ' - '; bloginfo( 'description' );;
        } elseif ( is_home() || is_front_page() ) {
            bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
	    }  elseif ( is_404() ) {
		    echo 'Error 404 Not Found | '; bloginfo( 'name' );
        } elseif ( is_single() ) {
            wp_title(''); echo ' | '; bloginfo( 'name' ); echo ' - '; bloginfo( 'description' );
        } else {
            wp_title(''); echo ' | '; bloginfo( 'name' ); echo ' - '; bloginfo( 'description' );
        } ?>
    </title>
    <meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/images/logo-icone.png" />
    <link href="https://fonts.googleapis.com/css?family=Neuton:300,400,400i,700" rel="stylesheet">
    <?php
    wp_head();
    ?>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
</head>
<body class="uk-offcanvas-content">

    <div id="offcanvas-overlay" uk-offcanvas="overlay: true" class="uk-offcanvas-jess">
        <div class="uk-offcanvas-bar uk-position-relative uk-height-1-1">

            <a href="<?= home_url() ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" class="uk-display-block uk-margin-auto" alt=""></a>

            <?php
            $menu_arg = array(
                'container'       => false,
                'container_class' => '',
                'menu_class' => 'uk-nav uk-margin-large-top',
                'theme_location' => 'header-nav',
                'items_wrap' =>'<ul id="%1$s" class="%2$s">%3$s</ul>',
            );
            wp_nav_menu($menu_arg);
            ?>

            <div class="uk-position-bottom-center uk-bottom-nav">
                <div class="uk-text-center">
                    <a href="#" target="_blank"  uk-icon="icon: facebook" class="uk-border-circle uk-border-facebook"></a>
                </div>
                <meta name="viewport" content="width=device-width, initial-scale=1">

                <span class="uk-text-small">Desgin by <a href="#" target="_blank">aligodu</a></span>
            </div>

        </div>
    </div>
    <div class="uk-width-xsmall uk-box-shadow-medium uk-height-1-1 uk-navleft uk-bgcolor-1 uk-visible@s" uk-height-viewport>
        <div class="uk-bgcolor-2 uk-padding uk-padding-remove-horizontal uk-padding-remove-bottom">

            <a href="<?= home_url() ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo-icone.png" class="uk-display-block uk-margin-auto" alt=""></a>
        </div>

        <div class="uk-padding-small uk-text-center">
            <a href="#" uk-icon="icon: menu; ratio: 2" uk-toggle="target: #offcanvas-overlay"></a>
        </div>

        <div class="uk-position-bottom-center uk-padding-small uk-text-center">
            <span uk-icon="icon: arrow-right;" class="uk-border-circle uk-border-1 uk-margin-medium-bottom"></span>
        </div>
    </div>

    <div class="uk-bgimage-1 uk-height-1-1 uk-padding uk-padding-remove-horizontal uk-padding-remove-top" style="box-sizing: border-box; min-height: 100vh; background-color: <?= tr_options_field('pc_options.bgcolor') ?>; background-image: url('<?php echo wp_get_attachment_image_src(tr_options_field('pc_options.bgimg'), 'full')[0] ?>');">


