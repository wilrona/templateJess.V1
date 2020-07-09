<?php
/**
 * Created by IntelliJ IDEA.
 * User: macbookpro
 * Date: 21/02/2018
 * Time: 12:28
 */

include( 'admin-ui/admin-ui.php' );

include( 'framework/init.php' );

include( 'app/init.php' );

tr_frontend();

//Ensure that a session exists (just in case)
if( !session_id() )
{
	session_start();
}


/**
 * Function to create and display error and success messages
 * @access public
 * @param string session name
 * @param string message
 * @param string display class
 * @return string message
 */
function flash( $name = '', $message = '', $class = 'uk-alert-success' )
{
	//We can only do something if the name isn't empty
	if( !empty( $name ) )
	{
		//No message, create it
		if( !empty( $message ) && empty( $_SESSION[$name] ) )
        {
	        if( !empty( $_SESSION[$name] ) )
	        {
		        unset( $_SESSION[$name] );
	        }
	        if( !empty( $_SESSION[$name.'_class'] ) )
	        {
		        unset( $_SESSION[$name.'_class'] );
	        }

	        $_SESSION[$name] = $message;
	        $_SESSION[$name.'_class'] = $class;
        }
        //Message exists, display it
        elseif( !empty( $_SESSION[$name] ) && empty( $message ) )
        {
	        $class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'uk-alert-success';
	        echo '<div class="'.$class.'" uk-alert> <a class="uk-alert-close" uk-close></a> <p>'.$_SESSION[$name].'</p></div>';
	        unset($_SESSION[$name]);
	        unset($_SESSION[$name.'_class']);
        }
    }
}