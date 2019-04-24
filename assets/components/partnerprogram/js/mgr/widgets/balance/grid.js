partnerProgram.grid.Balance = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'partnerprogram-grid-balance';
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/balance/getlist',
            sort: 'user_id',
            dir: 'asc',
        },
        stateful: true,
        stateId: config.id,
        ddGroup: 'partnerprogram-settings-balance',
    });
    partnerProgram.grid.Balance.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.grid.Balance, partnerProgram.grid.Default, {

    getFields: function () {
        return [
            'id', 'user_id', 'balance', 'possible_balance', 'paid_balance', 'paid',
            'phone', 'card', 'description', 'customer', 'customer_username'
        ];
    },

    getColumns: function () {
        return [
            {header: _('partnerprogram_balance_id'), dataIndex: 'id', width: 30},
            {header: _('partnerprogram_balance_user_id'), dataIndex: 'customer', width: 100, renderer: function(val, cell, row) {
                return partnerProgram.utils.userLink(val, row.data['user_id'], true);
            }},
            {header: _('partnerprogram_balance'), dataIndex: 'balance', width: 50},
            {header: _('partnerprogram_balance_possible_balance'), dataIndex: 'possible_balance', width: 50},
            {header: _('partnerprogram_balance_paid_balance'), dataIndex: 'paid_balance', width: 50},
            {header: _('partnerprogram_balance_paid'), dataIndex: 'paid', width: 50},
            {header: _('partnerprogram_balance_phone'), dataIndex: 'phone', width: 50},
            {header: _('partnerprogram_balance_card'), dataIndex: 'card', width: 50},
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
            handler: this.createBalance,
            scope: this
        }, '->', this.getSearchField()];
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateBalance(grid, e, row);
            },
        };
    },

    balanceAction: function (method) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: partnerProgram.config['connector_url'],
            params: {
                action: 'mgr/balance/multiple',
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

    createBalance: function (btn, e) {
        var w = Ext.getCmp('partnerprogram-window-balance-create');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-balance-create',
            id: 'partnerprogram-window-balance-create',
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

    updateBalance: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }

        var w = Ext.getCmp('partnerprogram-window-balance-update');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-balance-update',
            id: 'partnerprogram-window-balance-update',
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

    removeBalance: function () {
        var ids = this._getSelectedIds();

        Ext.MessageBox.confirm(
            _('partnerprogram_menu_remove_title'),
            ids.length > 1
                ? _('partnerprogram_menu_remove_multiple_confirm')
                : _('partnerprogram_menu_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    this.balanceAction('remove');
                }
            }, this
        );
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
Ext.reg('partnerprogram-grid-balance', partnerProgram.grid.Balance);