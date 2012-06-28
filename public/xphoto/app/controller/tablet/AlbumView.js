Ext.define('XPhoto.controller.tablet.AlbumView',{
    extend:'Ext.app.Controller',
    config:{
        views:['XPhoto.view.tablet.AlbumView'],
        stores:['AlbumViewStore'],
        models:['Thumbnail'],
        refs:{
            
        },
        control:{
            'xphotoalbumview dataview':{
                itemtap:function(dv, j, t, r, e){
                    this.getPhotoView(dv, j, t, r, e);
                }
            },
            'xphotoalbumview carousel':{
                activeitemchange:function(c, v, ov, e){
                    var i = c.getActiveIndex();
                    var path = v.myPath;
                    v.getStore().load({
                        params:{
                            dir_path:path,
                            limit:16,
                            skip:i*16
                        },
                        callback:function(){
                            v.setItemCls('showclass')
                            if(i+1 < c.items.getCount()){
                                c.getAt(i+1).setItemCls('hideclass');
                            }
                            if(i-1 >= 0){
                                c.getAt(i-1).setItemCls('hideclass');
                            }
                        }
                    });
                }
            }
        }
    },
    
    getPhotoView:function(dv, j, t, r, e){
        var photoView = Ext.create('XPhoto.view.tablet.PhotoView');
        var pc = photoView.getComponent('photoViewCarouselTablet');
        this.loadPhotoView(photoView, pc, dv, j, t, r, e);
    },
    
    loadPhotoView:function(pv, pc, dv, j, t, r, e){
        var h = Ext.Viewport.getWindowHeight();
        var w = Ext.Viewport.getWindowWidth();
        var thumbDVStore = dv.getStore();
        var myPath = thumbDVStore.getAt(0).data.path;
        var albumIndex = dv.up().getActiveIndex();
        var me = this;
        
        Ext.Ajax.request({
            url:'?/Index/getThumbsList',
            params:{
                dir_path:myPath.replace(/\\/g, '/'),
                limit:0
            },
            method:'GET',
            success:function(response, opts){
                var obj = Ext.decode(response.responseText);
                for(var i=0; i<obj.rows.length; i++){
                    var path = obj.rows[i].path;
                    var name = obj.rows[i].name;
                    var imageLink = '?/Index/showImage/file_path/'+path+'/file_name/'+name+'/height/'+h+'/width/'+w;
                    pc.add({
                        html:imageLink,
                        width:w,
                        height:h,
                        style: 'background-color: #000000'
                    })
                }
                pc.setActiveItem(j+(albumIndex*16));
                Ext.Viewport.add(pv);
                Ext.Viewport.setActiveItem(1);
            }
        })
    },
    
    constructor:function(config){
        this.callParent(arguments);
    }
})
