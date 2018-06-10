<?php
namespace TypeRocket\Register;

use TypeRocket\Core\Config;
use TypeRocket\Models\WPPost;

class Registry
{

    public static $collection = [];
    public static $postTypes = [
        'post' => ['post', 'posts'],
        'page' => ['page', 'pages']
    ];

    public static $taxonomies = [
        'category' => ['category', 'categories'],
        'post_tag' => ['tag', 'tags']
    ];

    /**
     * Add a post type resource
     *
     * @param string $id post type id
     * @param array $resource resource name ex. posts, pages, books
     */
    public static function addPostTypeResource($id, $resource = []) {
        self::$postTypes[$id] = $resource;
    }

    /**
     * Get the post type resource
     *
     * @param $id
     *
     * @return null
     */
    public static function getPostTypeResource($id) {
        return ! empty(self::$postTypes[$id]) ? self::$postTypes[$id] : null;
    }

    /**
     * Get the taxonomy resource
     *
     * @param $id
     *
     * @return null
     */
    public static function getTaxonomyResource($id) {
        return ! empty(self::$taxonomies[$id]) ? self::$taxonomies[$id] : null;
    }

    /**
     * Add a taxonomy resource
     *
     * @param string $id post type id
     * @param array $resource resource name ex. posts, pages, books
     */
    public static function addTaxonomyResource($id, $resource = []) {
        self::$taxonomies[$id] = $resource;
    }

    /**
     * Add Registrable objects to collection
     *
     * @param null|Registrable|string $obj
     */
    public static function addRegistrable( $obj = null )
    {
        if ( $obj instanceof Registrable) {
            self::$collection[] = $obj;
        }
    }

    /**
     * Loop through each Registrable and add hooks automatically
     */
    public static function initHooks()
    {
        $collection = [];
        $later = [];

        if(empty(self::$collection)) {
            return;
        }

        foreach(self::$collection as $obj) {
            if ( $obj instanceof Registrable) {
                $collection[] = $obj;
                $use = $obj->getApplied();
                foreach($use as $objUsed) {
                    if( ! in_array($objUsed, $collection) && ! $objUsed instanceof Page) {
                        $later[] = $obj;
                        array_pop($collection);
                        break 1;
                    }
                }

                if ($obj instanceof Page && ! empty( $obj->parent ) ) {
                    $later[] = $obj;
                    array_pop($collection);
                }
            }
        }
        $collection = array_merge($collection, $later);

        foreach ($collection as $obj) {
            if ($obj instanceof Taxonomy) {
                add_action( 'init', [$obj, 'register']);

                self::taxonomyFormContent($obj);

            } elseif ($obj instanceof PostType) {
                /** @var PostType $obj */
                add_action( 'init', [$obj, 'register']);

                if (is_string( $obj->getTitlePlaceholder() )) {
                    add_filter( 'enter_title_here', function($title) use ($obj) {
                        global $post;

                        if(!empty($post)) {
                            if ( $post->post_type == $obj->getId() ) {
                                return $obj->getTitlePlaceholder();
                            }
                        }

                        return $title;

                    } );
                }

                if( !empty($obj->getArchiveQuery()) ) {
                    add_action('pre_get_posts', \Closure::bind(function( \WP_Query $main_query ) {
                        if($main_query->is_main_query() && $main_query->is_post_type_archive($this->getId())) {
                            $query = $this->getArchiveQuery();
                            foreach ($query as $key => $value) {
                                $main_query->set($key, $value);
                            }
                        }
                    }, $obj));
                }

                self::setPostTypeColumns($obj);
                self::postTypeFormContent($obj);

            } elseif ($obj instanceof MetaBox) {
                add_action( 'admin_init', [$obj, 'register']);
                add_action( 'add_meta_boxes', [$obj, 'register']);
            } elseif ($obj instanceof Page) {
                if($obj->useController) {
                    add_action( 'admin_init', [$obj, 'respond'] );
                }

                add_action( 'admin_menu', [$obj, 'register']);
            }
        }
    }

    /**
     * Add taxonomy form hooks
     *
     * @param \TypeRocket\Register\Taxonomy $obj
     */
    public static function taxonomyFormContent( Taxonomy $obj ) {

        $callback = function( $term, $type, $obj )
        {
            /** @var Taxonomy $obj */
            if ( $term == $obj->getId() || $term->taxonomy == $obj->getId() ) {
                $func = 'add_form_content_' . $obj->getId() . '_' . $type;
                echo '<div class="typerocket-container typerocket-taxonomy-style">';
                $form = $obj->getForm( $type );
                if (is_callable( $form )) {
                    call_user_func( $form, $term );
                } elseif (function_exists( $func )) {
                    call_user_func( $func, $term );
                } elseif ( Config::getDebugStatus() == true) {
                    echo "<div class=\"tr-dev-alert-helper\"><i class=\"icon tr-icon-bug\"></i> Add content here by defining: <code>function {$func}() {}</code></div>";
                }
                echo '</div>';
            }
        };

        if ($obj->getForm( 'main' )) {
            add_action( $obj->getId() . '_edit_form', function($term) use ($obj, $callback) {
                $type = 'main';
                call_user_func_array($callback, [$term, $type, $obj]);
            }, 10, 2 );

            add_action( $obj->getId() . '_add_form_fields', function($term) use ($obj, $callback) {
                $type = 'main';
                call_user_func_array($callback, [$term, $type, $obj]);
            }, 10, 2 );
        }
    }

