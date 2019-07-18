partnerProgram.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    partnerProgram.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(partnerProgram.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },

    _triggerSearch: function () {
        this.fireEvent('search', this);
    },

    _triggerClear: function () {
        this.fireEvent('clear', this);
    },

});
Ext.reg('partnerprogram-combo-search', partnerProgram.combo.Search);
Ext.reg('partnerprogram-field-search', partnerProgram.combo.Search);


partnerProgram.combo.User = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'user',
        fieldLabel: config.name || 'createdby',
        hiddenName: config.name || 'createdby',
        displayField: 'fullname',
        valueField: 'id',
        anchor: '99%',
        fields: ['username', 'id', 'fullname'],
        pageSize: 20,
        typeAhead: false,
        editable: true,
        allowBlank: false,
        url: partnerProgram.config['connector_url'],
        baseParams: {
            action: 'mgr/user/getlist',
            combo: true,
        },
        tpl: new Ext.XTemplate('\
            <tpl for=".">\
                <div class="x-combo-list-item">\
                    <span>\
                        <small>({id})</small>\
                        <b>{username}</b>\
                        <tpl if="fullname && fullname != username"> - {fullname}</tpl>\
                    </span>\
                </div>\
            </tpl>',
            {compiled: true}
        ),
    });
    partnerProgram.combo.User.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.combo.User, MODx.combo.ComboBox);
Ext.reg('partnerprogram-combo-user', partnerProgram.combo.User);


partnerProgram.combo.DateTime = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        timePosition: 'right',
        allowBlank: true,
        hiddenFormat: 'Y-m-d H:i:s',
        dateFormat: MODx.config['manager_date_format'],
        timeFormat: MODx.config['manager_time_format'],
        dateWidth: 120,
        timeWidth: 120
    });
    partnerProgram.combo.DateTime.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.combo.DateTime, Ext.ux.form.DateTime);
Ext.reg('partnerprogram-xdatetime', partnerProgram.combo.DateTime);

partnerProgram.combo.Chunk = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'chunk',
        hiddenName: config.name || 'chunk',
        displayField: 'name',
        valueField: 'id',
        editable: true,
        fields: ['id', 'name'],
        pageSize: 20,
        emptyText: _('partnerprogram_combo_select'),
        hideMode: 'offsets',
        url: partnerProgram.config['connector_url'],
        baseParams: {
            action: 'mgr/chunk/getlist',
            mode: 'chunks'
        }
    });
    partnerProgram.combo.Chunk.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.combo.Chunk, MODx.combo.ComboBox);
Ext.reg('partnerprogram-combo-chunk', partnerProgram.combo.Chunk);

partnerProgram.combo.listeners_disable = {
    render: function () {
        this.store.on('load', function () {
            if (this.store.getTotalCount() == 1 && this.store.getAt(0).id == this.value) {
                this.readOnly = true;
                this.addClass('disabled');
            }
            else {
                this.readOnly = false;
                this.removeClass('disabled');
            }
        }, this);
    }
};


partnerProgram.combo.Status = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        name: 'status',
        id: 'partnerprogram-combo-status',
        hiddenName: 'status',
        displayField: 'name',
        valueField: 'id',
        fields: ['id', 'name'],
        pageSize: 10,
        emptyText: _('partnerprogram_combo_select_status'),
        url: partnerProgram.config['connector_url'],
        baseParams: {
            action: 'mgr/status/getlist',
            combo: true,
            addall: config.addall || 0,
            order_id: config.order_id || 0
        },
        listeners: partnerProgram.combo.listeners_disable
    });
    partnerProgram.combo.Status.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.combo.Status, MODx.combo.ComboBox);
Ext.reg('partnerprogram-combo-status', partnerProgram.combo.Status);


partnerProgram.combo.Object = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        name: 'object',
        id: 'partnerprogram-combo-object',
        hiddenName: 'object_id',
        displayField: 'name',
        valueField: 'id',
        fields: ['id', 'name'],
        pageSize: 10,
        emptyText: _('partnerprogram_combo_select_object'),
        url: partnerProgram.config['connector_url'],
        baseParams: {
            action: 'mgr/object/getlist',
            combo: true,
            addall: config.addall || 0,
            object_id: config.object_id || 0
        },
        listeners: partnerProgram.combo.listeners_disable
    });
    partnerProgram.combo.Object.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram.combo.Object, MODx.combo.ComboBox);
Ext.reg('partnerprogram-combo-object', partnerProgram.combo.Object);

partnerProgram.combo.typepol = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.ArrayStore({
            id: 0
            ,fields: ['typepol','display']
            ,emptyText: _('partnerprogram_combo_select_typepol')
            ,data: [
                [1,'Топпинговые']
                ,[2,'Полимерные']
            ]
        })
        ,mode: 'local'
        ,hiddenName: 'typepol'
        ,displayField: 'display'
        ,valueField: 'typepol'
    });
    partnerProgram.combo.typepol.superclass.constructor.call(this,config);
};
Ext.extend(partnerProgram.combo.typepol,MODx.combo.ComboBox);
Ext.reg('combo-typepol',partnerProgram.combo.typepol);