Ext.define('XPhoto.model.Album',{
    extend:'Ext.data.Model',
    config:{
        fields:['name', 'path'],
        proxy:{
            type:'ajax',
            url:'?/Index/getAlbumList',
            reader:{
                type:'json',
                rootProperty:'rows'
            }
        }
    }
});

