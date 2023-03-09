cronos.page.Manage = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [
            {
                xtype: 'cronos-panel-manage',
                renderTo: 'cronos-panel-manage-div'
            }
        ]
    });
    cronos.page.Manage.superclass.constructor.call(this, config);
};
Ext.extend(cronos.page.Manage, MODx.Component);
Ext.reg('cronos-page-manage', cronos.page.Manage);
