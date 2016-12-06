(function (ns) {

    var Enemy = ns.Enemy = Hilo.Class.create({
        Extends: Hilo.Container,
        constructor: function (properties) {
            Enemy.superclass.constructor.call(this, properties);
            this.init(properties);

        },

        runningView: null,
        stopView: null,

        init: function (properties) {
            this.runningView = new Hilo.Container();
            var running = new Hilo.Sprite();
            running.addFrame(ns.asset.enemyAtlas.getSprite('running'));
            running.interval = 4;
            running.pivotX = 27;
            running.pivotY = 96;
            this.runningView.addChild(ns.createShadow(-16, -4, running.width - 2, 6, true), running);
            
            this.stopView = new Hilo.Container({visible: false});
            var stopBitmap = new Hilo.Bitmap({image: ns.asset.enemyStop});
            stopBitmap.pivotX = 27;
            stopBitmap.pivotY = 96;
            this.stopView.addChild(ns.createShadow(-16, -4, running.width - 2, 6), stopBitmap);

            this.addChild(this.runningView, this.stopView);
        },

        swapState: function () {
            this.runningView.visible = !this.runningView.visible;
            this.stopView.visible = !this.stopView.visible;
        }

    });

})(window.game);