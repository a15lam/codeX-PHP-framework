Ext.application({
    name: 'XPhoto',
    appFolder:'xphoto/app',
    profiles:['Phone', 'Tablet'],
    phoneStartupScreen: 'xphoto/images/Homescreen.jpg',
    tabletStartupScreen: 'xphoto/images/Homescreen~ipad.jpg',
    tabletIcon:'xphoto/images/icon@144.png',
    launch:function(){
        if(!Ext.os.is.Phone && !Ext.os.is.Tablet){
            location.href='?/Index/index/pc/1'
        }
    }
});