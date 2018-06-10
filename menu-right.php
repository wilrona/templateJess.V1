<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 22/02/2018
 * Time: 11:42
 */

?>

<?php //$user = _wp_get_current_user(); ?>
<!---->
<?php //if($user->ID == 0): ?>

<?php
	$menu_arg = array(
		'container'       => false,
		'container_class' => '',
		'menu_class' => 'uk-subnav uk-subnav-pill uk-subnav-divider uk-margin-remove-bottom',
		'theme_location' => 'login-nav',
		'items_wrap' =>'<ul id="%1$s" class="%2$s">%3$s</ul>',
	);
	wp_nav_menu($menu_arg);
?>


<?php //else: ?>
<!---->
<!--	--><?php
//	$menu_arg = array(
//		'container'       => false,
//		'container_class' => '',
//		'menu_class' => 'uk-subnav uk-subnav-pill uk-subnav-divider uk-margin-remove-bottom',
//		'theme_location' => 'profil-nav',
//		'items_wrap' =>'<ul id="%1$s" class="%2$s">%3$s</ul>',
//	);
//	wp_nav_menu($menu_arg);
//	?>
<!---->
<?php //endif; ?>
