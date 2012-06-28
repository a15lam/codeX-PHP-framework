Ext.define('XPhoto.store.AlbumViewStore',{
    extend:'Ext.data.Store',
    config:{
        model:'XPhoto.model.Thumbnail',
        autoLoad:true,
        storeId:'albumViewStore'
    }
})


