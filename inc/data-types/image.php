<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class RWSImage extends RWSAttribute {
    public $css_class;
    public $form_group_class;
    public $validation;
    public $value;
    public $col_span;
    public $validation_msg;
    public $fieldDescription;
    public function __construct($att)
    {
        parent::__construct($att["field_name"], $att["display"]);
        $this->css_class = $att["css_class"];
        $this->validation = $att["validation"];
        $this->value = $att["value"];
        $this->form_group_class = $att["form_group_class"];
        $this->placeHolder = $att["placeholder"];
        $this->col_span = $att["col_span"];
        $this->validation_msg = isset($att['validation_msg'])? $att['validation_msg'] : "";
        $this->fieldDescription = isset($att['field_description'])? $att['field_description'] : "";
    }
    public function show($formLabel){
        ?>
        <div class="form-group <?=$this->form_group_class?> padding-zero">
            <?=parent::show($formLabel)?>
            <div  class = " col-sm-8 " >
                <input type="text" class="<?=$this->css_class?>"
                       name="<?=$this->field_name?>"
                       id="<?=$this->field_name?>"
                    <?=$this->validation?>
                       placeholder="<?=$this->placeHolder?>"
                       data-error="<?=$this->validation_msg?>"
                       value="<?=$this->value?>"
                >
                <p class="custom-para col-sm-12 padding-zero"><?=$this->fieldDescription?></p>
                <div class="help-block with-errors"></div>
                <input id="<?=$this->field_name?>_button" type="button" class="button" value="Upload Image" onclick="uploadImage()">
            </div>
        </div>

        <?php
    }
}