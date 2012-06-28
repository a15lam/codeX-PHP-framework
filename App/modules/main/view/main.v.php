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
?>
<div id="titlebar">
    <button id="listToggle">Hide List</button>
    <div id="titletext">XPhoto - An Album Browser</div>
</div>
<div id="xphotoContainer">
    <div id="albumList">
        <ul>
            <?
            foreach($albumList as $l){
                ?><li><a href="?/Index/loadDesktopApp/dir_path/<?=urlencode($l['path'].$l['name'].'/')?>"><?=$l['name']?></a></li><?
            }
            ?>
        </ul>
    </div>
    <div id="albumView">
        <?
        if(count($thumbnails)>0){
            for($i=0; $i<count($thumbnails); $i++){
                $t = $thumbnails[$i];
                ?><div id="thumbnail_<?=$i?>" class="node" data-num="<?=$i?>" data-path="<?=$t['path']?>" data-name="<?=$t['name']?>"><img src="?Index/showThumb/path/<?=$t['path']?>/name/<?=$t['name']?>"></div><?
            }
        }
        ?>
    </div>
</div>
<div id="xphotoView">
    <ul>
        <?
        for($j=0; $j<count($thumbnails); $j++){
            ?><li><img class="XPhoto" id="image_<?=$j?>" src="" /></li><?
        }
        ?>
    </ul>
</div>