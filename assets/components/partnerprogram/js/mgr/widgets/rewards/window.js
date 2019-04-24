partnerProgram.window.CreateRewards = function (config) {
    config = config || {};
    this.ident = config.ident || 'mecitem' + Ext.id();
    Ext.applyIf(config, {
        title: _('partnerprogram_menu_create'),
        width: 600,
        baseParams: {
            action: 'mgr/rewards/create',
        },
    });
    partnerProgram.window.CreateRewards.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.CreateRewards, partnerProgram.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'textfield',
                id: config.id + '-name',
                fieldLabel: _('partnerprogram_name'),
                name: 'name',
                anchor: '99%',
            },{
                xtype: 'partnerprogram-combo-object',
                id: config.id + '-object_id',
                fieldLabel: _('partnerprogram_rewards_object_id'),
                name: 'object_id',
                anchor: '99%'
            },{
                xtype: 'partnerprogram-combo-user',
                id: config.id + '-user_id',
                fieldLabel: _('partnerprogram_rewards_user_id'),
                name: 'user_id',
                anchor: '99%'
            },{
                xtype: 'textfield',
                id: config.id + '-sum',
                fieldLabel: _('partnerprogram_rewards_sum'),
                name: 'sum',
                anchor: '99%',
            }, {
                xtype: 'textarea',
                id: config.id + '-description',
                fieldLabel: _('partnerprogram_description'),
                name: 'description',
                anchor: '99%',
            }
        ];
    }

});
Ext.reg('partnerprogram-window-rewards-create', partnerProgram.window.CreateRewards);


partnerProgram.window.UpdateRewards = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('partnerprogram_menu_update'),
        baseParams: {
            action: 'mgr/rewards/update',
        },
    });
    partnerProgram.window.UpdateRewards.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.UpdateRewards, partnerProgram.window.CreateRewards);
Ext.reg('partnerprogram-window-rewards-update', partnerProgram.window.UpdateRewards);