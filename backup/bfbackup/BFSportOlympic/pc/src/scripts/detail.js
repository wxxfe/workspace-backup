require.config({
    baseUrl: 'src/scripts'
});

require(['components/navigation'], function (Navigation) {
    Navigation.init();
});

