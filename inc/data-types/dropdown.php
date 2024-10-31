<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class RWSDropDown extends RWSAttribute {

    public $css_class;
    public $form_group_class;
    public $options = [[]];
    public $value;
    public $col_span;
    public $fieldDescription;
    public function __construct($att)
    {
        parent::__construct($att["field_name"], $att["display"]);
        $this->css_class = $att["css_class"];
        $this->options = $att["options"];
        $this->value = $att["value"];
        $this->form_group_class = $att["form_group_class"];
        $this->col_span = $att["col_span"];
        $this->fieldDescription = isset($att['field_description'])? $att['field_description'] : "";
    }

    public function show($formLabel)
    {
        ?>
        <div class="form-group <?=$this->form_group_class?> padding-zero">
            <?=parent::show($formLabel)?>
            <div  class = " col-sm-8 " >
                <select name="<?=$this->field_name?>" class="<?=$this->css_class?>" id="<?=$this->field_name?>">
                    <?php
                    foreach ($this->options as $op){
                        ?>
                        <option value="<?=$op["value"]?>" <?php if ($this->value==$op["value"]) echo 'selected="selected"';?>> <?=$op["label"]?> </option>
                        <?php
                    }
                    ?>

                </select>
                <p class="custom-para col-sm-12 padding-zero"><?=$this->fieldDescription?></p>
            </div>

        </div>
        <?php
    }
}