partnerProgram.grid.History = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'partnerprogram-grid-history';
    }

    Ext.applyIf(config, {
        baseParams: {
            action: 'mgr/history/getlist',
            sort: 'id',
            dir: 'asc',
        },
        stateful: true,
        stateId: config.id
    });
    partnerProgram.grid.History.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.grid.History, partnerProgram.grid.Default, {

    getFields: function () {
        return [
            'id', 'user_id', 'object_id', 'description', 'timestamp', 'action',
            'entry', 'ip', 'customer', 'object_name'
        ];
    },

    getColumns: function () {
        return [
            {header: _('partnerprogram_history_id'), dataIndex: 'id', width: 30},
            {header: _('partnerprogram_history_user_id'), dataIndex: 'customer', width: 100, renderer: function(val, cell, row) {
                return partnerProgram.utils.userLink(val, row.data['user_id'], true);
            }},
            {header: _('partnerprogram_history_object_id'), dataIndex: 'object_name', width: 100},
            {header: _('partnerprogram_history_timestamp'), dataIndex: 'timestamp', width: 100},
            {header: _('partnerprogram_history_action'), dataIndex: 'action', width: 100}
        ];
    },

    getTopBar: function () {
        return ['->', this.getSearchField()];
    },

    getListeners: function () {
        return {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateStatus(grid, e, row);
            },
        };
    }
});
Ext.reg('partnerprogram-grid-history', partnerProgram.grid.History);