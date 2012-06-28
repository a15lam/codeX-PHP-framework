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
namespace App\modules\main\model;

class Album extends \X\engine\XDirectory{
    private $supportedImageTypes = array('.jpg', '.jpeg', '.JPG', '.JPEG');
    
    public function __construct($path=PICTURE_LIBRARY_PATH){
        parent::__construct($path);
    }
    
    public function getList($type='dir'){
        $list = parent::getList($type);
        $myList = array();
        foreach($list as $l){
            if($l['name']!='.' && $l['name']!='..'){
                $myList[] = $l;
            }
        }
        return $myList;
    }
    
    public function getCount($type='file'){
        $list = $this->getList($type);
        return count($list);
    }
    
    public function getThumbnails(){
        $list = $this->getList('file');
        $thumbs = array();
        foreach($list as $l){
            if(in_array($l['extension'], $this->supportedImageTypes)){
                //$this->generateThumbnail($l['name'], 1);
                $thumbFileName = $this->getThumbName($this->path.$l['name']);
                $thumbFilePath = THUMBNAIL_WEB_PATH.$thumbFileName;
                $thumbs[] = array('thumb'=>$thumbFilePath, 'name'=>$l['name'], 'path'=>str_replace('/', '\\', $l['path']));
            }
        }
        return $thumbs;
    }
    
    private function getThumbName($fullFilePath){
        $thumbFileName = str_replace(array(PICTURE_LIBRARY_PATH, "/", ":", "\\", " ", "(", ")", "%", "&", "+"), "_", $fullFilePath);
        return $thumbFileName;
    }
    
    private function getThumbPath($fullFilePath){
        $thumbFileName = $this->getThumbName($fullFilePath);
        $thumbFilePath = THUMBNAIL_PATH.$thumbFileName;
        return $thumbFilePath;
    }
    
    private function resizeImageHeightWidth($fullFilePath, $wh=0, $ww=0, $oh=0, $ow=0){
        if(!$wh) $wh = THUMB_HEIGHT;
        if(!$ww) $ww = THUMB_WIDTH;
        
        if($oh==0 || $ow==0){
            list($w, $h) = getimagesize($fullFilePath);
        }
        else{
            $w = $ow;
            $h = $oh;
        }
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
    
    public function generateThumbnail($fileName, $maintainAspectRatio=0, $print=false){
        $fullFilePath = $this->path.$fileName;
        $thumbFilePath = $this->getThumbPath($fullFilePath);
        if(!file_exists($thumbFilePath)){
            list($w, $h) = getimagesize($fullFilePath);
            $newWidth = THUMB_WIDTH;
            $newHeight = THUMB_HEIGHT;
            if($maintainAspectRatio){
                $heightWidth = $this->resizeImageHeightWidth($fullFilePath, THUMB_HEIGHT, THUMB_WIDTH, $h, $w);
                $newHeight = $heightWidth['height'];
                $newWidth = $heightWidth['width'];
            }
            $srcImage = imagecreatefromjpeg($fullFilePath);
            $desImage = imagecreatetruecolor(THUMB_WIDTH, THUMB_HEIGHT);
            $desX = (THUMB_WIDTH/2)-($newWidth/2);
            $desY = (THUMB_HEIGHT/2)-($newHeight/2);
            imagecopyresized($desImage, $srcImage, $desX, $desY, 0, 0, $newWidth, $newHeight, $w, $h);
            if($print){
                imagejpeg($desImage, $thumbFilePath, 100);
                header('Content-Type: image/jpeg');
                imagejpeg($desImage);
            }
            else{
                imagejpeg($desImage, $thumbFilePath, 100);
            }
            imagedestroy($desImage);
            imagedestroy($srcImage);
        }
        else if($print){
            header('Content-Type: image/jpeg');
            readfile($thumbFilePath);
        }
    }
    
    public function display($file, $h, $w){
        $h = $h-40;
        $w = $w*1;
        $fileFullPath = $this->path.$file;
        list($sw, $sh) = getimagesize($fileFullPath);
        $hw = $this->resizeImageHeightWidth($fileFullPath, $h, $w);
        $nh = $hw['height'];
        $nw = $hw['width'];
        
        $srcImage = imagecreatefromjpeg($fileFullPath);
        $desImage = imagecreatetruecolor($w, $h);
        $desX = ($w/2)-($nw/2);
        $desY = ($h/2)-($nh/2);
        imagecopyresampled($desImage, $srcImage, $desX, $desY, 0, 0, $nw, $nh, $sw, $sh);
        imagedestroy($srcImage);
        header('Content-Type: image/jpeg');
        imagejpeg($desImage);
        imagedestroy($desImage);
    }
}
?>
