partnerProgram.window.CreateBalance = function (config) {
    config = config || {};
    this.ident = config.ident || 'mecitem' + Ext.id();
    Ext.applyIf(config, {
        title: _('partnerprogram_menu_create'),
        width: 600,
        baseParams: {
            action: 'mgr/balance/create',
        },
    });
    partnerProgram.window.CreateBalance.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.CreateBalance, partnerProgram.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    items: [{
                        xtype: 'partnerprogram-combo-user',
                        id: config.id + '-user_id',
                        fieldLabel: _('partnerprogram_balance_user_id'),
                        name: 'user_id',
                        anchor: '99%'
                    },{
                        xtype: 'textfield',
                        id: config.id + '-balance',
                        fieldLabel: _('partnerprogram_balance'),
                        name: 'balance',
                        anchor: '99%'
                    },{
                        xtype: 'textfield',
                        id: config.id + '-balance-paid-balance',
                        fieldLabel: _('partnerprogram_balance_paid_balance'),
                        name: 'paid_balance',
                        anchor: '99%'
                    },],
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    items: [{
                        xtype: 'textfield',
                        id: config.id + '-balance-possible-balance',
                        fieldLabel: _('partnerprogram_balance_possible_balance'),
                        name: 'possible_balance',
                        anchor: '99%'
                    }, {
                        xtype: 'textfield',
                        id: config.id + '-balance-paid',
                        fieldLabel: _('partnerprogram_balance_paid'),
                        name: 'paid',
                        anchor: '99%'
                    }],
                }]
            }, {
                xtype: 'textarea',
                id: config.id + '-description',
                fieldLabel: _('partnerprogram_description'),
                name: 'description',
                anchor: '99%',
            }
        ];
    },

});
Ext.reg('partnerprogram-window-balance-create', partnerProgram.window.CreateBalance);


partnerProgram.window.UpdateBalance = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('partnerprogram_menu_update'),
        baseParams: {
            action: 'mgr/balance/update',
        },
    });
    partnerProgram.window.UpdateBalance.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.UpdateBalance, partnerProgram.window.CreateBalance);
Ext.reg('partnerprogram-window-balance-update', partnerProgram.window.UpdateBalance);