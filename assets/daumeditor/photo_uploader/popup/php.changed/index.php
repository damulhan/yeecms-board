<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
include_once("./_common.php");
@include_once("./JSON.php");
@include_once("./db.mysql.func.php");
@include_once("./table.info.php");
@include_once("./db.info.php");

$DB_CONNECT = isConnectDb($DB);

//////////////////////////////////////////////
// ==> 
if( !function_exists('json_encode') ) {
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}

@ini_set('gd.jpeg_ignore_warning', 1);

$ym = date('Ym');
$day = date('d');

$data_dir = G5_DATA_PATH.'/editor/'.$ym.'/'.$day.'/';
$data_url = G5_DATA_URL.'/editor/'.$ym.'/'.$day.'/';

@mkdir($data_dir, G5_DIR_PERMISSION);
@chmod($data_dir, G5_DIR_PERMISSION);

require('UploadHandler.php');

$options = array(
    'upload_dir' => $data_dir,
    'upload_url' => $data_url,
    // This option will disable creating thumbnail images and will not create that extra folder.
    // However, due to this, the images preview will not be displayed after upload
    //'image_versions' => array()
);

$upload_handler = new UploadHandler($options);

// <== 
//////////////////////////////////////////////


