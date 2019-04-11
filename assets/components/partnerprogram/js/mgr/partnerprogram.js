var partnerProgram = function (config) {
    config = config || {};
    partnerProgram.superclass.constructor.call(this, config);
};
Ext.extend(partnerProgram, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('partnerprogram', partnerProgram);

partnerProgram = new partnerProgram();