<?php
namespace TypeRocket\Http;

use TypeRocket\Controllers\Controller;
use TypeRocket\Core\Resolver;
use TypeRocket\Models\Model;
use TypeRocket\Utility\Str;

/**
 * Class Router
 *
 * Run proper controller based on request.
 *
 * @package TypeRocket\Http\Middleware
 */
class Router
{
    public $returned = [];
    protected $request = null;
    protected $response = null;
    /** @var Controller  */
    protected $controller;
    public $middleware = [];
    public $action;
    public $routerArgs = [];

    /**
     * Router constructor.
     *
     * @param \TypeRocket\Http\Request $request
     * @param \TypeRocket\Http\Response $response
     * @param string $action_method
     */
    public function __construct( Request $request, Response $response, $action_method = 'GET' )
    {
        $this->request = $request;
        $this->response = $response;
        $this->action = $this->getAction( $action_method );
        $resource = $this->request->getResource();
        $resource = Str::camelize( $resource );
        $controller  = "\\" . TR_APP_NAMESPACE . "\\Controllers\\{$resource}Controller";

        if ( class_exists( $controller ) ) {
            $this->controller = $controller = new $controller( $this->request, $this->response);

            if ( ! $controller instanceof Controller || ! method_exists( $controller, $this->action ) ) {
                $this->response->setMessage('Something went wrong');
                $this->response->exitAny(405);
            }

            $this->routerArgs = $this->request->getRouterArgs();
            $this->middleware = $this->controller->getMiddleware();

        } else {
            wp_die('Missing controller: ' . $controller );
        }
    }

    /**
     * Handle routing to controller
     */
    public function handle() {
        $action = $this->action;
        $controller = $this->controller;
        $params = (new \ReflectionMethod($controller, $this->action))->getParameters();

        if( $params ) {

            $args = [];
            $vars = $this->routerArgs;

            foreach ($params as $index => $param ) {
                $varName = $param->getName();
                $class = $param->getClass();
                if ( $class ) {

                    $instance = (new Resolver)->resolve( $param->getClass()->name );

                    if( $instance instanceof Model ) {
                        $injectionColumn = $instance->getRouterInjectionColumn();
                        if( isset($vars[ $injectionColumn ]) ) {
                            $instance = $instance->findFirstWhereOrDie($injectionColumn, $vars[ $injectionColumn ] );
                        }
                    }

                    $args[$index] = $instance;
                } elseif( isset($vars[$varName]) ) {
                    $args[$index] = $vars[$varName];
                } else {
                    $args[$index] = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
                }
            }

            $this->returned = call_user_func_array( [$controller, $action], $args );
        } else {
            $this->returned = $controller->$action();
        }
    }

    /**
     * Get the middleware group
     *
     * @return array
     */
    public function getMiddlewareGroups() {
        $groups = [];

        foreach ($this->middleware as $group ) {
            if (array_key_exists('group', $group)) {
                $use = null;

                if( ! array_key_exists('except', $group) && ! array_key_exists('only', $group) ) {
                    $use = $group['group'];
                }

                if (array_key_exists('except', $group) && ! in_array($this->action, $group['except'])) {
                    $use = $group['group'];
                }

                if (array_key_exists('only', $group) && in_array($this->action, $group['only'])) {
                    $use = $group['group'];
                }

                if($use) {
                    $groups[] = $use;
                }
            }
        }

        return $groups;
    }

    /**
     * Get the controller action to call
     *
     * @param string $action_method
     *
     * @return null|string
     */
    protected function getAction( $action_method = 'GET' ) {
        $request = $this->request;

        $method = $request->getMethod();
        $action = null;
        switch ( $request->getAction() ) {
            case 'add' :
                if( $method == 'POST' ) {
                    $action = 'create';
                } else {
                    $action = 'add';
                }
                break;
            case 'create' :
                if( $method == 'POST' ) {
                    $action = 'create';
                }
                break;
            case 'edit' :
                if( $method == 'PUT' ) {
                    $action = 'update';
                } else {
                    $action = 'edit';
                }
                break;
            case 'update' :
                if( $method == 'PUT' ) {
                    $action = 'update';
                }
                break;
            case 'delete' :
                if( $method == 'DELETE' ) {
                    $action = 'destroy';
                } else {
                    $action = 'delete';
                }
                break;
            case 'index' :
                if( $method == 'GET' ) {
                    $action = 'index';
                }
                break;
            case 'show' :
                if( $method == 'GET' ) {
                    $action = 'show';
                }
                break;
            default :
                if($action_method == $method ) {
                    $action = $request->getAction();
                }
                break;
        }

        return $action;
    }

}