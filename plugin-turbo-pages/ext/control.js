const eSave = Ext.create('Ext.Button', {
    text: _('Сохранить'),
    handler: function() {
        Ext.Ajax.request({
            url: '/plugins/turbo-pages/data/options.php',
            method: 'POST',
            params: {
                action: 'setFilename',
                param: Ext.getCmp('tp-filename').getValue()
            },
            success: function(response, options){
                const result = Ext.decode(response.responseText);
                console.log(result);
    
            },
            failure: function(response, options){
                alert("Ошибка: " + response.statusText);
            }
        });
    },
    width: 100,

});

const eExport = Ext.create('Ext.Button', {
    text: _('Экспорт'),
    handler: function() {
        Ext.Ajax.request({
            url: '/plugins/turbo-pages/data/export.php',
            method: 'POST',
            params: {
                param: Ext.getCmp('tp-filename').getValue()
            },
            success: function(response, options){
                const result = Ext.decode(response.responseText);
                console.log(result);
    
            },
            failure: function(response, options){
                alert("Ошибка: " + response.statusText);
            }
        });
    },
    width: 100,

});

const filenameInit = (eInput) => {
    Ext.Ajax.request({
        url: '/plugins/turbo-pages/data/options.php',
        method: 'POST',
        params: {
            action: 'getFilename'
        },
        success: function(response, options){
            const result = Ext.decode(response.responseText);
            eInput.setValue(result.result);
        },
        failure: function(response, options){
            alert("Ошибка: " + response.statusText);
        }
    });
}

const eFileName = {
    xtype: 'textfield',
    id: 'tp-filename',
    name: 'filename',
    fieldLabel: _('Файл экспорта'),
    allowBlank: false,
    value: 'turbo-default.xml',
    flex: 1,
    initComponent: function () {
        filenameInit(this);
    },
}

Ext.define('Plugin.turbo-pages.control', {
    extend: 'Ext.toolbar.Toolbar',

    items: [
        eSave,
        '-',
        eExport,
        '-',
        eFileName,]

});
