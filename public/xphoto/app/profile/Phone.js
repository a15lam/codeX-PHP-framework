Ext.define('XPhoto.profile.Phone', {
    extend: 'Ext.app.Profile',

    config: {
        name:'Phone'
    },

    isActive: function() {
        return Ext.os.is.Phone;
    },
    
    launch:function(){
        Ext.Msg.alert('Sorry...', 'This app is not available for iPhone yet.')
    }
});