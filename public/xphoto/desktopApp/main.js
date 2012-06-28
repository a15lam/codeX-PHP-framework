$(document).ready(function(){
    var albumView = {
        config:{
            container:$('#albumView'),
            thumbnail:$('#albumView div.node'),
            aspectRatio:1.78, //16:9
            w: $(window).width()-100,
            h: $(window).height()-120,
            currPic:1
        },
        
        init:function(config){
            $.extend(this.config, config);
            this.config.thumbnail.on('click', this.selectThumbnail)
        },
        
        selectThumbnail:function(){
            var thumbnail = $(this),
                picNum = thumbnail.data('num'),
                w = albumView.getDialogWidth(),
                h = albumView.getDialogHeight(),
                totalPics = albumView.config.thumbnail.length;
            
            $('#xphotoView ul').css('width', totalPics*w);
            $('.XPhoto').css({
                'width':albumView.getImageWidth(),
                'height':albumView.getImageHeight()
            });
            albumView.config.currPic = picNum;
            albumView.loadImage();
            
            $('#xphotoView ul').animate({
                'margin-left':-picNum*w
            })
            
            $('#xphotoView').dialog({
                modal:true,
                width:w,
                height:h,
                buttons:[{
                    text:'Prev',
                    click:function(){
                        albumView.config.currPic--;
                        if(albumView.loadImage()){
                            $('#xphotoView ul').animate({
                                'margin-left':'+='+w
                            });
                        }
                        else{
                            albumView.config.currPic++;
                        }
                    }
                },{
                    text:'Next',
                    click:function(){
                        albumView.config.currPic++;
                        if(albumView.loadImage()){
                            $('#xphotoView ul').animate({
                                'margin-left':'-='+w
                            });
                        }
                        else{
                            albumView.config.currPic--;
                        }
                    }
                }]
            })
        },
        
        loadImage:function(){
            var picNum = albumView.config.currPic,
                prevPic = picNum-1,
                nextPic = picNum+1,
                totalPics = albumView.config.thumbnail.length;
           
           if(picNum>=totalPics || picNum<0){
               alert('No more picture this way...');
               return false
           }
           
           $('#image_'+picNum).attr('src', albumView.makeLink(picNum))
            if(nextPic<totalPics){
                var nextImage = $('#image_'+nextPic);
                if(nextImage.attr('src')==''){
                    nextImage.attr('src', albumView.makeLink(nextPic))
                }
            }
            if(prevPic>=0){
                var prevImage = $('#image_'+prevPic);
                if(prevImage.attr('src')==''){
                    prevImage.attr('src', albumView.makeLink(prevPic))
                }
            }
            return true
        },
        
        makeLink:function(picNum){
            var thumbnail = $('#thumbnail_'+picNum),
                path = thumbnail.data('path'),
                name = thumbnail.data('name'),
                w = albumView.getImageWidth(),
                h = albumView.getImageHeight(),
                link = '?/Index/showImage/file_path/'+path+'/file_name/'+name+'/height/'+h+'/width/'+w;
            
            return link;
        },
        
        getDialogWidth:function(){
            var w = albumView.config.w,
                h = w/albumView.config.aspectRatio;
                
            if(h>albumView.config.h){
                h = (albumView.config.h-120);
                w = h*albumView.config.aspectRatio;
            }
            return w;
        },
        
        getDialogHeight:function(){
            var w = albumView.getDialogWidth(),
                h = w/albumView.config.aspectRatio;
            return h+120;
        },
        
        getImageWidth:function(){
            return albumView.getDialogWidth();
        },
        
        getImageHeight:function(){
            var h = (albumView.getDialogHeight()-120);
            return h;
        }
    }
    
    
    
    albumView.init();
    
    

    $('#listToggle').on('click', function(){
        if($(this).text()=='Hide List'){
            $(this).text('Show List');
        }
        else{
            $(this).text('Hide List');
        }
            
        $('#albumList').animate({
            'width':'toggle'
        }, 500);
    })
});