    /**
     * Add post type form hooks
     *
     * @param PostType $obj
     */
    public static function postTypeFormContent( PostType $obj) {

        /**
         * @param \WP_Post $post
         * @param string $type
         * @param PostType $obj
         */
        $callback = function( $post, $type, $obj )
        {
            if ($post->post_type == $obj->getId()) {
                $func = 'add_form_content_' . $obj->getId() . '_' . $type;
                echo '<div class="typerocket-container">';

                $form = $obj->getForm( $type );
                if (is_callable( $form )) {
                    call_user_func( $form );
                } elseif (function_exists( $func )) {
                    call_user_func( $func, $post );
                } elseif (Config::getDebugStatus() == true) {
                    echo "<div class=\"tr-dev-alert-helper\"><i class=\"icon tr-icon-bug\"></i> Add content here by defining: <code>function {$func}() {}</code></div>";
                }
                echo '</div>';
            }
        };

        // edit_form_top
        if ($obj->getForm( 'top' )) {
            add_action( 'edit_form_top', function($post) use ($obj, $callback) {
                $type = 'top';
                call_user_func_array($callback, [$post, $type, $obj]);
            } );
        }

        // edit_form_after_title
        if ($obj->getForm( 'title' )) {
            add_action( 'edit_form_after_title', function($post) use ($obj, $callback) {
                $type = 'title';
                call_user_func_array($callback, [$post, $type, $obj]);
            } );
        }

        // edit_form_after_editor
        if ($obj->getForm( 'editor' )) {
            add_action( 'edit_form_after_editor', function($post) use ($obj, $callback) {
                $type = 'editor';
                call_user_func_array($callback, [$post, $type, $obj]);
            } );
        }

        // dbx_post_sidebar
        if ($obj->getForm( 'bottom' )) {
            add_action( 'dbx_post_sidebar', function($post) use ($obj, $callback) {
                $type = 'bottom';
                call_user_func_array($callback, [$post, $type, $obj]);
            } );
        }

    }

    /**
     * Add post type admin table columns hooks
     *
     * @param \TypeRocket\Register\PostType $post_type
     */
    public static function setPostTypeColumns( PostType $post_type)
    {
        $pt = $post_type->getId();
        $new_columns = $post_type->getColumns();

        add_filter( "manage_edit-{$pt}_columns" , function($columns) use ($new_columns) {
            foreach ($new_columns as $key => $new_column) {
                if($new_column == false && array_key_exists($key, $columns)) {
                    unset($columns[$key]);
                } else {
                    $columns[$new_column['field']] = $new_column['label'];
                }
            }

            return $columns;
        });

        add_action( "manage_{$pt}_posts_custom_column" , function($column, $post_id) use ($new_columns) {
            global $post;

            foreach ($new_columns as $new_column) {
                if(!empty($new_column['field']) && $column == $new_column['field']) {
                    $data = [
                        'column' => $column,
                        'field' => $new_column['field'],
                        'post' => $post,
                        'post_id' => $post_id
                    ];
                    $post_temp = (new WPPost());
                    $value = $post_temp
                        ->setProperty($post_temp->getIdColumn(), $post_id)
                        ->getBaseFieldValue($new_column['field']);

                    call_user_func_array($new_column['callback'], [$value, $data]);
                }
            }
        }, 10, 2);

        foreach ($new_columns as $new_column) {
            if(!empty($new_column['sort'])) {
                add_filter( "manage_edit-{$pt}_sortable_columns", function($columns) use ($new_column) {
                    $columns[$new_column['field']] = $new_column['field'];
                    return $columns;
                } );

                add_action( 'load-edit.php', function() use ($pt, $new_column) {
                    add_filter( 'request', function( $vars ) use ($pt, $new_column) {
                        if ( isset( $vars['post_type'] ) && $pt == $vars['post_type'] ) {
                            if ( isset( $vars['orderby'] ) && $new_column['field'] == $vars['orderby'] ) {

                                if( ! in_array($new_column['field'], (new WPPost())->getBuiltinFields())) {
                                    if(!empty($new_column['order_by'])) {

                                        switch($new_column['order_by']) {
                                            case 'number':
                                            case 'num':
                                            case 'int':
                                                $new_vars['orderby'] = 'meta_value_num';
                                                break;
                                            case 'decimal':
                                            case 'double':
                                                $new_vars['orderby'] = 'meta_value_decimal';
                                                break;
                                            case 'date':
                                                $new_vars['orderby'] = 'meta_value_date';
                                                break;
                                            case 'datetime':
                                                $new_vars['orderby'] = 'meta_value_datetime';
                                                break;
                                            case 'time':
                                                $new_vars['orderby'] = 'meta_value_time';
                                                break;
                                            case 'string':
                                            case 'str':
                                                break;
                                            default:
                                                $new_vars['orderby'] = $new_column['order_by'];
                                                break;
                                        }
                                    }
                                    $new_vars['meta_key'] = $new_column['field'];
                                } else {
                                    $new_vars = [ 'orderby' => $new_column['field'] ];
                                }

                                $vars = array_merge( $vars, $new_vars );
                            }
                        }

                        return $vars;
                    });
                } );
            }
        }
    }
}
