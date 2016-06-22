(function (ns) {

    var Football = ns.Football = Hilo.Class.create({
        Extends: Hilo.Container,
        constructor: function (properties) {
            Football.superclass.constructor.call(this, properties);
            this.init(properties);
        },

        init: function (properties) {
            var ball = new Hilo.Bitmap({
                image: ns.asset.football
            });
            ball.pivotX = 10;
            ball.pivotY = 10;

            this.addChild(ns.createShadow(-8,ball.height - 14, ball.width - 2, 6,true), ball);

            Hilo.Tween.to(ball, {y: -3, rotation: 360}, {duration: 300, loop: true, ease: Hilo.Ease.Bounce.EaseInOut});

        }
    });

})(window.game);