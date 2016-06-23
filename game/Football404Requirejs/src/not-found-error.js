requirejs.config({
    baseUrl: 'src'
});


requirejs(
    ['game'],

    function (game) {
        game.init();
    }
);