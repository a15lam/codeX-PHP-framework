Ext.define('XPhoto.view.tablet.AlbumView',{
    extend:'Ext.Panel',
    alias:'widget.xphotoalbumview',
    config:{
        layout:'fit',
        padding:1,
        items:[{
            xtype:'carousel',
            style:{
                paddingTop:'10px'
            },
            id:'photoAlbumViewCarouselTablet',
            animation: {
                duration: 500,
                easing: {
                    type: 'ease-out'
                }
            },
            indicator:false,
            items:[]
        }]
    }
})

