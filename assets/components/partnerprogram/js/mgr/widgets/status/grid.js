partnerProgram.grid.Status = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'partnerprogram-grid-status';
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/status/getlist',
            sort: 'rank',
            dir: 'asc',
        },
        stateful: true,
        stateId: config.id,
        ddGroup: 'partnerprogram-settings-status',
        ddAction: 'mgr/status/sort',
        enableDragDrop: true,
        multi_select: true,
    });
    partnerProgram.grid.Status.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.grid.Status, partnerProgram.grid.Default, {

    getFields: function () {
        return [
            'id', 'name', 'description', 'color', 'email_user', 'email_manager',
            'subject_user', 'subject_manager', 'body_user', 'body_manager', 'active',
            'final', 'fixed', 'rank', 'editable', 'actions'
        ];
    },

    getColumns: function () {
        return [
            {header: _('partnerprogram_status_id'), dataIndex: 'id', width: 30},
            {header: _('partnerprogram_status_name'), dataIndex: 'name', width: 50, renderer: this._renderColor},
            {header: _('partnerprogram_status_email_user'), dataIndex: 'email_user', width: 50, renderer: this._renderBoolean},
            {header: _('partnerprogram_status_email_manager'), dataIndex: 'email_manager', width: 50, renderer: this._renderBoolean},
            {header: _('partnerprogram_status_final'), dataIndex: 'final', width: 50, renderer: this._renderBoolean},
            {header: _('partnerprogram_status_fixed'), dataIndex: 'fixed', width: 50, renderer: this._renderBoolean},
            {header: _('partnerprogram_status_rank'), dataIndex: 'rank', width: 35, hidden: true},
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
            handler: this.createStatus,
            scope: this
        }, '->', this.getSearchField()];
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateStatus(grid, e, row);
            },
        };
    },

    statusAction: function (method) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: partnerProgram.config['connector_url'],
            params: {
                action: 'mgr/status/multiple',
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

    createStatus: function (btn, e) {
        var w = Ext.getCmp('partnerprogram-window-status-create');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-status-create',
            id: 'partnerprogram-window-status-create',
            record: {
                color: '000000',
                active: 1
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

    updateStatus: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }

        var w = Ext.getCmp('partnerprogram-window-status-update');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-status-update',
            id: 'partnerprogram-window-status-update',
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

    enableStatus: function () {
        this.statusAction('enable');
    },

    disableStatus: function () {
        this.statusAction('disable');
    },

    removeStatus: function () {
        var ids = this._getSelectedIds();

        Ext.MessageBox.confirm(
            _('partnerprogram_menu_remove_title'),
            ids.length > 1
                ? _('partnerprogram_menu_remove_multiple_confirm')
                : _('partnerprogram_menu_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    this.statusAction('remove');
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
Ext.reg('partnerprogram-grid-status', partnerProgram.grid.Status);