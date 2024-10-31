<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class RWSAttribute {
    public $field_name;
    public $display;
    public function __construct($field_name, $display)
    {
        $this->field_name = $field_name;
        $this->display = $display;
    }
    public  function show($formlabel){
        echo '<div class="col-sm-2">';
        echo "<label>$this->display</label>";
        echo '</div>';
    }
}
?>