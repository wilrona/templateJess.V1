<?php
namespace TypeRocket\Elements\Fields;

use \TypeRocket\Html;

class Submit extends Field
{

    /**
     * Run on construction
     */
    protected function init()
    {
        $this->setType( 'submit' );
    }

    /**
     * Covert Submit to HTML string
     */
    public function getString()
    {
        $name = '_tr_submit_form';
        $value = esc_attr( $this->getAttribute('value') );
        $this->removeAttribute('value');
        $this->removeAttribute('name');
        $this->appendStringToAttribute('class', ' button button-primary');

        $generator = new Html\Generator();
        return $generator->newInput( 'submit', $name, $value, $this->getAttributes() )->getString();
    }

}