(function (ns) {

    var Hero = ns.Hero = Hilo.Class.create({
        Extends: Hilo.Container,
        constructor: function (properties) {
            Hero.superclass.constructor.call(this, properties);
            this.init(properties);
        },

        runningView: null,
        stopView: null,

        init: function (properties) {
            this.runningView = new Hilo.Container();
            var running = new Hilo.Sprite();
            running.addFrame(ns.asset.heroAtlas.getSprite('running'));
            running.interval = 4;
            running.pivotX = 20;
            running.pivotY = 98;
            var football = new ns.Football();
            football.y = -20;
            this.runningView.addChild(football, ns.createShadow(-16,-4, running.width - 2, 6,true), running);

            this.stopView = new Hilo.Container({visible: false});
            var stopBitmap = new Hilo.Bitmap({image: ns.asset.heroStop});
            stopBitmap.pivotX = 26;
            stopBitmap.pivotY = 88;
            this.stopView.addChild(ns.createShadow(-16, -4, running.width - 2, 6), stopBitmap);

            this.addChild(this.runningView, this.stopView);
        },

        swapState: function (direction) {
            this.runningView.visible = !this.runningView.visible;
            this.stopView.visible = !this.stopView.visible;
            if(direction){
                this.stopView.scaleX = -1;
            }else{
                this.stopView.scaleX = 1;
            }
        }
    });

})(window.game);