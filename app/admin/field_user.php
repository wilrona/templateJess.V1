<?php
/**
 * Created by IntelliJ IDEA.
 * User: NDI RONALD STEVE
 * Date: 07/04/2018
 * Time: 17:18
 */


add_action('tr_user_profile', function($user) {

	if(user_can($user->ID, 'subscriber')){
		$form = tr_form();

		echo $form->text('matricule')->setLabel('Matricule de l\'utilisateur');
		echo $form->text('cnps')->setLabel('Numero CNPS');
	}
});