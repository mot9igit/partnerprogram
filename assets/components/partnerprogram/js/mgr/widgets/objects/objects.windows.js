partnerProgram.window.CreateObject = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'partnerprogram-object-window-create';
    }
    Ext.applyIf(config, {
        title: _('partnerprogram_object_create'),
        width: 550,
        autoHeight: true,
        url: partnerProgram.config.connector_url,
        action: 'mgr/object/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    partnerProgram.window.CreateObject.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.CreateObject, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('partnerprogram_object_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('partnerprogram_object_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('partnerprogram_object_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('partnerprogram-object-window-create', partnerProgram.window.CreateObject);


partnerProgram.window.UpdateObject = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'partnerprogram-object-window-update';
    }
    Ext.applyIf(config, {
        title: _('partnerprogram_object_update'),
        width: 550,
        autoHeight: true,
        url: partnerProgram.config.connector_url,
        action: 'mgr/object/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    partnerProgram.window.UpdateObject.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.UpdateObject, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'textfield',
            fieldLabel: _('partnerprogram_object_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('partnerprogram_object_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('partnerprogram_object_active'),
            name: 'active',
            id: config.id + '-active',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('partnerprogram-object-window-update', partnerProgram.window.UpdateObject);