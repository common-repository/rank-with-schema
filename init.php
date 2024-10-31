<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('RWSSN_SCHEMA_VERSION_NUMBER', '1.0.5');
define('RWSSN_SCHEMA_TBL_NAME', 'rwssn_schemas');
define('RWSSN_SCHEMA_FORMAT', 'json');
define('RWSSN_SCHEMA_PLUGIN_NAME', 'rank_with_schema' );

require_once plugin_dir_path( __FILE__ )."inc/data-types/attribute.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/text.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/textarea.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/url.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/number.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/image.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/hidden.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/dropdown.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/date.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/array.php";
require_once plugin_dir_path( __FILE__ )."inc/data-types/textt.php";
require_once plugin_dir_path( __FILE__ )."inc/utils.php";
require_once plugin_dir_path( __FILE__ )."inc/schema.php";
