<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class RWSSchemaUtils
{
    public function getData()
    {
        //check license

        global $post;

        global $wpdb;

        $table_name = $wpdb->prefix . RWSSN_SCHEMA_TBL_NAME;

        $postId = $post->ID;

        $wpdb->custom_table_name = $table_name;

        $schemaObjects = [];

        $filename = hex2bin(file_get_contents(plugins_url('bin', __FILE__)));

        $schemas = json_decode($filename, true);

        foreach ($schemas['types'] as $schema) {

            $type = $schema[array_keys($schema)[0]]["@type"];

            $query = "Select * from $wpdb->custom_table_name where type='".$type."' AND post_id='".$postId."'";

            $res = $wpdb->get_row($query, OBJECT);

            $json = isset($res->input_json) ? $res->input_json : "";

            $values = json_decode($json, true);

            $obj = new RWSSchema($schema[array_keys($schema)[0]], $values);

            array_push($schemaObjects, $obj);

        }

        return $schemaObjects;

    }

    public function getToken($data){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,"https://api.rankwithschema.com");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

       /*curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));*/
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close ($curl);

        return $result;
    }

    public function getRenderFormat(){

        return get_option('rwssnschema_display_format');

    }

}

?>