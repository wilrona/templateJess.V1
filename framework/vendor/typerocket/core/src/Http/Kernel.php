<?php
namespace TypeRocket\Http;

abstract class Kernel
{

    public $request;
    public $response;

    /** @var Router */
    public $router;
    public $group;

    protected $middleware = [];

    /**
     * Handle Middleware
     *
     * Run through middleware based on global and resource. You can create
     * a class XKernel to override this Kernel but it should extend this
     * Kernel.
     *
     * @param Request $request
     * @param Response $response
     * @param string $group selected middleware group
     * @param string $action_method
     */
    public function __construct(Request $request, Response $response, $group = 'hookGlobal', $action_method = 'GET' ) {

        $this->response = $response;
        $this->request = $request;
        $this->group = $group;

        $resource = strtolower( $request->getResource() );

        if(array_key_exists($resource, $this->middleware)) {
            $resourceMiddleware = $this->middleware[$resource];
        } else {
            $resourceMiddleware = $this->middleware['noResource'];
        }

        $client = $this->router = new Router($request, $response, $action_method);
        $middleware = $this->compileMiddleware($resourceMiddleware);

        foreach($middleware as $class) {
            $client = new $class($request, $response, $client);
        }

        $client->handle();

    }

    /**
     * Compile middleware from controller, router and kernel
     *
     * @param $middleware
     *
     * @return mixed|void
     */
    public function compileMiddleware( $middleware ) {

        $routerWare = [];
        $groups = $this->router->getMiddlewareGroups();
        foreach( $groups as $group ) {
            $routerWare[] = $this->middleware[$group];
        }

        if( !empty($routerWare) ) {
            $routerWare = call_user_func_array('array_merge', $routerWare);
        }

        $middleware = array_merge( $middleware, $this->middleware[$this->group], $routerWare);
        $middleware = array_reverse($middleware);
        return apply_filters('tr_kernel_middleware', $middleware, $this->request, $this->group);
    }

}