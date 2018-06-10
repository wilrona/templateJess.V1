<?php
namespace TypeRocket\Http\Middleware;

/**
 * Class IsUserOrCanEditUsers
 *
 * Validate that is user or can edit users and if the user is not
 * invalidate the response.
 *
 * @package TypeRocket\Http\Middleware
 */
class IsUserOrCanEditUsers extends Middleware
{

    public function handle() {

        if( ! $this->request->isHook() ) {
            $currentUser = wp_get_current_user();
            $user  = get_user_by( 'id', $this->request->getRouterArg('id') );

            if ($user->ID != $currentUser->ID && ! current_user_can( 'edit_users' )) {
                $this->response->setError( 'auth', false );
                $this->response->flashNow( "Sorry, you don't have enough rights.", 'error' );
                $this->response->exitAny(401);
            }
        }

        $this->next->handle();
    }

}