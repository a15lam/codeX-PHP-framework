Ext.define('XPhoto.profile.Tablet', {
    extend: 'Ext.app.Profile',

    config: {
        name:'Tablet',
        views:['XPhoto.view.tablet.Main'],
        controllers:[
            'XPhoto.controller.tablet.AlbumList', 
            'XPhoto.controller.tablet.AlbumView', 
            'XPhoto.controller.tablet.PhotoView'
        ]
    },

    isActive: function() {
        //return true;
        return Ext.os.is.Tablet;
    },
    
    launch:function(){
        Ext.Viewport.add(Ext.create('XPhoto.view.tablet.Main'));
    }
});