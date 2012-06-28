Ext.define('XPhoto.view.tablet.Main',{
    extend:'Ext.Container',
    requires:['Ext.TitleBar'],
    alias:'widget.xphotomainview',
    fullscreen:true,
    config:{
        layout:'fit',
        items: [{
            xtype:'titlebar',
            docked:'top',
            title:'XPhoto - An Album Browser',
            items:[{
                xtype:'button',
                ui:'back',
                text:'Hide List',
                align:'left'
            }]
        },{
            xtype:'xphotoalbumlist',
            docked:'left',
            width:255
        },{
            xtype: 'xphotoalbumview',
            style: 'background-color: gray'
        }]
    }
})