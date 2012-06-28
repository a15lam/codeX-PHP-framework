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
namespace App\modules\main\controller;

class IndexController extends \X\engine\Controller{
    
    public function index(){
        //No authentication required for this App. It's open for all
        $_SESSION['userID'] = 1;
        $pc = $this->X->G->pc;
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')!==false){
            $pc = 1;
        }
        
        if($pc){
            $this->loadDesktopApp();
        }
        else{
            $this->X->T->setExtJSVars('XPhoto.Data', array('slide_show_interval'=>SLIDE_SHOW_INTERVAL));
            $this->X->T->show(
                array(
                    "css_files"=>array('xphoto/css/app.css'),
                    "js_files"=>array('xphoto/app/app.js')
                )
            );
        }
    }
    
    public function loadDesktopApp(){
        set_time_limit(300);
        $path = null;
        if($this->X->G->dir_path){
            $path = urldecode($this->X->G->dir_path);
        }
        if(!$path || $path==''){
            $path = PICTURE_LIBRARY_PATH;
        }
        
        $album = new \App\modules\main\model\Album();
        $albumView = new \App\modules\main\model\Album($path);
        $this->X->T->albumList = $album->getList('dir');
        $this->X->T->thumbnails = $albumView->getThumbnails();
        $this->X->T->show(
            array(
                "title"=>"XPhoto - An Album Browser",
                "view_file"=>"main",
                "css_files"=>array('xphoto/css/desktopMain.css', 'xphoto/css/smoothness/jquery-ui-1.8.18.custom.css'),
                "js_files"=>array('xphoto/desktopApp/main.js', 'lib/jquery/jquery-ui-1.8.18.custom.min.js'),
                "base_view"=>BASE_STANDARD_VIEW
            )
        );
    }
    
    public function getAlbumList(){
        $album = new \App\modules\main\model\Album();
        $list = $album->getList('dir');
        $this->X->T->show(
            array(
                "output_type"=>"json",
                "json_data"=>array("success"=>true, "rows"=>$list)
            )
        );
    }
    
    public function getAlbumPicCount(){
        $path = urldecode($this->X->P->dir_path);
        if(!$path || $path==''){
            $path = PICTURE_LIBRARY_PATH;
        }
        $album = new \App\modules\main\model\Album($path);
        $count = $album->getCount();
        $this->X->T->show(array('output_type'=>'json', 'json_data'=>array("success"=>true, "count"=>$count)));
        
        $output = array();
        $cmd = 'php '.APP_INCLUDE_PATH.'thumbnailGenerator.php '.escapeshellarg($path).'>/dev/null &';
        exec($cmd, $output);
    }
    
    public function getThumbsList(){
        set_time_limit(300);
        $path = urldecode($this->X->G->dir_path);
        $limit = $this->X->G->limit;
        $skip = $this->X->G->skip;
        if(!$path || $path==''){
            $path = PICTURE_LIBRARY_PATH;
        }
        if($path == $_SESSION['currentAlbumPath']){
            $data = $_SESSION['currentAlbumList'];
        }
        else{
            $album = new \App\modules\main\model\Album($path);
            $data = $album->getThumbnails();
            $_SESSION['currentAlbumPath'] = $path;
            $_SESSION['currentAlbumList'] = $data;
        }
        if($limit>0){
            $chunk = array_slice($data, $skip, $limit);
        }
        else{
            $chunk = $data;
        }
        $this->X->T->show(
            array(
                "output_type"=>"json",
                "json_data"=>array("success"=>true, "rows"=>$chunk)
            )
        );
    }
    
    public function showThumb(){
          $path = urldecode($this->X->G->path);
          $path = str_replace('\\', '/', $path);
          $name = $this->X->G->name;
          $album = new \App\modules\main\model\Album($path);
          $album->generateThumbnail($name, 1, 1);
        
    }
    
    public function showImage(){
        $path = urldecode($this->X->G->file_path);
        $path = str_replace('\\', '/', $path);
        $name = $this->X->G->file_name;
        $windowHeight = $this->X->G->height;
        $windowWidth = $this->X->G->width;
        
        $album = new \App\modules\main\model\Album($path);
        $album->display($name, $windowHeight, $windowWidth);
        
    }
    
    public function generateAllThumbnails(){
        set_time_limit(0);
        $a = new \App\modules\main\model\Album();
        $list = $a->getList();
        foreach($list as $l){
            $b = new \App\modules\main\model\Album($l['path'].$l['name'].'/');
            $b->getThumbnails();
        }
    }
}
?>
