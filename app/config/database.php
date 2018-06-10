<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 15/03/2018
 * Time: 13:55
 */

function create_datatable($oldname, $oldtheme=false){
	global $wpdb;
	global $custom_table_example_db_version;
	$custom_table_example_db_version = '1.2';
	$charset_collate = $wpdb->get_charset_collate();

	require_once(ABSPATH.'wp-admin/includes/upgrade.php');

	$table_name = $wpdb->prefix . 'candidats';
	$sql        = "CREATE TABLE $table_name (
                id INT NOT NULL AUTO_INCREMENT,
                wp_user INT NULL,
                ville VARCHAR(255) NULL,
          		phone VARCHAR(255) NULL,
                nbre_enfant INT NULL,
                date_naissance VARCHAR(255) NULL,
                situation_matrimoniale INT NULL,
                PRIMARY KEY (id)
                ) $charset_collate;";

	dbDelta( $sql );


	add_option('custom_table_example_db_version', $custom_table_example_db_version);

}


add_action("after_switch_theme", "create_datatable", 10 , 2);