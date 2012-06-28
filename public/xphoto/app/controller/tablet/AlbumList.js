Ext.define('XPhoto.controller.tablet.AlbumList',{
    extend:'Ext.app.Controller',
    config:{
        views:['XPhoto.view.tablet.AlbumList'],
        stores:['AlbumListStore'],
        models:['Album'],
        refs:{
            albumListContainer:'xphotomainview xphotoalbumlist',
            //albumDataView:'xphotoalbumview dataview',
            albumCarousel:'xphotoalbumview carousel',
            albumList:'xphotoalbumlist list'
        },
        control:{
            'xphotomainview titlebar button':{
                tap:function(b){
                    if(b.getText()=='Hide List'){
                        this.getAlbumListContainer().hide();
                        b.setText('Show List');
                        b.setUi('forward');
                    }
                    else if(b.getText()=='Show List'){
                        this.getAlbumListContainer().show();
                        b.setText('Hide List');
                        b.setUi('back');
                    }
                }
            },
            'xphotoalbumlist list':{
                itemtap:function(dv, i, t, r, e){
                    var path = r.data.path+r.data.name+'/';
                    var cr = this.getAlbumCarousel();
                    this.loadAlbumCards(path, cr);
                }
            },
            'xphotoalbumlist toolbar searchfield':{
                clearicontap:function(){
                    this.clearSearch();
                },
                keyup:function(f){
                    this.doSearch(f);
                }
            }
        }
    },
    
    loadAlbumCards:function(path, cr){
        Ext.Ajax.request({
            url:'?/Index/getAlbumPicCount',
            params:{
                dir_path:path
            },
            method:'POST',
            success:function(response, opts){
                var obj = Ext.decode(response.responseText);
                var count = obj.count*1;
                var cards = Math.ceil(count/16);
                cr.removeAll(true, true);
                for(var i=0; i<cards; i++){
                    cr.add({
                        xtype:'dataview',
                        hidden:false,
                        store:'albumViewStore',
                        myPath:path,
                        itemCls:'hideclass',
                        itemTpl: ['<div>',
                                    '<tpl for=".">',
                                        '<div class="node"><img src="?Index/showThumb/path/{path}/name/{name}"></div>',
                                    '</tpl>',
                                '</div>']
                    })
                }
                cr.setActiveItem(0);
            }
        })
    },
    
    doSearch:function(field) {
        //get the store and the value of the field
        var value = field.getValue(),
            store = this.getAlbumList().getStore();

        //first clear any current filters on thes tore
        store.clearFilter();

        //check if a value is set first, as if it isnt we dont have to do anything
        if (value) {
            //the user could have entered spaces, so we must split them so we can loop through them all
            var searches = value.split(' '),
                regexps = [],
                i;

            //loop them all
            for (i = 0; i < searches.length; i++) {
                //if it is nothing, continue
                if (!searches[i]) continue;

                //if found, create a new regular expression which is case insenstive
                regexps.push(new RegExp(searches[i], 'i'));
            }

            //now filter the store by passing a method
            //the passed method will be called for each record in the store
            store.filter(function(record) {
                var matched = [];

                //loop through each of the regular expressions
                for (i = 0; i < regexps.length; i++) {
                    var search = regexps[i],
                        didMatch = record.get('name').match(search);

                    //if it matched the first or last name, push it into the matches array
                    matched.push(didMatch);
                }

                //if nothing was found, return false (dont so in the store)
                if (regexps.length > 1 && matched.indexOf(false) != -1) {
                    return false;
                } else {
                    //else true true (show in the store)
                    return matched[0];
                }
            });
        }
    },
    
    clearSearch:function() {
        //call the clearFilter method on the store instance
        this.getAlbumList().getStore().clearFilter();
    },
    
    launch:function(){
       
    },
    
    constructor:function(config){
        this.callParent(arguments);
    }
})
