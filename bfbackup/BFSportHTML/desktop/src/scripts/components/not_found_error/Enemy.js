define(
    function () {

        var Enemy = Hilo.Class.create({
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
                running.addFrame(properties.assetEnemyAtlas.getSprite('running'));
                running.interval = 4;
                running.pivotX = 27;
                running.pivotY = 96;
                this.runningView.addChild(this.createShadow(-16, -4, running.width - 2, 6, true), running);

                this.stopView = new Hilo.Container({visible: false});
                var stopBitmap = new Hilo.Bitmap({image: properties.assetEnemyStop});
                stopBitmap.pivotX = 27;
                stopBitmap.pivotY = 96;
                this.stopView.addChild(this.createShadow(-16, -4, running.width - 2, 6), stopBitmap);

                this.addChild(this.runningView, this.stopView);
            },

            swapState: function () {
                this.runningView.visible = !this.runningView.visible;
                this.stopView.visible = !this.stopView.visible;
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

        return Enemy;

    }
);