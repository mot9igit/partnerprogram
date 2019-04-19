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
            html: '<h2>' + _('partnerprogram') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('partnerprogram_objects'),
                layout: 'anchor',
                items: [{
                    html: _('partnerprogram_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'partnerprogram-grid-objects',
                    cls: 'main-wrapper',
                }]
            },{
                title: _('partnerprogram_statuses'),
                layout: 'anchor',
                items: [{
                    html: _('partnerprogram_statuses_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'partnerprogram-grid-status',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    partnerProgram.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.panel.Home, MODx.Panel);
Ext.reg('partnerprogram-panel-home', partnerProgram.panel.Home);
