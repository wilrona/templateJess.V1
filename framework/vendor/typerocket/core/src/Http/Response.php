<?php
namespace TypeRocket\Http;

use TypeRocket\Elements\Notice;

/**
 * Class Response
 *
 * The Response class is not designed for PSR-7
 *
 * This class is designed to give hooks for the json
 * response sent back by the TypeRocket AJAX REST
 * API.
 *
 * @package TypeRocket\Http
 */
class Response {

    private $message = 'No Message Set';
    private $messageType = 'success';
    private $redirect = false;
    private $status = 200;
    private $flash = true;
    private $blockFlash = false;
    private $errors = [];
    private $data = [];

    /**
     * Set HTTP status code
     *
     * @param $status
     *
     * @return $this
     */
    public function setStatus( $status )
    {
        status_header( (int) $status );
        $this->status = (int) $status;

        return $this;
    }

    /**
     * Set message property
     *
     * This is the message seen in the flash alert.
     *
     * @param $message
     *
     * @return $this
     */
    public function setMessage( $message )
    {
        $this->message = (string) $message;

        return $this;
    }

    /**
     * Set redirect
     *
     * Redirect the user to a new url. This only works when using AJAX
     * REST API on a Form.
     *
     * @param $url
     *
     * @return $this
     */
    public function setRedirect( $url )
    {
        $this->redirect = $url;

        return $this;
    }

    /**
     * Set Flash
     *
     * Set if the flash message should be shown on the front end.
     *
     * @param bool|true $flash
     *
     * @return $this
     */
    public function setFlash( $flash = true )
    {
        $this->flash = (bool) $flash;

        return $this;
    }

    /**
     * Set Errors
     *
     * Set errors to help front-end developers
     *
     * @param array $errors
     *
     * @return $this
     */
    public function setErrors( $errors )
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Get Errors
     *
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Get Error by key
     *
     * @param $key
     *
     * @return array
     */
    public function getError($key) {

        $error = null;

        if(array_key_exists($key, $this->errors)) {
            $error = $this->errors[$key];
        }

        return $error;
    }

    /**
     * Set Error by key
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setError($key, $value) {
        $this->errors[$key] = $value;

        return $this;
    }

    /**
     * Remove Error by key
     *
     * @param $key
     *
     * @return $this
     */
    public function removeError($key) {

        if(array_key_exists($key, $this->errors)) {
            unset($this->errors[$key]);
        }

        return $this;

    }

    /**
     * Set Data by key
     *
     * Set the data to return for front-end developers. This should
     * be data used to describe what was updated or created for
     * example.
     *
     * @param $key
     * @param $data
     */
    public function setData( $key, $data ) {
        $this->data[$key] = $data;
    }

    /**
     * Get HTTP status
     *
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Get message
     *
     * Get the message used in the flash alert on front-end
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Get Data
     *
     * @param null $key
     *
     * @return array
     */
    public function getData( $key = null ) {
        if( array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $this->data;
    }

    /**
     * Get Redirect
     *
     * Get redirect URL used by the AJAX REST API
     *
     * @return bool
     */
    public function getRedirect() {
        return $this->redirect;
    }

    /**
     * Get Flash
     *
     * Get the flash property to see if the front-end should
     * flash the message.
     *
     * @return bool
     */
    public function getFlash() {
        return $this->flash;
    }

    /**
     * Block Flash
     *
     * Block the flashing no matter what.
     *
     * @return bool
     */
    public function blockFlash() {
        return $this->blockFlash = true;
    }

    /**
     * Get Response Properties
     *
     * Return the private properties that make up the response
     *
     * @return array
     */
    public function getResponseArray() {
        $vars = get_object_vars($this);
        return $vars;
    }

    /**
     * @param $data
     *
     * @return Response $this
     */
    public function with( $data ) {

        if( !empty( $data ) ) {
            $cookie = new Cookie();
            $cookie->setTransient('tr_redirect_data', $data);
        }

        return $this;
    }

    /**
     * @param $fields
     *
     * @return Response $this
     */
    public function withFields( $fields ) {

        if( $fields instanceof Fields) {
            $fields = $fields->getArrayCopy();
        }

        if( !empty( $fields ) ) {
            $cookie = new Cookie();
            $cookie->setTransient('tr_old_fields', $fields);
        }

        return $this;
    }

    /**
     * @param $message
     * @param $type
     *
     * @return \TypeRocket\Http\Response $this
     */
    public function flashNext($message, $type = 'success')
    {
        if( ! $this->blockFlash && ! headers_sent() ) {
            $this->flash       = true;
            $this->message     = $message;
            $this->messageType = strtolower($type);

            $cookie = new Cookie();
            $data = [
                'type' => $this->messageType,
                'message' => $this->message,
            ];

            if(empty($_POST['_tr_ajax_request'])) {
                $cookie->setTransient('tr_admin_flash', $data);
            }
        }

        return $this;
    }

    public function flashNow($message, $type)
    {
        if( ! $this->blockFlash ) {
            $this->flash       = true;
            $this->message     = $message;
            $this->messageType = strtolower($type);

            $data = [
                'type' => $this->messageType,
                'message' => $this->message,
            ];

            add_action( 'admin_notices', \Closure::bind(function() use ($data) {
                Notice::dismissible($data);
            }, $this));
        }

        return $this;
    }

    /**
     * Exit
     *
     * @param int $code
     */
    public function exitAny( $code = 500 ) {
        if( ! empty($_POST['_tr_ajax_request']) ) {
            $this->exitJson($code);
        } else {
            $this->exitMessage($code);
        }
    }

    /**
     * Exit with JSON dump
     *
     * @param int $code
     */
    public function exitJson( $code = 500 )
    {
        $this->setStatus($code);
        wp_send_json( $this->getResponseArray() );
        die();
    }

    /**
     * Exit with message
     *
     * @param int $code
     */
    public function exitMessage( $code = 500 )
    {
        $this->setStatus($code);
        wp_die($this->message);
    }

    /**
     * Exit with 404 Page
     */
    public function exitNotFound()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 );
        exit();
    }

}