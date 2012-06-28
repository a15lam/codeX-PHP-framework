<?php
/******************************************************************************
* Copyright (c) 2012 Ariful Islam
* 
* Permission is hereby granted, free of charge, to any person obtaining
* a copy of this software and associated documentation files (the
* "Software"), to deal in the Software without restriction, including
* without limitation the rights to use, copy, modify, merge, publish,
* distribute, sublicense, and/or sell copies of the Software, and to
* permit persons to whom the Software is furnished to do so, subject to
* the following conditions:
* 
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
* LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
* WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*******************************************************************************/
define('APP_NAME', 'XPhoto');
define('BASE_VIEW_FILE', BASE_TOUCH_VIEW);
define('DB_TYPE', NULL);
//DB authentication params
/**
 *No DB Used for XPhoto 
 *
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'XPhoto');
 * 
 */
define('THUMBNAIL_DIR', 'thumbnails');
define('FULL_IMAGE_DIR', 'images');
define('APP_DATA_WEB_PATH', 'xphoto/app/data/');
define('THUMBNAIL_WEB_PATH', APP_DATA_WEB_PATH.THUMBNAIL_DIR.'/');
define('APP_DATA_PATH', APP_PUBLIC_PATH.APP_DATA_WEB_PATH);
define('THUMBNAIL_PATH', APP_DATA_PATH.THUMBNAIL_DIR.'/');
define('FULL_IMAGE_PATH', APP_DATA_PATH.FULL_IMAGE_DIR.'/');
define('PICTURE_LIBRARY_PATH', 'E:/Users/arif/Pictures/');
define('THUMB_WIDTH', 135);
define('THUMB_HEIGHT', 88);
define('SLIDE_SHOW_INTERVAL', 6) //SECONDS
?>
