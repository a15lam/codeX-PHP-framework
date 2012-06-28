Ext.define('XPhoto.model.Thumbnail', {
    extend:'Ext.data.Model',
    config:{
        fields:['thumb', 'name', 'path'],
        proxy:{
            type:'ajax',
            timeout:300000,
            url:'?/Index/getThumbsList',
            reader:{
                type:'json',
                rootProperty:'rows'
            }
        }
    }
})

