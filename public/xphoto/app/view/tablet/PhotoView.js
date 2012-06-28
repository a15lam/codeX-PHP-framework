Ext.define('XPhoto.view.tablet.PhotoView',{
    extend:'Ext.Container',
    alias:'widget.xphotoview',
    requires:['Ext.carousel.Carousel'],
    config:{
        fullscreen:true,
        layout:'fit',
        items:[{
            xtype:'toolbar',
            docked:'top',
            items:[{
                xtype:'spacer'
            },{
                xtype:'button',
                text:'Play'
            },{
                xtype:'button',
                text:'Close'
            }]
        },{
            xtype:'carousel',
            id:'photoViewCarouselTablet',
            animation: {
                duration: 500,
                easing: {
                    type: 'ease-out'
                }
            },
            indicator:false,
            items: []
        }]
    }
})

