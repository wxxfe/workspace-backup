require.config({
    baseUrl: 'src/scripts'
});

requirejs(
    ['components/not_found_error/game'],

    function (game) {
        game.init();
    }
);