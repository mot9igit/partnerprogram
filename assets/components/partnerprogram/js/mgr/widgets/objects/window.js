partnerProgram.window.CreateObject = function (config) {
    config = config || {};
    this.ident = config.ident || 'mecitem' + Ext.id();
    Ext.applyIf(config, {
        title: _('partnerprogram_menu_create'),
        width: 600,
        baseParams: {
            action: 'mgr/object/create',
        },
    });
    partnerProgram.window.CreateObject.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.CreateObject, partnerProgram.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {
                xtype: 'textfield',
                id: config.id + '-name',
                fieldLabel: _('partnerprogram_name'),
                name: 'name',
                anchor: '99%',
            }, {
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    items: [{
                        xtype: 'partnerprogram-combo-user',
                        id: config.id + '-user_id',
                        fieldLabel: _('partnerprogram_object_user_id'),
                        name: 'user_id',
                        anchor: '99%'
                    },{
                        xtype: 'textfield',
                        id: config.id + '-object-area',
                        fieldLabel: _('partnerprogram_object_area'),
                        name: 'area',
                        anchor: '99%'
                    }, {
                        xtype: 'textfield',
                        id: config.id + '-object-locality',
                        fieldLabel: _('partnerprogram_object_locality'),
                        name: 'locality',
                        anchor: '99%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: _('partnerprogram_object_house'),
                        name: 'house',
                        id: config.id + '-object-house',
                        anchor: '99%'
                    }, {
                        xtype: 'xdatetime',
                        fieldLabel: _('partnerprogram_object_createdon'),
                        name: 'createdon',
                        id: config.id + '-object-createdon',
                        anchor: '99%'
                    }],
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    items: [{
                        xtype: 'partnerprogram-combo-status',
                        id: config.id + '-object-status',
                        fieldLabel: _('partnerprogram_object_status'),
                        name: 'coordinates',
                        anchor: '99%'
                    },{
                        xtype: 'textfield',
                        id: config.id + '-object-coordinates',
                        fieldLabel: _('partnerprogram_object_coordinates'),
                        name: 'coordinates',
                        anchor: '99%'
                    }, {
                        xtype: 'textfield',
                        id: config.id + '-object-city',
                        fieldLabel: _('partnerprogram_object_city'),
                        name: 'city',
                        anchor: '99%'
                    }, {
                        xtype: 'textfield',
                        id: config.id + '-object-street',
                        fieldLabel: _('partnerprogram_object_street'),
                        name: 'street',
                        anchor: '99%'
                    }, {
                        xtype: 'textfield',
                        id: config.id + '-object-customer',
                        fieldLabel: _('partnerprogram_object_customer'),
                        name: 'customer',
                        anchor: '99%'
                    }, {
                        xtype: 'xdatetime',
                        fieldLabel: _('partnerprogram_object_updatedon'),
                        name: 'updatedon',
                        id: config.id + '-object-updatedon',
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
    }

});
Ext.reg('partnerprogram-window-object-create', partnerProgram.window.CreateObject);


partnerProgram.window.UpdateObject = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('partnerprogram_menu_update'),
        baseParams: {
            action: 'mgr/object/update',
        },
    });
    partnerProgram.window.UpdateObject.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.UpdateObject, partnerProgram.window.CreateObject);
Ext.reg('partnerprogram-window-object-update', partnerProgram.window.UpdateObject);