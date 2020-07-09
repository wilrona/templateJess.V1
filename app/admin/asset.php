<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 03/10/2017
 * Time: 12:07
 */

# ajout des elements css et js dans mon template
function themeprefix_bootstrap_modals() {


	wp_register_script ( 'uikit' , get_stylesheet_directory_uri() . '/js/uikit.js', array( 'jquery' ), '1', true );
	wp_register_script ( 'uikit-icons' , get_stylesheet_directory_uri() . '/js/uikit-icons.js', '', '1', true );
	wp_register_script ( 'morphtext' , get_stylesheet_directory_uri() . '/js/morphtext.js', '', '1', true );
	wp_register_script ( 'dotdot' , get_stylesheet_directory_uri() . '/js/jquery.dotdotdot.js', '', '1', true );
	wp_register_script ( 'carousel' , get_stylesheet_directory_uri() . '/js/owl.carousel.js', '', '1', true );
	wp_register_script ( 'app' , get_stylesheet_directory_uri() . '/js/app.js', '', '1', true );


	wp_register_style ( 'uikit' , get_stylesheet_directory_uri() . '/css/uikit.css', '' , '', 'all' );
	wp_register_style ( 'morphtext' , get_stylesheet_directory_uri() . '/css/morphtext.css', '' , '', 'all' );
	wp_register_style ( 'animate' , get_stylesheet_directory_uri() . '/css/animate.css', '' , '', 'all' );
	wp_register_style ( 'carousel' , get_stylesheet_directory_uri() . '/css/owl.carousel.css', '' , '', 'all' );
	wp_register_style ( 'app' , get_stylesheet_directory_uri() . '/css/app.css', '' , '1.2', 'all' );


	wp_enqueue_script( 'uikit' );
	wp_enqueue_script( 'uikit-icons' );
	wp_enqueue_script( 'morphtext' );
	wp_enqueue_script( 'dotdot' );
	wp_enqueue_script( 'carousel' );
	wp_enqueue_script( 'app' );

	wp_enqueue_style( 'uikit' );
	wp_enqueue_style( 'morphtext' );
	wp_enqueue_style( 'animate' );
	wp_enqueue_style( 'carousel' );
	wp_enqueue_style( 'app' );
}

add_action( 'wp_enqueue_scripts', 'themeprefix_bootstrap_modals');

