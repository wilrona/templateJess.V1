<?php

namespace TypeRocket\Http;

use TypeRocket\Utility\Validator;

class Fields extends \ArrayObject
{

    protected $fillable = [];
    protected $rules = [];

    /**
     * Load commands
     *
     * @param array $fields
     */
    public function __construct( $fields = [] ) {

        if( empty($fields) ) {
            $fields = (new Request())->getFields();
        }

        $this->exchangeArray( $fields );
    }

    /**
     * Get fillable
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Set fillable
     *
     * @param array $fillable
     */
    public function setFillable(array $fillable)
    {
        $this->fillable = $fillable;
    }

    /**
     * Validate fields
     *
     * @param array|null $rules
     * @param $modelClass
     *
     * @return \TypeRocket\Utility\Validator
     * @throws \Exception
     */
    public function validate($rules = null, $modelClass = null)
    {
        if( ! $rules ) {
            $rules = $this->rules;
        }

        if( empty($rules) ) {
            throw new \Exception('No options for validator set.');
        }

        return new Validator($rules, $this->getArrayCopy(), $modelClass);
    }

}