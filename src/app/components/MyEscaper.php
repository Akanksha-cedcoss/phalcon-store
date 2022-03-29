<?php

namespace App\components;

use Exception;
use Phalcon\Escaper;


/**
 * escaper class return sanitize outputs
 */
class MyEscaper
{
    public function __construct()
    {
        $this->escaper = new Escaper();
    }
    public function sanitize($html)
    {
        // die($html);
        try {
            // echo $html;
            return $this->escaper->escapeHtml($html);
        } catch (Exception $e) {
            print_r($e);
        }
    }
}
