<?php

namespace TypeRocket\Register;

use TypeRocket\Core\Config;
use TypeRocket\Http\Request;
use TypeRocket\Http\Responders\ResourceResponder;
use TypeRocket\Utility\Sanitize;
use TypeRocket\Elements\Icons;
use TypeRocket\Template\View;
use TypeRocket\Utility\Str;

class Page extends Registrable
{

    public $title = 'Admin Page Title';
    public $resource = 'admin';
    public $action = 'index';
    public $actionMap = [];
    public $icon = null;
    public $pages = [];
    /** @var null|Page parent page */
    public $parent = null;
    public $showTitle = true;
    public $showMenu = true;
    public $showAddNewButton = false;
    public $useController = false;
    public $builtin = [
        'tools' => 'tools.php',
        'dashboard' => 'index.php',
        'media' => 'upload.php',
        'appearance' => 'themes.php',
        'plugins' => 'plugins.php',
        'users' => 'users.php',
        'settings' => 'options-general.php'
    ];

    /**
     * Page constructor.
     *
     * @param string $resource set the resource or section the page belongs to
     * @param string $action set the action the page is responsible for
     * @param string $title set the title of the page and menu
     * @param array $settings menu, capability, position, view, slug
     */
    public function __construct($resource, $action, $title, array $settings = [])
    {
        $this->title    = $title;
        $this->resource = Sanitize::underscore( $resource );
        $this->id       = Sanitize::underscore( $this->title );
        $this->action   = Sanitize::underscore( $action );
        $this->args     = array_merge( [
            'menu' => $this->title,
            'capability' => false,
            'inherit_capability' => true,
            'position' => 99,
            'view_file' => null,
            'slug' => $this->resource . '_' . $this->action,
        ], $settings );

    }

    /**
     * Set the post type menu icon
     *
     * Add the CSS needed to create the icon for the menu
     *
     * @param $name
     *
     * @return $this
     */
    public function setIcon( $name )
    {
        $name       = strtolower( $name );
        $icons      = Config::getIcons();

        if( ! $icons instanceof Icons ) {
            $icons = new Icons();
        }

        $this->icon = !empty($icons[$name]) ? $icons[$name] : null;
        if( ! $this->icon ) {
            return $this;
        }

        add_action( 'admin_head', \Closure::bind( function() use ($icons) {
            $slug = $this->args['slug'];
            $icon = $this->getIcon();
            echo "
            <style type=\"text/css\">
                #adminmenu #toplevel_page_{$slug} .wp-menu-image:before {
                    font: {$icons->fontWeight} {$icons->fontSize} {$icons->fontFamily} !important;
                    content: '{$icon}';
                    speak: none;
                    top: 2px;
                    position: relative;
                    -webkit-font-smoothing: antialiased;
                }
            </style>";
        }, $this ) );

