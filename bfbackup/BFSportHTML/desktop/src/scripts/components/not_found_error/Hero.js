require.config({
    baseUrl: 'src/scripts'
});

define(
    ['components/not_found_error/Football'],

    function (Football) {

        var Hero = Hilo.Class.create({
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
                running.addFrame(properties.assetHeroAtlas.getSprite('running'));
                running.interval = 4;
                running.pivotX = 20;
                running.pivotY = 98;
                var football = new Football({assetFootball: properties.assetFootball});
                football.y = -20;
                this.runningView.addChild(football, this.createShadow(-16, -4, running.width - 2, 6, true), running);

                this.stopView = new Hilo.Container({visible: false});
                var stopBitmap = new Hilo.Bitmap({image: properties.assetHeroStop});
                stopBitmap.pivotX = 26;
                stopBitmap.pivotY = 88;
                this.stopView.addChild(this.createShadow(-16, -4, running.width - 2, 6), stopBitmap);

                this.addChild(this.runningView, this.stopView);
            },

            swapState: function (direction) {
                this.runningView.visible = !this.runningView.visible;
                this.stopView.visible = !this.stopView.visible;
                if (direction) {
                    this.stopView.scaleX = -1;
                } else {
                    this.stopView.scaleX = 1;
                }
            },

            createShadow: function (x, y, w, h, tween) {
                var shadow = new Hilo.Graphics();
                shadow.beginFill('#B2B2B2', 0.6);
                shadow.drawEllipse(0, 0, w, h);
                shadow.endFill();
                shadow.x = x;
                shadow.y = y;
                if (tween) {
                    Hilo.Tween.to(shadow, {scaleX: 0.8, scaleY: 0.8}, {
                        duration: 300,
                        loop: true,
                        ease: Hilo.Ease.Bounce.EaseInOut
                    });
                }
                return shadow;
            }
        });

        return Hero;

    }
);