<?php

namespace wpai_gravityforms_add_on\gf\fields;

/**
 * Class fieldTextarea
 *
 * @package wpai_gravityforms_add_on\gf\fields
 */
class fieldTextarea extends Field {

    /**
     *  Field type key
     */
    public $type = 'textarea';

    /**
     * Parse field data.
     *
     * @param $xpath
     * @param $parsingData
     * @param array $args
     */
    public function parse( $xpath, $parsingData, $args = array() ) {
        parent::parse( $xpath, $parsingData, $args );
        $values = $this->getByXPath( $xpath );
        $this->setOption('values', $values);
    }

    /**
     * @param $importData
     * @param array $args
     * @return mixed
     */
    public function import( $importData, $args = array() ) {
        $isUpdated = parent::import($importData, $args);
        if ( ! $isUpdated ) {
            return FALSE;
        }
	    return $this->getFieldValue();
    }
}