const tbDirs = (scope, from) => {

    const btnReload = {
        tooltip: _('Обновить'),
        iconCls: 'icon-reload',
    };

    const btnAdd = {
        id: from + 'tp_new',
        iconCls: 'icon-new',
        text: _('Добавить'),
        tooltip: '<b>Добавить</b>',
        scope: scope,
        handler: function () {
            this.addEx();
        },
    }

    const separator = '-';

    const btnDelete = {
        id: from + 'tp_delete',
        disabled: true,
        iconCls: 'icon-delete',
        text: _('Удалить'),
        tooltip: '<b>Удалить</b>',
        scope: scope,
        handler: function () {
            this.deleteEx();
        },
    }

    const toolbar = Ext.create('Ext.toolbar.Toolbar');
    
    toolbar.add(btnReload);
    toolbar.add(separator);
    toolbar.add(btnAdd);
    toolbar.add(separator);
    toolbar.add(btnDelete);

    return toolbar;
}

//cms
Ext.define('Plugin.turbo-pages.grid', {
 
    extend: 'Ext.grid.GridPanel',

    selModel: {
        mode: 'SINGLE',
        listeners: {
            'selectionchange': {
                fn: function (sm) {
                    const hs = sm.hasSelection();
                    // Ext.getCmp(sm.store.from + 'tp_edit').setDisabled(!hs);
                    Ext.getCmp(sm.store.from + 'tp_delete').setDisabled(!hs);
                },
                scope: this
            }
        }
    },

    columns: [
        {header: "N", width: 50, dataIndex: 'N'},
        {header: "ID", width: 100, dataIndex: 'id'},
        {flex: 1, header: "Заголовок", width: 500, dataIndex: 'header'},
        {flex: 1, header: "Alias", width: 500, dataIndex: 'alias'},
    ],

    initComponent: function () {
        this.store = new Ext.data.JsonStore({
            autoDestroy: true,
            remoteSort: true,
            fields: ['N', 'id', 'header', 'alias'],
            sortInfo: {field: "id", direction: "ASC"},
            totalProperty: 'total',
            proxy: {
                type: 'ajax',
                url: '/plugins/turbo-pages/data/options.php',
                method: 'POST',
                simpleSortMode: true,
                reader: {
                    root: 'rows',
                    idProperty: 'id'
                },
                extraParams: {
                    action: 'getList',
                    from: this.from,
                }
            },
            from: this.from,
        });

        this.tbar = tbDirs(this, this.from);

        this.on({
            'celldblclick': function () {
                alert(this.getSelectionModel().getSelection()[0].getId());
            },
            scope: this
        },
        );

        this.fireEvent('activate');
        this.callParent();
        this.reload();
    },

    border: false,
    loadMask: true,
    stripeRows: true,

    reload: function () {
        this.store.load();
    },

    addEx: function () {
        if (!this.propertiesWin) {
            this.propertiesWin = Ext.create('Plugin.turbo-pages.Selector', {
                from: this.from,
            });
            this.propertiesWin.on('listChanged', function () {
                this.reload();
            }, this);
        }
        this.propertiesWin.show();
    },

    deleteEx: function () {

        Ext.Ajax.request({
            url: '/plugins/turbo-pages/data/options.php',
            params: {
                action: 'deleteID',
                from: this.from,
                param: this.getSelectionModel().getSelection()[0].getId(),
            },
            scope: this,
            success: function (response, options) {
                this.store.reload();
            },
            failure: function (response, options) {

            }
        });
    },

});