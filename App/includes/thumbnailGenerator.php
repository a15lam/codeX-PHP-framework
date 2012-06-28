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
$path = $argv[1];
const PICTURE_LIBRARY_PATH = '/media/XPhotoAlbums/';
const TP = '/var/www/XPhoto/public/xphoto/app/data/thumbnails/';
const TW = 245;
const TH = 162;

function getNewWH($h, $w){
    $wh = TH;
    $ww = TW;
    
    $newWidth = $w;
    $newHeight = $h;

    $ratio = $h/$w;
    if($w > $ww || $h > $wh){
        if($wh < $ww){
            $newHeight = $wh;
            $newWidth = $wh/$ratio;
            if($newWidth > $ww){
                $newWidth = $ww;
                $newHeight = $ratio*$ww;
            }
        }
        else{
            $newWidth = $ww;
            $newHeight = $ratio*$ww;
            if($newHeight > $wh){
                $newHeight = $wh;
                $newWidth = $wh/$ratio;
            }
        }
    }

    return array('height'=>$newHeight, 'width'=>$newWidth);
}

function getThumbName($fullFilePath){
    $thumbFileName = str_replace(array(PICTURE_LIBRARY_PATH, "/", ":", "\\", " ", "(", ")", "%", "&", "+"), "_", $fullFilePath);
    return $thumbFileName;
}
function makeThumbnail($image, $maintainAspectRatio=1){
    print getThumbName($image)."\n";
    
    $fullFilePath = $image;
    $thumbFilePath = TP.getThumbName($fullFilePath);
    if(!file_exists($thumbFilePath)){
        list($w, $h) = getimagesize($fullFilePath);
        $newWidth = TW;
        $newHeight = TH;
        if($maintainAspectRatio){
            $heightWidth = getNewWH($h, $w);
            $newHeight = $heightWidth['height'];
            $newWidth = $heightWidth['width'];
        }
        $srcImage = imagecreatefromjpeg($fullFilePath);
        $desImage = imagecreatetruecolor(TW, TH);
        $desX = (TW/2)-($newWidth/2);
        $desY = (TH/2)-($newHeight/2);
        imagecopyresized($desImage, $srcImage, $desX, $desY, 0, 0, $newWidth, $newHeight, $w, $h);
        imagejpeg($desImage, $thumbFilePath, 100);
        imagedestroy($desImage);
        imagedestroy($srcImage);
    }
}

$dh = opendir($path);
if(!$dh) throw new Exception("Failed to open Directory ".$path);

while($file = readdir($dh)){
    $extension = substr($file, strrpos($file, '.'));
    if(in_array($extension, array('.jpg', '.jpeg', '.JPG', '.JPEG'))){
        makeThumbnail($path.$file);
    }
}
?>
