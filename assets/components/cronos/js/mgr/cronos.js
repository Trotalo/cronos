var Cronos = function (config) {
    config = config || {};
    Cronos.superclass.constructor.call(this, config);
};
Ext.extend(Cronos, Ext.Component, {

    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    combo: {},
    field: {},
    config: {},

});
Ext.reg('cronos', Cronos);
cronos = new Cronos();
