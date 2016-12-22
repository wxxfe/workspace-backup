define(
    function () {

        var Football = Hilo.Class.create({
            Extends: Hilo.Container,
            constructor: function (properties) {
                Football.superclass.constructor.call(this, properties);
                this.init(properties);
            },

            init: function (properties) {
                var ball = new Hilo.Bitmap({
                    image: properties.assetFootball
                });
                ball.pivotX = 10;
                ball.pivotY = 10;

                this.addChild(this.createShadow(-8, ball.height - 14, ball.width - 2, 6, true), ball);

                Hilo.Tween.to(ball, {y: -3, rotation: 360}, {
                    duration: 300,
                    loop: true,
                    ease: Hilo.Ease.Bounce.EaseInOut
                });

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

        return Football;

    }
);



