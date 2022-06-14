Ext.define('Plugin.turbo-pages.Panel', {
    extend: 'Ext.panel.FormPanel',

    requires: ['Plugin.turbo-pages.grid', 'Plugin.turbo-pages.grid'],

    bodyCls: 'x-window-body-default',
    cls: 'x-window-body-default',
    style: 'border: none',
    border: false,
    layout: 'vbox',

    items: [
        Ext.create('Plugin.turbo-pages.grid', {
            flex: 1,
            from: 'dir_data',
            'title': _('Список разделов'),
        }),
        Ext.create('Plugin.turbo-pages.grid', {
            flex: 1,
            from: 'materials',
            'title': _('Список материалов'),
        })

    ]

});