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
namespace X\engine;

class XDirectory{
    protected $path;
    protected $contentList = array();
    
    public function __construct($path){
        $this->path = $path;
        if(!is_dir($this->path)){
            throw new \UnexpectedValueException(__METHOD__.": Invalid Directory path supplied");
        }
        $this->listAll();
    }
    
    public function getList($type='dir'){
        $list = $this->contentList;
        $myList = array();
        foreach($list as $l){
            if($l['type']==$type){
                $myList[] = $l;
            }
        }
        return $myList;
    }
    
    private function listAll(){
        $list = array();
        $dh = opendir($this->path);
        if(!$dh) throw new \UnexpectedValueException(__METHOD__.": Failed to open Directory ".$this->path);
        
        while($file = readdir($dh)){
            $extension = substr($file, strrpos($file, '.'));
            //$path = str_replace('/', '\\', $this->path);
            $path = $this->path;
            $list[] = array('name'=>$file, 'path'=>$path, 'type'=>filetype($this->path.$file), 'extension'=>$extension);
        }
        $this->contentList = $list;
    }
}
?>