        return $this;
    }

    /**
     * Get the post type icon
     *
     * @return null
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * Get the slug
     *
     * @return mixed
     */
    public function getSlug() {
        return $this->args['slug'];
    }

    /**
     * Set the slug
     *
     * @param $slug
     *
     * @return $this
     */
    public function setSlug( $slug ) {
        $this->args['slug'] = $slug;

        return $this;
    }

    /**
     * Set the parent page
     *
     * @param \TypeRocket\Register\Page $parent
     *
     * @return $this
     */
    public function setParent( Page $parent ) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the parent page
     *
     * @return null|\TypeRocket\Register\Page
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title
     *
     * @param $title
     *
     * @return $this
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Remove title from page
     *
     * @return $this
     */
    public function removeTitle()
    {
        $this->showTitle = false;

        return $this;
    }

    /**
     * Get admin page
     *
     * Get the page such as admin.php tools.php upload.php that Page belongs to
     *
     * @return mixed|string
     */
    public function getAdminPage()
    {
        $resource = $this->resource;
        return !empty($this->builtin[$resource]) ? $this->builtin[$resource] : 'admin.php';
    }

    /**
     * Get URL for admin page
     *
     * @param array $params
     *
     * @return string
     */
    public function getUrl( $params = [] ) {
        $query = http_build_query( array_merge(
            [ 'page' => $this->getSlug() ],
            $params
        ) );
        $url = admin_url() . $this->getAdminPage() . '?' . $query;

        return $url;
    }

    /**
     * Get URL for admin page with existing params in URL
     *
     * @param array $params
     *
     * @return string
     */
    public function getUrlWithParams( $params = [] ) {
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $request_params);

        $query = http_build_query( array_merge(
            [ 'page' => $this->getSlug() ],
            $request_params,
            $params
        ) );
        $url = admin_url() . $this->getAdminPage() . '?' . $query;

        return $url;
    }

    /**
     * Remove menu
     *
     * @return $this
     */
    public function removeMenu()
    {
        $this->showMenu = false;

        return $this;
    }

    /**
     * Show new button
     *
     * @param bool $url
     *
     * @return $this
     */
    public function addNewButton( $url = true ) {
        $this->showAddNewButton = $url;

        return $this;
    }

    /**
     * Make the page use a TypeRocket controller and routing
     *
     * @return $this
     */
    public function useController()
    {
        $this->useController = true;

        return $this;
    }

    /**
     * Get Capability
     *
     * @return string
     */
    protected function getCapability() {
        $default_capability = 'administrator';
        $capability = $this->args['capability'] ? $this->args['capability'] : $default_capability;

        if( $this->getParent() && $this->args['inherit_capability'] && ! $this->args['capability'] ) {
            $parent_capability = $this->getParent()->getArgument('capability');
            $capability = $parent_capability ? $parent_capability : $default_capability;
        }

        return $capability;
    }

    /**
     * Register with WordPress
     *
     * Override this in concrete classes
     *
     * @return $this
     */
    public function register()
    {
        $menu_title = $this->args['menu'];
        $capability = $this->getCapability();
        $slug = $this->getSlug();
        $position = $this->args['position'];

        $callback = function() {

            $url = $action = '';

            if( $this->parent ) {
                $all_pages = $this->parent->pages;
            } else {
                $all_pages = $this->pages;
            }

            do_action('tr_page_start_view_' . $this->id, $this);
            echo '<div id="typerocket-admin-page" class="wrap typerocket-container">';

            foreach ($all_pages as $page) {
                /** @var Page $page */
                if($page->action == 'add') {
                    $url =  $page->getUrl();
                    break;
                }
            }

            if( $url && $this->showAddNewButton ) {
                if( is_string($this->showAddNewButton) ) {
                    $url = $this->showAddNewButton;
                }
                $action = ' <a href="'.$url.'" class="page-title-action">Add New</a>';
            }

            if( $this->showTitle ) {
                echo '<h1 class="tr-admin-page-title">'. $this->title . $action . '</h1>';
            }

            echo '<div>';

            if( file_exists($this->args['view_file']) ) {
                /** @noinspection PhpIncludeInspection */
                include( $this->args['view_file'] );
            } elseif (file_exists( View::$page )) {
                $this->loadView();
            } elseif( Config::getDebugStatus() == true ) {
                echo "<div class=\"tr-dev-alert-helper\"><i class=\"icon tr-icon-bug\"></i> Add content here by creating or setting a view.</div>";
            }
            echo '</div></div>';
            do_action('tr_page_end_view_' . $this->id, $this);

        };

        if( array_key_exists( $this->resource, $this->builtin ) ) {
            add_submenu_page( $this->builtin[$this->resource] , $this->title, $menu_title, $capability, $slug, \Closure::bind( $callback, $this ) );
        } elseif( ! $this->parent ) {
            add_menu_page( $this->title, $menu_title, $capability, $slug, \Closure::bind( $callback, $this ), '', $position);
            if( $this->hasShownSubPages() ) {
                add_submenu_page( $slug , $this->title, $menu_title, $capability, $slug );
            }
        } else {
            $parent_slug = $this->parent->getSlug();
            add_submenu_page( $parent_slug, $this->title, $menu_title, $capability, $slug, \Closure::bind( $callback, $this ) );

            if( ! $this->showMenu ) {
                add_action( 'admin_head', function() use ($parent_slug, $slug) {
                    remove_submenu_page( $parent_slug, $slug );
                } );
            }
        }

        return $this;
    }

    /**
     * Add Admin Bar Menu Item
     *
     * @param $id
     * @param null $title
     * @param string $parent_id
     * 
     * @return $this
     */
    public function adminBar( $id, $title = null, $parent_id = 'site-name')
    {
        add_action('admin_bar_menu', \Closure::bind(function() use ($parent_id, $title, $id) {
            if( current_user_can( $this->getCapability() ) ) {
                /** @var $wp_admin_bar \WP_Admin_Bar */
                global $wp_admin_bar;
                $link = $this->getUrl();
                $wp_admin_bar->add_menu([
                    'id'     => $id,
                    'parent' => $parent_id,
                    'meta'   => [
                        'class' => 'custom-page-admin-bar-item',
                    ],
                    'title'  => $title ? $title : $this->getTitle(),
                    'href'   => $link
                ]);
            }
        }, $this), 80);
        
        return $this;
    }

    /**
     * Map Action
     *
     * Use to page controller actions for different request methods
     *
     * @param string $method use the string POST, GET, UPDATE, DELETE
     * @param string $action use the action on the controller you want to call
     *
     * @return $this
     */
    public function mapAction($method, $action)
    {
        $this->actionMap[strtoupper($method)] = $action;

        return $this;
    }

    /**
     * Invoked if $useController is true
     */
    public function respond()
    {
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $request_params);

        if( !empty($request_params['page']) &&  $request_params['page'] == $this->getSlug() ) {
            $respond = new ResourceResponder();
            $respond->setResource( $this->resource );
            $respond->setAction( $this->action );
            $form_method = (new Request())->getFormMethod();
            $respond->setActionMethod($form_method);

            if( !empty($this->actionMap[$form_method]) ) {
                $respond->setAction($this->actionMap[$form_method]);
            }

            $args = [];

            if(isset($_GET)) {
                foreach ($_GET as $name => $value) {
                    if( Str::starts('route_', $name) ) {
                        $args[mb_substr($name, 6)] = $value;
                    }
                }
            }

            $respond->respond( $args );
        }
    }

    /**
     * Has shown sub pages
     *
     * @return bool
     */
    public function hasShownSubPages()
    {
        if( ! empty( $this->pages ) ) {
            foreach($this->pages as $page) {
                if( $page->showMenu ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Add page to page
     *
     * @param string|Page $s
     *
     * @return $this
     */
    public function addPage( $s )
    {

        if ( $s instanceof Page && ! in_array( $s, $this->pages )) {
            $this->pages[] = $s;
            $s->setParent( $this );
        } elseif( is_array($s) ) {
            foreach($s as $n) {
                $this->addPage($n);
            }
        }

        return $this;

    }

    protected function loadView() {
        $GLOBALS['_tr_page'] = $this;
        $class = '\\' . TR_APP_NAMESPACE . '\\Models\\' . ucfirst($this->resource);
        if( class_exists( $class ) ) {
            $GLOBALS['_tr_resource'] = new $class;
        }
        unset($class);

        extract( View::$data );
        /** @noinspection PhpIncludeInspection */
        include( View::$page );
    }
}
