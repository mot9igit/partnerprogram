partnerProgram.grid.Objects = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'partnerprogram-grid-objects';
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/object/getlist',
            sort: 'id',
            dir: 'asc',
        },
        stateful: true,
        stateId: config.id,
        ddGroup: 'partnerprogram-settings-object',
        ddAction: 'mgr/object/sort',
        enableDragDrop: true,
        multi_select: true,
    });
    partnerProgram.grid.Objects.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.grid.Objects, partnerProgram.grid.Default, {

    getFields: function () {
        return [
            'id', 'name', 'area', 'locality', 'city', 'street', 'house',
            'coordinates', 'customer', 'contact_name', 'contact_email', 'contact_phone',
            'description', 'createdon', 'updatedon', 'active', 'status', 'actions', 'status_row', 'user_id'
        ];
    },

    getColumns: function () {
        return [
            {header: _('partnerprogram_object_id'), dataIndex: 'id', width: 30},
            {header: _('partnerprogram_object_name'), dataIndex: 'name', width: 50, renderer: this._renderColor},
            {header: _('partnerprogram_object_area'), dataIndex: 'area', width: 50},
            {header: _('partnerprogram_object_createdon'), dataIndex: 'createdon', width: 50},
            {header: _('partnerprogram_object_user_id'), dataIndex: 'customer', width: 100, renderer: function(val, cell, row) {
                    return partnerProgram.utils.userLink(val, row.data['user_id'], true);
                }},
            {header: _('partnerprogram_object_status'), dataIndex: 'status_row', width: 50},
            {
                header: _('partnerprogram_actions'),
                dataIndex: 'actions',
                id: 'actions',
                width: 50,
                renderer: partnerProgram.utils.renderActions
            }
        ];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i> ' + _('partnerprogram_btn_create'),
            handler: this.createObject,
            scope: this
        }, '->', this.getSearchField()];
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateObject(grid, e, row);
            },
        };
    },

    objectAction: function (method) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: partnerProgram.config['connector_url'],
            params: {
                action: 'mgr/object/multiple',
                method: method,
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        //noinspection JSUnresolvedFunction
                        this.refresh();
                    }, scope: this
                },
                failure: {
                    fn: function (response) {
                        MODx.msg.alert(_('error'), response.message);
                    }, scope: this
                },
            }
        })
    },

    createObject: function (btn, e) {
        var w = Ext.getCmp('partnerprogram-window-object-create');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-object-create',
            id: 'partnerprogram-window-object-create',
            record: {
                active: 1,
                status: 1
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.show(e.target);
    },

    updateObject: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }

        var w = Ext.getCmp('partnerprogram-window-object-update');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-object-update',
            id: 'partnerprogram-window-object-update',
            title: this.menu.record['name'],
            record: this.menu.record,
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.fp.getForm().reset();
        w.fp.getForm().setValues(this.menu.record);
        w.show(e.target);
    },

    enableObject: function () {
        this.objectAction('enable');
    },

    disableObject: function () {
        this.objectAction('disable');
    },

    removeObject: function () {
        var ids = this._getSelectedIds();

        Ext.MessageBox.confirm(
            _('partnerprogram_menu_remove_title'),
            ids.length > 1
                ? _('partnerprogram_menu_remove_multiple_confirm')
                : _('partnerprogram_menu_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    this.objectAction('remove');
                }
            }, this
        );
    },

    _renderColor: function (value, cell, row) {
        //noinspection CssInvalidPropertyValue
        return row.data['active']
            ? String.format('<span style="color:#{0}">{1}</span>', row.data['color'], value)
            : value;
    },

    _renderBoolean: function(value, cell, row) {
        var color, text;

        if (value == 0 || value == false || value == undefined) {
            color = 'red';
            text = _('no');
        }
        else {
            color = 'green';
            text = _('yes');
        }

        return row.data['active']
            ? String.format('<span class="{0}">{1}</span>', color, text)
            : text;
    },
});
Ext.reg('partnerprogram-grid-objects', partnerProgram.grid.Objects);