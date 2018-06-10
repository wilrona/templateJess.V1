<?php
namespace TypeRocket\Http\Responders;

/**
 * Class Hook
 *
 * Used by core to hook into WordPress API
 *
 * @package TypeRocket\Http\Responders
 */
class Hook {

    /**
     * Respond to posts hooks
     *
     * @param $id
     */
    static public function posts($id) {
        $responder = new PostsResponder();
        $responder->hook = true;
        $responder->respond([ 'id' => $id ]);
    }

    /**
     * Respond to comments posts
     *
     * @param $id
     */
    static public function comments($id) {
        $responder = new CommentsResponder();
        $responder->hook = true;
        $responder->respond([ 'id' => $id ]);
    }

    /**
     * Respond to users hooks
     *
     * @param $id
     */
    static public function users($id) {
        $responder = new UsersResponder();
        $responder->hook = true;
        $responder->respond([ 'id' => $id ]);
    }

    /**
     * Respond to taxonomies hooks
     *
     * @param $term_id
     * @param $term_taxonomy_id
     * @param $taxonomy
     */
    static public function taxonomies($term_id, $term_taxonomy_id, $taxonomy) {
        $responder = new TaxonomiesResponder();
        $responder->taxonomy = $taxonomy;
        $responder->hook = true;
        $responder->respond([ 'id' => $term_id ]);
    }

}