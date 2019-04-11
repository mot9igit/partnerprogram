partnerProgram.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'partnerprogram-panel-home',
            renderTo: 'partnerprogram-panel-home-div'
        }]
    });
    partnerProgram.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.page.Home, MODx.Component);
Ext.reg('partnerprogram-page-home', partnerProgram.page.Home);