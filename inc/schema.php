<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class RWSSchema
{
    public $context;
    public $type;
    public $label;
    public $attributes = [];

    function __construct($keys, $vales)
    {

        $this->context = $keys['@context'];
        $this->label = $keys["label"];
        $this->type = $keys['@type'];

        foreach ($keys['attributes'] as $atti) {
            $atti['value'] = $vales[$atti['field_name']];
            $obj = new $atti['type']($atti);
            array_push($this->attributes, $obj);
        }
    }

    public function Show()
    {
        ?>
        <?php
        $totalColSpan = 0;
        for ($i = 0; $i < sizeof($this->attributes); $i++) {

            $colSpan = $this->attributes[$i]->col_span;
            if ($colSpan == 12) {
                ?>
                <div class="row">
                    <?php $this->attributes[$i]->show(preg_replace('/\s/', '', $this->label)); ?>
                </div>
                <div class="spam-row"></div>
                <?php
            } else {
                ?>

                <div class="row">
                    <?php
                    while ($totalColSpan < 12 And $i < sizeof($this->attributes)) {
                        $totalColSpan = $this->attributes[$i]->col_span + $totalColSpan;
                        $this->attributes[$i]->show(preg_replace('/\s/', '', $this->label));
                        if ($totalColSpan < 12)
                            $i++;
                    }
                    ?>
                </div>

                <?php if ($totalColSpan >= 12) {
                    $totalColSpan = 0;
                } ?>
                <div class="spam-row"></div>
                <?php

            }
        }
        ?>

            <input type='submit' name='<?= preg_replace('/\s/', '', $this->label) ?>_submit'
                   id='<?= preg_replace('/\s/', '', $this->label) ?>_submit' value="Submit" class='btn-blog'>

        <div class="loader hidden" id="<?= preg_replace('/\s/', '', $this->label) ?>-loader"></div>
        <div class="alert alert-success hidden" id="<?= preg_replace('/\s/', '', $this->label) ?>-msg"role="alert"></div>

        <?php
    }

}

?>