partnerProgram.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'partnerprogram-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: false,
            hideMode: 'offsets',
            items: [{
                title: _('partnerprogram_items'),
                layout: 'anchor',
                items: [{
                    html: _('partnerprogram_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'partnerprogram-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    partnerProgram.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.panel.Home, MODx.Panel);
Ext.reg('partnerprogram-panel-home', partnerProgram.panel.Home);
