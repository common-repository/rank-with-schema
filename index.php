<?php
/*
 * Plugin Name: Rank With Schema
 * Description: All in one schema snippets and more than that, You can easily defined schema type for pages or posts on your website. We support all schema types and keep adding more and updated with Google.
 * Version: 1.0.6
 * Author: Shakeel Mumtaz
 * Contributors: Shakeel Mumtaz,Tanveer Nandla
 * Plugin URI: https://rankwithschema.com/
 * Author URI: https://www.linkedin.com/in/shakeelmumtaz/
 * Text Domain: rankwithschema.com
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once plugin_dir_path(__FILE__) . "init.php";

$savedSchemas = [[]];

function rwssn_register_assets()
{
    global $pagenow;

    if ($pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'admin-ajax.php') {
        wp_register_style('rws-bootstrap-css', plugins_url('css/bootstrap.min.css?v=' . RWSSN_SCHEMA_VERSION_NUMBER, __FILE__));
        wp_enqueue_style('rws-bootstrap-css');

        wp_register_script('rws-bootstrap-js', plugins_url('js/bootstrap.min.js?v=' . RWSSN_SCHEMA_VERSION_NUMBER, __FILE__), array());
        wp_enqueue_script('rws-bootstrap-js');

        wp_register_style('rws-styles', plugins_url('css/styles.css?v=' . RWSSN_SCHEMA_VERSION_NUMBER, __FILE__));
        wp_enqueue_style('rws-styles');

        wp_register_script('rws-validator', plugins_url('js/validator.min.js?v=' . RWSSN_SCHEMA_VERSION_NUMBER, __FILE__), array('jquery'));
        wp_enqueue_script('rws-validator');

        wp_register_script('rws-scripts', plugins_url('js/scripts.js?v=' . RWSSN_SCHEMA_VERSION_NUMBER, __FILE__), array('jquery'));
        wp_enqueue_script('rws-scripts');

        wp_localize_script('rws-scripts', 'RWS_MBAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

    }
//    wp_enqueue_media();

}

add_action('admin_init', 'rwssn_register_assets');

function rwssn_register_db()
{
    global $wpdb;

    update_option('schema_display_format', 'json');

    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . RWSSN_SCHEMA_TBL_NAME;

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		post_id varchar(255) NOT NULL,
		type varchar(255) NOT NULL,
		data text NOT NULL,
		input_json text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);

}

register_activation_hook(__FILE__, 'rwssn_register_db');

function rwssn_unregister_db()
{
    global $wpdb;

    $table_name = $wpdb->prefix . RWSSN_SCHEMA_TBL_NAME;

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);

    delete_option("my_plugin_db_version");

}

register_deactivation_hook(__FILE__, 'rwssn_unregister_db');

function rwssn_schema_panel()
{

    add_meta_box(
        'rwsschema_panel_box',           // Unique ID
        'Rank With Schema Panel <br /><p class="hint">You are using free version of plugin with limited functionality. <a href="http://rankwithschema.com">Try our Pro version!</a></p>',  // Box title
        'rwssn_schema_panel_content'
    );

}

add_action('add_meta_boxes', 'rwssn_schema_panel');

function rwssn_schema_panel_content()
{
    $utils = new RWSSchemaUtils();

    $schemas = $utils->getData();
    ?>
    <div class="row custom-row">
    <div class="col-sm-2 padding-zero">
        <nav class="nav-sidebar">
            <ul class="nav tabs"><?php $i = 0;
                foreach ($schemas as $schema) {
                    $active = $i == 0 ? "active" : "";
                    echo '<li class="' . $active . '"><a href="#' . preg_replace('/\s/', '', $schema->label) . 'tab" data-toggle="tab">' . $schema->label . '</a></li>';
                    $i++;
                } ?>
            </ul>
        </nav>
    </div>
    <div class="tab-content col-sm-10 padding-zero">
        <form method="post" name="Hi">
        </form><?php global $post;
        $i = 0;

        foreach ($schemas as $schema) {

            $active = $i == 0 ? "active" : "";

            ?>
            <div class="tab-pane <?= $active ?>" id="<?= preg_replace('/\s/', '', $schema->label) ?>tab">
                <form enctype="multipart/form-data" method="post" action=""
                      id="<?= preg_replace('/\s/', '', $schema->label) ?>" class="custom-form"
                      data-toggle="validator"><?php $schema->Show() ?>
                    <input type="hidden" name="action" value="submitSchema"/>
                    <input type="hidden" name="schemaType" value="<?= $schema->type ?>"/>
                    <input type="hidden" name="lbl" value="<?= preg_replace('/\s/', '', $schema->label) ?>"/>
                    <input type="hidden" name="postId" value="<?= $post->ID; ?>"/>
                    <?php wp_nonce_field('rwssn_nonce_post', 'rwssn_nonce'); ?>
                </form>
            </div>
            <?php $i++;
        } ?>
    </div>
    </div><?php
}

function rwssn_submit_schema()
{
    global $wpdb;

    //if doesn't have valid nonce or not logged in
    if (!check_admin_referer('rwssn_nonce_post', 'rwssn_nonce') ||
        !is_user_logged_in()) {
        $response['success'] = 0;
        $response['msg'] = "Sorry, you are not allowed to perform this action.";
        echo json_encode($response);
        die();
    }

    $utils = new RWSSchemaUtils();

    $type = $_POST['schemaType'];
    if (!$type) {
        $type = '';
    }
    if (strlen($type) <= 3) {
        $type = '';
    }
    $type = sanitize_text_field($type);

    $postId = intval($_POST['postId']);
    if (!$postId) {
        $postId = '';
    }
    if ($postId < 1) {
        $postId = '';
    }
    $postId = sanitize_text_field($postId);

    $label = $_POST['lbl'];
    if (!$label) {
        $label = '';
    }
    if (strlen($label) <= 3) {
        $label = '';
    }
    $label = sanitize_text_field($label);

    $inputJson = $_POST;
    if (!$inputJson) {
        $inputJson = [];
    }
    $inputJson = json_encode($inputJson);

    $data = [];
    $data['post_params'] = $inputJson;
    $data['access_key'] = get_option('rwssnschema_access_key');
    $data['domain_name'] = site_url();
    $data['schema_format'] = $utils->getRenderFormat();

    $result = json_decode($utils->getToken($data));

    $response = [];

    if ($result == NULL) {
        $response['success'] = 0;
        $response['msg'] = "Couldn't connect with server.";
        echo json_encode($response);
        die();
    }

    if ($result->success == 0) {
        $response['success'] = 0;
        $response['msg'] = $result->error_msg;
        echo json_encode($response);
        die();
    }


    $table_name = $wpdb->prefix . RWSSN_SCHEMA_TBL_NAME;

    $wpdb->custom_table_name = $table_name;

    $query = "Select * from $wpdb->custom_table_name where type='" . $type . "' AND post_id='" . $postId . "'";

    $id = $wpdb->get_row($query, OBJECT);

    $data = [];

    //save JSON into different format
    if (isset($id)) {

        if ($wpdb->update($table_name,

                array(
                    'post_id' => $postId,
                    'type' => $label,
                    'data' => $result->data,
                    'input_json' => $inputJson
                ), array('id' => $id->id)
            ) === FALSE) {

            $response['success'] = 0;
            $response['msg'] = "Unable to save Schema settings.";
            echo json_encode($response);

        } else {

            $response['success'] = 1;
            $response['msg'] = "Schema settings successfully saved.";
            echo json_encode($response);

        }

    } else {

        if ($wpdb->insert($table_name,

                array(
                    'post_id' => $postId,
                    'type' => $label,
                    'data' => $result->data,
                    'input_json' => $inputJson
                )
            ) === FALSE) {

            $response['success'] = 0;
            $response['msg'] = "Unable to save Schema settings.";
            echo json_encode($response);

        } else {

            $response['success'] = 1;
            $response['msg'] = "Schema settings successfully saved.";
            echo json_encode($response);

        }

    }

    die();
}

add_action('wp_ajax_submitSchema', "rwssn_submit_schema");

function rwssn_render_content($content)
{
    global $wpdb;

    global $post;

 if($post->ID != NULL && $_POST['action'] != 'editpost' && $_POST['action'] != 'newpost'){
    $sutil = new RWSSchemaUtil();

    $scripts = '';

    $postId = $post->ID;

    $table_name = $wpdb->prefix . RWSSN_SCHEMA_TBL_NAME;

    $wpdb->custom_table_name = $table_name;

    $postId = sanitize_text_field($postId);
    $query = "Select * from $wpdb->custom_table_name where post_id='" . $postId . "'";

    $result = $wpdb->get_results($query);

    $utils = new RWSSchemaUtils();

    foreach ($result as $r) {
        $scripts .= $sutil->m($r, $utils->getRenderFormat());
        ?>
        <?php
    }

    $content .= $scripts;

    return $content;
 }

}

add_filter('the_content', 'rwssn_render_content');

add_action('admin_menu', 'rwssn_admin_schema_menu');
function rwssn_admin_schema_menu()
{
    add_menu_page('Rank With Schema', 'Rank With Schema', 'manage_options', 'rwssn_manage_schema_settings', 'rwssn_manage_schema_settings');
    rwssn_enqueue_assets();
}

function rwssn_enqueue_assets()
{
    wp_enqueue_style('schema_admin_styles_css', plugin_dir_url(__FILE__) . 'css/admin.css', false, '1.0.3');
}

function rwssn_manage_schema_settings()
{
    if ($_POST != NULL) {

        //if doesn't have valid nonce or not logged in
        if (!check_admin_referer('rwssn_nonce_adpost', 'rwssn_nonce_ad') ||
            !is_user_logged_in()) {
            die();
        }

        $format = $_POST['rwssnschema_display_format'];
        if (!$format) {
            $format = '';
        }
        if (strlen($format) <= 3) {
            $format = '';
        }
        $format = sanitize_text_field($format);

        update_option('rwssnschema_display_format', $format);


        $api_key = $_POST['rwssnschema_access_key'];
        if (!$api_key) {
            $api_key = '';
        }
        if (strlen($api_key) <= 8) {
            $api_key = '';
        }
        $api_key = sanitize_text_field($api_key);
        update_option('rwssnschema_access_key', $api_key);

    }

    ?>
    <h1>Rank With Schema Settings:</h1>

    <form method="post" action="<?php echo site_url() . '/wp-admin/admin.php?page=rwssn_manage_schema_settings' ?>">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Access Key:</th>
                <td>
                    <input type="text" name="rwssnschema_access_key"
                           value="<?php echo get_option('rwssnschema_access_key'); ?>"/>
                    <p class="hint">You can generate your access key by signing up on <a
                                href="https://rankwithschema.com/users/sign_up">https://rankwithschema.com</a></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Display Format:</th>
                <td>
                    <select name="rwssnschema_display_format">
                        <option value="json" <?php echo get_option('rwssnschema_display_format') == 'json' ? 'selected="selected"' : '' ?> >
                            JSON-LD
                        </option>
                    </select>
                    <p class="hint">Free version only support JSON-LD. Want more formats? <a
                                href="https://rankwithschema.com/pricing">Buy Pro Version</a></p>
                </td>
            </tr>
        </table>
        <?php wp_nonce_field('rwssn_nonce_adpost', 'rwssn_nonce_ad'); ?>
        <?php
        submit_button();
        ?>
    </form>

    <?php
}


function rwssn_ui_render()
{

    wp_enqueue_script('rws-main', plugins_url('js/main.js?v=' . RWSSN_SCHEMA_VERSION_NUMBER, __FILE__), array('jquery'), null, true);
    ?>
    <?php
}

add_action('wp_footer', 'rwssn_ui_render');

?>
