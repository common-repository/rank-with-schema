<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class RWSArrayObj extends RWSAttribute
{
    public $col_span;
    public $type;
    public $objType;
    public $attributes = [];
    public $values;

    public function __construct($att)
    {
        parent::__construct($att['field_name'], "");
        $this->col_span = $att["col_span"];
        $this->type = $att["type"];
        $this->objType = $att["objType"];
        $this->values = $att["value"];

        //print_r($this->values);

        foreach ($att['subAttributes'] as $atti) {
            $obj = new $atti['type']($atti);
            array_push($this->attributes, $obj);
        }
    }

    public function show($formLabel)
    {
        $i = 0;
        ?>
        <?php if (sizeof($this->values) > 0) {
        for ($i = 0; $i < sizeof($this->values); $i++) {
            ?>
            <div class="<?= $formLabel . $this->field_name ?> col-sm-11">
                <div class="panel panel-default " id="panel1">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-target="#<?= $formLabel . $this->field_name . "_" . $i ?>"
                               href="#<?= $formLabel . $this->field_name ?>">
                                <?= $this->field_name ?>-<?= ($i + 1) ?>
                            </a>
                        </h4>

                    </div>
                    <div id="<?= $formLabel . $this->field_name . "_" . $i ?>" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <fieldset>
                                <input type="hidden" name="<?= $this->field_name ?>[<?= $i ?>][@type]"
                                       value="<?= $this->objType ?>"/>
                                <?php
                                foreach ($this->attributes as $att) {
                                    $field_name = $att->field_name;
                                    $att->field_name = str_replace("0", $i, $att->field_name);
                                    $field_name = explode('[', $field_name);
                                    $f1 = str_replace("]", "", $field_name[1]);
                                    $f2 = str_replace("]", "", $field_name[2]);
                                    $att->value = $this->values[$i][$f2];
                                    $att->show($formLabel);
                                }
                                ?>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        $i--;
    } else {
        $i = 0;
        ?>


        <div class="<?= $formLabel . $this->field_name ?> col-sm-11">
            <div class="panel panel-default " id="panel1">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-target="#<?= $formLabel . $this->field_name ?>"
                           href="#<?= $formLabel . $this->field_name ?>">
                            <?= $this->field_name ?>-<?= ($i + 1) ?>
                        </a>
                    </h4>

                </div>
                <div id="<?= $formLabel . $this->field_name ?>" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <fieldset>
                            <input type="hidden" name="<?= $this->field_name ?>[<?= $i ?>][@type]"
                                   value="<?= $this->objType ?>"/>
                            <?php
                            foreach ($this->attributes as $att) {
                                $field_name = $att->field_name;
                                $att->field_name = str_replace("0", $i, $att->field_name);
                                $field_name = explode('[', $field_name);
                                $f1 = str_replace("]", "", $field_name[1]);
                                $f2 = str_replace("]", "", $field_name[2]);
                                $att->value = $this->values[$i][$f2];
                                $att->show($formLabel);
                            }
                            ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

        <?php

    }
        ?>


        <input id="<?= $formLabel . $this->field_name ?>_addnew" class="custom-btn" type="button"
               field_index="<?= $i ?>" class="button" value="Add new" onclick="addFields()">
        <?php
    }
}