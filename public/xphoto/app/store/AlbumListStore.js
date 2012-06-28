Ext.define('XPhoto.store.AlbumListStore', {
    extend:'Ext.data.Store',
    config:{
        model:'XPhoto.model.Album',
        autoLoad:true,
        storeId:'albumListStore',
        sorters:'name',
        grouper:function(record){
            return record.get('name')[0];
        }
    }
});

