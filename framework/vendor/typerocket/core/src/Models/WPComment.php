<?php
namespace TypeRocket\Models;

use TypeRocket\Exceptions\ModelException;
use TypeRocket\Models\Meta\WPCommentMeta;

class WPComment extends Model
{

    protected $idColumn = 'comment_ID';
    protected $resource = 'comments';

    protected $builtin = [
        'comment_author',
        'comment_author_email',
        'comment_author_url',
        'comment_type',
        'comment_parent',
        'user_id',
        'comment_date',
        'comment_date_gmt',
        'comment_content',
        'comment_karma',
        'comment_approved',
        'comment_agent',
        'comment_author_ip',
        'comment_post_id',
        'comment_id'
    ];

    protected $guard = [
        'comment_id'
    ];

    /**
     * Get Comment Meta
     *
     * @return null|\TypeRocket\Models\Model
     */
    public function meta()
    {
        return $this->hasMany( WPCommentMeta::class, 'comment_id' );
    }

    /**
     * Get comment by ID
     *
     * @param $id
     *
     * @return $this
     */
    public function findById( $id )
    {
        $this->fetchResult( get_comment( $id, ARRAY_A ) );

        return $this;
    }

    /**
     * Create comment from TypeRocket fields
     *
     * @param array|\TypeRocket\Http\Fields $fields
     *
     * @return $this
     * @throws \TypeRocket\Exceptions\ModelException
     */
    public function create( $fields = [] )
    {
        $fields  = $this->provisionFields($fields);
        $builtin = $this->getFilteredBuiltinFields( $fields );

        if ( ! empty( $builtin['comment_post_id'] ) &&
             ! empty( $builtin['comment_content'] )
        ) {
            remove_action( 'wp_insert_comment', 'TypeRocket\Http\Responders\Hook::comments' );
            $comment   = wp_new_comment( $this->caseFieldColumns( wp_slash($builtin) ) );
            add_action( 'wp_insert_comment', 'TypeRocket\Http\Responders\Hook::comments' );

            if ( empty( $comment ) ) {
                throw new ModelException('WPComments not created');
            } else {
                $this->findById($comment);
            }
        } else {
            $this->errors = [
                'Missing post ID `comment_post_id`.',
                'Missing comment content `comment_content`.'
            ];
        }

        $this->saveMeta( $fields );

        return $this;
    }

    /**
     * Update comment from TypeRocket fields
     *
     * @param array|\TypeRocket\Http\Fields $fields
     *
     * @return $this
     * @throws \TypeRocket\Exceptions\ModelException
     */
    public function update( $fields = [] )
    {
        $id = $this->getID();
        if ($id != null) {
            $fields  = $this->provisionFields($fields);
            $builtin = $this->getFilteredBuiltinFields( $fields );

            if ( ! empty( $builtin )) {
                remove_action( 'edit_comment', 'TypeRocket\Http\Responders\Hook::comments' );
                $builtin['comment_id'] = $id;
                $builtin = $this->caseFieldColumns( $builtin );
                $comment = wp_update_comment(  wp_slash($builtin) );
                add_action( 'edit_comment', 'TypeRocket\Http\Responders\Hook::comments' );

                if (empty( $comment )) {
                    throw new ModelException('WPComments not updated');
                }

                $this->findById($id);
            }

            $this->saveMeta( $fields );
        } else {
            $this->errors = ['No item to update'];
        }

        return $this;
    }

    /**
     * Save comment meta fields from TypeRocket fields
     *
     * @param array|\ArrayObject $fields
     *
     * @return $this
     */
    private function saveMeta( $fields )
    {
        $fields = $this->getFilteredMetaFields( $fields );
        if ( ! empty( $fields ) && ! empty( $this->id )) :
            foreach ($fields as $key => $value) :
                if (is_string( $value )) {
                    $value = trim( $value );
                }

                $current_value = get_comment_meta( $this->id, $key, true );

                if (( isset( $value ) && $value !== "" ) && $value !== $current_value) :
                    update_comment_meta( $this->id, $key, wp_slash($value) );
                elseif ( ! isset( $value ) || $value === "" && ( isset( $current_value ) || $current_value === "" )) :
                    delete_comment_meta( $this->id, $key );
                endif;

            endforeach;
        endif;

        return $this;
    }

    /**
     * Format irregular fields
     *
     * @param array|\ArrayObject $fields
     *
     * @return array
     */
    private function caseFieldColumns( $fields )
    {

        if ( ! empty( $fields['comment_post_id'] )) {
            $fields['comment_post_ID'] = (int) $fields['comment_post_id'];
            unset( $fields['comment_post_id'] );
        }

        if ( ! empty( $fields['comment_id'] )) {
            $fields['comment_ID'] = (int) $fields['comment_id'];
            unset( $fields['comment_id'] );
        }

        if ( ! empty( $fields['comment_author_ip'] )) {
            $fields['comment_author_IP'] = $fields['comment_author_ip'];
            unset( $fields['comment_author_ip'] );
        }

        return $fields;

    }

    /**
     * Get base field value
     *
     * Some fields need to be saved as serialized arrays. Getting
     * the field by the base value is used by Fields to populate
     * their values.
     *
     * @param $field_name
     *
     * @return null
     */
    public function getBaseFieldValue( $field_name )
    {
        if (in_array($field_name, $this->builtin)) {
            switch ($field_name) {
                case 'comment_author_ip' :
                    $data = $this->properties['comment_author_IP'];
                    break;
                case 'comment_post_id' :
                    $data = $this->properties['comment_post_ID'];
                    break;
                case 'comment_id' :
                    $data = $this->properties['comment_ID'];
                    break;
                default :
                    $data = $this->properties[$field_name];
                    break;
            }

        } else {
            $data = get_metadata('comment', $this->getID(), $field_name, true);
        }

        return $this->getValueOrNull($data);
    }

}
