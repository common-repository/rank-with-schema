<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class RWSHidden extends RWSAttribute {
    public $value;
    public $field_name;
    public function __construct($att)
    {
        parent::__construct($att['field_name'], "");
    }
    public function show($formLabel)
    {
        ?>
        <input id="<?=$this->field_name?>", type="hidden", value="<?=$this->value?>">
        <?php
    }
}