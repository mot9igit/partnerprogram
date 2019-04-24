partnerProgram.grid.Rewards = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'partnerprogram-grid-rewards';
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/rewards/getlist',
            sort: 'id',
            dir: 'asc',
        },
        stateful: true,
        stateId: config.id
    });
    partnerProgram.grid.Rewards.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.grid.Rewards, partnerProgram.grid.Default, {

    getFields: function () {
        return [
            'id', 'user_id', 'description', 'object_id', 'sum', 'customer', 'actions', 'object_name'
        ];
    },

    getColumns: function () {
        return [
            {header: _('partnerprogram_rewards_id'), dataIndex: 'id', width: 30},
            {header: _('partnerprogram_rewards_user_id'), dataIndex: 'customer', width: 30, renderer: function(val, cell, row) {
                return partnerProgram.utils.userLink(val, row.data['user_id'], true);
            }},
            {header: _('partnerprogram_rewards_description'), dataIndex: 'description', width: 30},
            {header: _('partnerprogram_rewards_object_id'), dataIndex: 'object_name', width: 30},
            {header: _('partnerprogram_rewards_sum'), dataIndex: 'sum', width: 30},
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
            handler: this.createRewards,
            scope: this
        }, '->', this.getSearchField()];
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateRewards(grid, e, row);
            },
        };
    },

    rewardsAction: function (method) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: partnerProgram.config['connector_url'],
            params: {
                action: 'mgr/rewards/multiple',
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

    createRewards: function (btn, e) {
        var w = Ext.getCmp('partnerprogram-window-rewards-create');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-rewards-create',
            id: 'partnerprogram-window-rewards-create',
            record: {

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

    updateRewards: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }

        var w = Ext.getCmp('partnerprogram-window-rewards-update');
        if (w) {
            w.close();
        }
        w = MODx.load({
            xtype: 'partnerprogram-window-rewards-update',
            id: 'partnerprogram-window-rewards-update',
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

    removeRewards: function () {
        var ids = this._getSelectedIds();

        Ext.MessageBox.confirm(
            _('partnerprogram_menu_remove_title'),
            ids.length > 1
                ? _('partnerprogram_menu_remove_multiple_confirm')
                : _('partnerprogram_menu_remove_confirm'),
            function (val) {
                if (val == 'yes') {
                    this.rewardsAction('remove');
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
Ext.reg('partnerprogram-grid-rewards', partnerProgram.grid.Rewards);