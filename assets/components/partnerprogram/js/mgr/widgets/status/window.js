partnerProgram.window.CreateStatus = function (config) {
    config = config || {};
    this.ident = config.ident || 'mecitem' + Ext.id();
    Ext.applyIf(config, {
        title: _('partnerprogram_menu_create'),
        width: 600,
        baseParams: {
            action: 'mgr/status/create',
        },
    });
    partnerProgram.window.CreateStatus.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.CreateStatus, partnerProgram.window.Default, {

    getFields: function (config) {
        return [
            {xtype: 'hidden', name: 'id', id: config.id + '-id'},
            {xtype: 'hidden', name: 'color', id: config.id + '-color'},
            {
                xtype: 'textfield',
                id: config.id + '-name',
                fieldLabel: _('partnerprogram_name'),
                name: 'name',
                anchor: '99%',
            }, {
                xtype: 'colorpalette', fieldLabel: _('partnerprogram_color'),
                id: config.id + '-color-palette',
                listeners: {
                    select: function (palette, color) {
                        Ext.getCmp(config.id + '-color').setValue(color)
                    },
                    beforerender: function (palette) {
                        if (config.record['color'] != undefined) {
                            palette.value = config.record['color'];
                        }
                    }
                },
            }, {
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    items: [{
                        xtype: 'xcheckbox',
                        id: config.id + '-email-user',
                        boxLabel: _('partnerprogram_email_user'),
                        name: 'email_user',
                        listeners: {
                            check: {
                                fn: function (checkbox) {
                                    this.handleStatusFields(checkbox);
                                }, scope: this
                            },
                            afterrender: {
                                fn: function (checkbox) {
                                    this.handleStatusFields(checkbox);
                                }, scope: this
                            }
                        },
                    }, {
                        xtype: 'textfield',
                        id: config.id + '-subject-user',
                        fieldLabel: _('partnerprogram_subject_user'),
                        name: 'subject_user',
                        anchor: '99%'
                    }, {
                        xtype: 'partnerprogram-combo-chunk',
                        fieldLabel: _('partnerprogram_body_user'),
                        name: 'body_user',
                        id: config.id + '-body-user',
                        anchor: '99%'
                    }],
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    items: [{
                        xtype: 'xcheckbox',
                        id: config.id + '-email-manager',
                        boxLabel: _('partnerprogram_email_manager'),
                        name: 'email_manager',
                        listeners: {
                            check: {
                                fn: function (checkbox) {
                                    this.handleStatusFields(checkbox);
                                }, scope: this
                            },
                            afterrender: {
                                fn: function (checkbox) {
                                    this.handleStatusFields(checkbox);
                                }, scope: this
                            }
                        },
                    }, {
                        xtype: 'textfield',
                        id: config.id + '-subject-manager',
                        fieldLabel: _('partnerprogram_subject_manager'),
                        name: 'subject_manager',
                        anchor: '99%'
                    }, {
                        xtype: 'partnerprogram-combo-chunk',
                        id: config.id + '-body-manager',
                        fieldLabel: _('partnerprogram_body_manager'),
                        name: 'body_manager',
                        anchor: '99%'
                    }],
                }]
            }, {
                xtype: 'textarea',
                id: config.id + '-description',
                fieldLabel: _('partnerprogram_description'),
                name: 'description',
                anchor: '99%',
            }, {
                xtype: 'checkboxgroup',
                hideLabel: true,
                columns: 3,
                items: [{
                    xtype: 'xcheckbox',
                    id: config.id + '-active',
                    boxLabel: _('partnerprogram_active'),
                    name: 'active',
                    checked: parseInt(config.record['active']),
                }, {
                    xtype: 'xcheckbox',
                    id: config.id + '-final',
                    boxLabel: _('partnerprogram_status_final'),
                    description: _('partnerprogram_status_final_help'),
                    name: 'final',
                    checked: parseInt(config.record['final']),
                }, {
                    xtype: 'xcheckbox',
                    id: config.id + '-fixed',
                    boxLabel: _('partnerprogram_status_fixed'),
                    description: _('partnerprogram_status_fixed_help'),
                    name: 'fixed',
                    checked: parseInt(config.record['fixed']),
                }]
            }
        ];
    },

    handleStatusFields: function (checkbox) {
        var type = checkbox.name.replace(/(^.*?_)/, '');

        var subject = Ext.getCmp(this.config.id + '-subject-' + type);
        var body = Ext.getCmp(this.config.id + '-body-' + type);
        if (checkbox.checked) {
            subject.enable().show();
            body.enable().show();
        }
        else {
            subject.hide().disable();
            body.hide().disable();
        }
    },

});
Ext.reg('partnerprogram-window-status-create', partnerProgram.window.CreateStatus);


partnerProgram.window.UpdateStatus = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('partnerprogram_menu_update'),
        baseParams: {
            action: 'mgr/status/update',
        },
    });
    partnerProgram.window.UpdateStatus.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.window.UpdateStatus, partnerProgram.window.CreateStatus);
Ext.reg('partnerprogram-window-status-update', partnerProgram.window.UpdateStatus);