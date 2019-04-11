Ext.onReady(function () {
    partnerProgram.config.connector_url = OfficeConfig.actionUrl;

    var grid = new partnerProgram.panel.Home();
    grid.render('office-partnerprogram-wrapper');

    var preloader = document.getElementById('office-preloader');
    if (preloader) {
        preloader.parentNode.removeChild(preloader);
    }
});