function load_custom_wp_admin_asset() {
	wp_register_script ( 'admin' , get_stylesheet_directory_uri() . '/js/admin.js','', '1.1', true );
	wp_enqueue_script( 'admin' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_asset' );


// Ajout de select 2 dans l'interface d'administration
function enqueue_select2_jquery() {
	wp_register_style( 'select2css', get_stylesheet_directory_uri().'/css/select2.css', false, '1.0', 'all' );
	wp_register_script( 'select2', get_stylesheet_directory_uri().'/js/select2.min.js', '', '1.0', true );
	wp_enqueue_style( 'select2css' );
	wp_enqueue_script( 'select2' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );

function select2jquery_inline() {
	?>
<!--	<style type="text/css">-->
<!--		.select2-container {margin: 0 2px 0 2px;}-->
<!--		.tablenav.top #doaction, #doaction2, #post-query-submit {margin: 0px 4px 0 4px;}-->
<!--	</style>-->
	<script type='text/javascript'>
        jQuery(document).ready(function ($) {
            if( $( 'select.select' ).length > 0 ) {
                $( 'select.select' ).select2();
            }
        });
	</script>
	<?php
}
add_action( 'admin_head', 'select2jquery_inline' );



function select2jquery_inline_frontend() {
	?>
<!--    	<style type="text/css">-->
<!--    		.select2-container {margin: 0 2px 0 2px;}-->
<!--    		.tablenav.top #doaction, #doaction2, #post-query-submit {margin: 0px 4px 0 4px;}-->
<!--            .select2 {-->
<!--                width:100%!important;-->
<!--            }-->
<!--    	</style>-->
    <script type='text/javascript'>
        jQuery(document).ready(function ($) {
            if( $( 'select.selected' ).length > 0 ) {
                $( 'select.selected' ).select2();
//                $( document.body ).on( "click", function() {
//                    $( 'select' ).select2();
//                });
            }

            if( $( 'select.selectedComp' ).length > 0 ) {
                $( 'select.selectedComp' ).select2({
                    maximumSelectionLength: 10
                });
//                $( document.body ).on( "click", function() {
//                    $( 'select' ).select2();
//                });
            }
        });
    </script>
	<?php
}
add_action( 'wp_enqueue_scripts', 'enqueue_select2_jquery');
add_action( 'wp_footer', 'select2jquery_inline_frontend');


function datepicker(){
	wp_register_style( 'datepickercss', get_stylesheet_directory_uri().'/css/datepicker.css', false, '1.0', 'all' );
	wp_register_script( 'datepicker', get_stylesheet_directory_uri().'/js/datepicker.js', '', '1.0', true );
	wp_register_script( 'datepicker.fr', get_stylesheet_directory_uri().'/js/datepicker.fr-FR.js', '', '1.0', true );
	wp_register_script( 'repeater', get_stylesheet_directory_uri().'/js/jquery.repeater.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_style( 'datepickercss' );
	wp_enqueue_script( 'datepicker' );
	wp_enqueue_script( 'datepicker.fr' );
	wp_enqueue_script( 'repeater' );
}

add_action( 'wp_enqueue_scripts', 'datepicker');
add_action( 'admin_enqueue_scripts', 'datepicker');

function datepicker_script(){
    ?>

    <script type='text/javascript'>
        jQuery(document).ready(function ($) {
            if( $( '.datepicker' ).length > 0 ) {
                $( '.datepicker' ).datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    autoHide: true
                });
            }

            if( $( '.datepicker_birth' ).length > 0 ) {
                $( '.datepicker_birth' ).datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    startView : 2,
                    autoHide: true
                });
            }

            if( $( '.datepicker_start' ).length > 0 ) {
                $( '.datepicker_start' ).datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    startView : 2,
                    autoHide: true,
                    pick: function (date) {
                        $date_end = $(date.currentTarget).parent().next().find('input.datepicker_end');
                        $reforme_date_start_show = ('0' + date.date.getDate()).slice(-2)+'/'+( '0' + (date.date.getMonth()+1) ).slice( -2 )+'/'+date.date.getFullYear();
                        $reforme_date_start = ''+( '0' + (date.date.getMonth()+1) ).slice( -2 )+'/'+date.date.getDate()+'/'+date.date.getFullYear();

                        if($date_end){

                            $reforme_date_end = "";

                            if ($date_end.val() === ''){
                                $date_end.val($reforme_date_start_show);
                            }else{
                                date_end_js = $date_end.val().split('/');
                                $reforme_date_end = (date_end_js[1]) + '/' + date_end_js[0] + '/' + date_end_js[2];
                            }

                            if(new Date($reforme_date_start) >= new Date($reforme_date_end)){
                                $date_end.val($reforme_date_start_show);
                            }
                        }
                    }
                });
            }

            if( $( '.datepicker_end' ).length > 0 ) {
                $( '.datepicker_end' ).datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    startView : 2,
                    autoHide: true,
                    pick: function (date) {
                        $date_start = $(date.currentTarget).parent().prev().find('input.datepicker_start');
                        $reforme_date_end_show = ('0' + date.date.getDate()).slice(-2)+'/'+( '0' + (date.date.getMonth()+1) ).slice( -2 )+'/'+date.date.getFullYear();
                        $reforme_date_end = ''+( '0' + (date.date.getMonth()+1) ).slice( -2 )+'/'+date.date.getDate()+'/'+date.date.getFullYear();

                        if($date_start){

                            $reforme_date_start = "";

                            if ($date_start.val() === ''){
                                $date_start.val($reforme_date_end_show);
                            }else{
                                date_start_js = $date_start.val().split('/');
                                $reforme_date_start = (date_start_js[1]) + '/' + date_start_js[0] + '/' + date_start_js[2];
                            }


                            if(new Date($reforme_date_end) <= new Date($reforme_date_start)){
                                $date_start.val($reforme_date_end_show);
                            }
                        }
                    }
                });
            }

            if( $( '.datepicker_year_start' ).length > 0 ) {
                $( '.datepicker_year_start' ).datepicker({
                    language: 'fr-FR',
                    format: 'yyyy',
                    startView : 2,
                    autoHide: true,
                    pick: function(date){

                        if($('.datepicker_year_end')){

                            if ($('.datepicker_year_end').val() === ''){
                                $('.datepicker_year_end').val(date.date.getFullYear() + 1);
                            }

                            if(parseFloat(date.date.getFullYear()) >= parseInt($('.datepicker_year_end').val())){

                                $('.datepicker_year_end').val(date.date.getFullYear() + 1);
                            }
                        }

                    }
                })
            }

            if( $( '.datepicker_year_end' ).length > 0 ) {
                $( '.datepicker_year_end' ).datepicker({
                    language: 'fr-FR',
                    format: 'yyyy',
                    startView : 2,
                    autoHide: true,
                    pick: function(date){
                        if($('.datepicker_year_start')){

                            if ($('.datepicker_year_start').val() === ''){
                                $('.datepicker_year_start').val(date.date.getFullYear() - 1);
                            }

                            if(parseFloat(date.date.getFullYear()) <= parseInt($('.datepicker_year_start').val())){

                                $('.datepicker_year_start').val(date.date.getFullYear() - 1);
                            }
                        }
                    }
                })
            }



// Listen for input event on numInput.
            $('#number').onkeydown = function(e) {
                if(!((e.keyCode > 95 && e.keyCode < 106)
                        || (e.keyCode > 47 && e.keyCode < 58)
                        || e.keyCode == 8)) {
                    return false;
                }
            }


            $('.repeater').repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function (remove) {
                    if(confirm('Etes vous sure de supprimer cet élément ?')) {
                        $(this).slideUp(remove);
                    }
                }
            });
        });
    </script>


<?php

}

add_action( 'wp_footer', 'datepicker_script');

function datepicker_script_admin(){
	?>

    <script type='text/javascript'>
        jQuery(document).ready(function ($) {
            if( $('.datepicker').length > 0 ) {
                $('.datepicker').datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    autoHide: true
                });
            }
        });
    </script>


	<?php

}

add_action( 'admin_footer', 'datepicker_script_admin');