Ext.define('XPhoto.controller.tablet.PhotoView',{
    extend:'Ext.app.Controller',
    config:{
        views:['XPhoto.view.tablet.PhotoView'],
        refs:{
            xPhotoView:'xphotoview'
        },
        control:{
            'xphotoview toolbar button':{
                tap:function(b){
                    if(b.getText()=='Close'){
                        clearInterval(XPhoto.Data.slideShowTimer);
                        this.getXPhotoView().destroy();
                        Ext.Viewport.setActiveItem(0);
                    }
                    else if(b.getText()=='Play'){
                        var me = this;
                        XPhoto.Data.slideShowTimer = setInterval(function(){
                            var c = me.getXPhotoView().getComponent('photoViewCarouselTablet');
                            var ai = c.getActiveIndex();
                            if(ai+1 < c.items.getCount()){
                                c.setAnimation({
                                    duration: 3000,
                                    easing: {
                                        type: 'ease-out'
                                    }
                                });
                                c.next();
                            }
                            else{
                                c.setActiveItem(0);
                            }
                        }, XPhoto.Data.slide_show_interval*1000)
                        b.setText('Stop');
                    }
                    else if(b.getText()=='Stop'){
                        clearInterval(XPhoto.Data.slideShowTimer);
                        b.setText('Play');
                        var c = this.getXPhotoView().getComponent('photoViewCarouselTablet');
                        c.setAnimation({
                            duration: 500,
                            easing: {
                                type: 'ease-out'
                            }
                        });
                    }
                }
            },
            'xphotoview carousel':{
                activeitemchange:function(c, v, ov, e){
                    this.loadImage(v);
                    var i = c.getActiveIndex();
                    if(i+1 < c.items.getCount()){
                        this.loadImage(c.getAt(i+1));
                    }
                    if(i+2 < c.items.getCount()){
                        this.loadImage(c.getAt(i+2));
                    }
                    if(i-1 >= 0){
                        this.loadImage(c.getAt(i-1));
                    }
                    if(i-2 >= 0){
                        this.loadImage(c.getAt(i-2));
                    }
                }
            }
        }
    },
    
    loadImage:function(v){
        var link = v.getHtml();
        var w = v.getWidth();
        var h = v.getHeight();
        if(link && link.indexOf('<img src="')==-1){
            var linkView = '<div><img src="'+link+'"></div>';
            v.setHtml(linkView);
        }
    },
    
    launch:function(){
        
    },
    
    constructor:function(config){
        this.callParent(arguments);
    }
})