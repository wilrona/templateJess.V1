<?php
if ( ! function_exists( 'add_action' )) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Setup Form
$form = tr_form()->useJson()->setGroup( $this->getName() );
?>

<h1>Theme Options</h1>
<div class="typerocket-container">
	<?php
	echo $form->open();

	// Information sur les réseaux sociaux
	$social = function() use ($form) {
		echo $form->text('facebook')->setLabel('Lien de la page facebook');
		echo $form->text('tweeter')->setLabel('Lien du compte tweeter');
		echo $form->text('linkedin')->setLabel('Lien de la page linkedin');
		echo $form->image('logodesktop')->setLabel('Logo pour la version desktop');
		echo $form->image('logomobile')->setLabel('Logo pour la version mobile');
	};


	$custom = function () use ($form){
	    echo $form->image('bgimg')->setLabel('Image de fond d\'écran');
	    echo $form->color('bgcolor')->setLabel('Couleur de fond d\'écran')->setHelp('Il ne s\'applique que lorsque l\'image n\'est pas definie');
    };

	$message = function () use ($form){
	    echo $form->text('message1')->setLabel('ligne de message 1');
	    echo $form->text('message2')->setLabel('ligne de message 2');
	    echo $form->text('message3')->setLabel('ligne de message 3');
    };

	$partenaire = function () use ($form){

        $repeater = $form->repeater('partenaireslide')->setFields([
            $form->image('partenaireslider')->setLabel('Image du partenaire')
        ])->setLabel('Slider des partenaires');
        echo $repeater;
    };

	$linkpage = function () use ($form){
		echo $form->search('lien_page_recruteur')->setLabel('Lien de la page recruteur')->setPostType('page');
		echo $form->search('lien_page_annonce')->setLabel('Lien de la page annonce')->setPostType('page');
		echo $form->search('lien_page_candidat')->setLabel('Lien de la page candidat')->setPostType('page');
		echo $form->search('lien_page_register')->setLabel('Lien de la page Inscription')->setPostType('page');
		echo $form->search('lien_page_contact')->setLabel('Lien de la page Contact')->setPostType('page');
    };

	$admin = function () use ($form){
		$repeater = $form->repeater('email_admin')->setFields([
			$form->text('email')->setLabel('Adresse Email')
		])->setLabel('Adresse Email des administrateurs');
		echo $repeater;
    };
	// Save
	$save = $form->submit( 'Save' );

	// Layout
	tr_tabs()->setSidebar( $save )
	         ->addTab( 'Reseaux sociaux', $social )
	         ->addTab( 'Personnalisation Background', $custom )
	         ->addTab( 'Redaction des messages', $message )
             ->addTab('Partenaire de l\'entreprise', $partenaire)
             ->addTab('Lien vers les pages importantes', $linkpage)
             ->addTab('Adresse Email des admins', $admin)
	         ->render( 'box' );
	echo $form->close();
	?>

</div>