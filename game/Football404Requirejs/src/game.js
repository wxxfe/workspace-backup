define(
    ['Asset', 'Football', 'Hero', 'Enemy'],

    function (Asset, Football, Hero, Enemy) {


        var game = {
            stage: null,
            scoreView: null,
            groundView: null,
            readyView: null,
            overView: null,
            heroView: null,
            enemyView: null,
            width: 0,
            height: 0,
            asset: null,
            ticker: null,
            state: null,
            score: 0,
            moveDirection: 0,
            scaleFactor: 0,
            groundY: 0,
            heroY: 0,
            swapDone: false,
            tweening: false,

            init: function () {
                this.asset = new Asset();
                this.asset.on('complete', function (e) {
                    this.asset.off('complete');
                    this.initStage();
                }.bind(this));
                this.asset.load();
            },

            initStage: function () {
                var container = document.getElementById('container');
                container.innerHTML = '';

                this.width = 550;
                this.height = 400;
                this.scale = 1;

                this.groundY = 148;
                this.heroY = 370;

                //舞台
                var canvasType = location.search.indexOf('dom') != -1 ? 'div' : 'canvas';
                var canvas = Hilo.createElement(canvasType, {
                    style: {
                        position: 'absolute',
                        width: this.width * this.scale + 'px',
                        height: this.height * this.scale + 'px'
                    }
                });
                this.stage = new Hilo.Stage({
                    canvas: canvas,
                    width: this.width,
                    height: this.height,
                    scaleX: this.scale,
                    scaleY: this.scale
                }).addTo(container);

                //启动计时器
                this.ticker = new Hilo.Ticker(60);
                this.ticker.addTick(Hilo.Tween);
                this.ticker.addTick(this.stage);
                this.ticker.start();

                //绑定交互事件
                this.stage.enableDOMEvent(Hilo.event.POINTER_START, true);
                this.stage.on(Hilo.event.POINTER_START, function (e) {
                    this.gameReadyOut();
                    this.gameOverOut();
                }.bind(this));

                //键控制

                if (document.addEventListener) {
                    document.addEventListener('keydown', this.keydownHandle.bind(this));
                    document.addEventListener('keyup', this.keyupHandle.bind(this));
                } else {
                    document.attachEvent('onkeydown', this.keydownHandle.bind(this));
                    document.attachEvent('onkeyup', this.keyupHandle.bind(this));
                }

                //舞台更新
                this.stage.onUpdate = this.onUpdate.bind(this);

                //初始化
                this.initBackgroundView();

                this.enemyView = new Enemy({
                    assetEnemyAtlas: this.asset.enemyAtlas,
                    assetEnemyStop: this.asset.enemyStop,
                    visible: false
                }).addTo(this.stage);
                this.heroView = new Hero({
                    assetHeroAtlas: this.asset.heroAtlas,
                    assetHeroStop: this.asset.heroStop,
                    assetFootball: this.asset.football,
                    visible: false
                }).addTo(this.stage);

                this.initScenes();

                this.scoreView = new Hilo.BitmapText({glyphs: this.asset.numberGlyphs}).addTo(this.stage);
                this.updateScoreView();

                //准备游戏
                this.gameReadyIn();
            },

            keydownHandle: function (e) {
                if (this.state === 'playing') {
                    if (e.keyCode === 37) {
                        //左
                        this.moveDirection = -1;
                    }
                    if (e.keyCode === 39) {
                        //右
                        this.moveDirection = 1;
                    }
                }
                //space
                if (e.keyCode === 32) {
                    this.gameReadyOut();
                    this.gameOverOut();
                }

                e.stopPropagation && e.stopPropagation();
                e.stopImmediatePropagation && e.stopImmediatePropagation();
                e.preventDefault && e.preventDefault();
                return false;

            },

            keyupHandle: function (e) {
                if ((e.keyCode === 37 && this.moveDirection === -1) || ( e.keyCode === 39 && this.moveDirection === 1) && this.state === 'playing') {
                    this.moveDirection = 0;
                }
            },

            initBackgroundView: function () {
                //地面
                this.groundView = new Hilo.Bitmap({image: this.asset.ground}).addTo(this.stage);

                //设置地面的y轴坐标
                this.groundView.y = this.groundY;

                //移动地面
                Hilo.Tween.to(this.groundView, {y: 400}, {duration: 600, loop: true, repeatDelay: 1000});
            },

            initScenes: function () {
                //准备场景
                this.readyView = new Hilo.Container().addTo(this.stage);
                this.readyView.x = 198;
                this.readyView.y = 166;
                var goBitmap = new Hilo.Bitmap({image: this.asset.go_txt});
                var footballGo = new Football({assetFootball: this.asset.football});
                footballGo.x = 137;
                footballGo.y = 63;
                this.readyView.addChild(goBitmap, footballGo);

                //结束场景
                this.overView = new Hilo.Container({visible: false}).addTo(this.stage);
                var overBitmap = new Hilo.Bitmap({image: this.asset.over_txt});
                var footballOver = new Football({assetFootball: this.asset.football});
                footballOver.x = 216;
                footballOver.y = 52;
                this.overView.addChild(overBitmap, footballOver);
            },

            resetEnemyView: function () {
                this.enemyView.x = this.randRange(200, 380);
                this.enemyView.y = this.groundY;
                this.enemyView.visible = true;
            },

            onUpdate: function () {
                if (this.state === 'playing') {
                    if (this.moveDirection === -1 && this.heroView.x > 20) {
                        //左
                        this.heroView.x -= 3;
                    }
                    if (this.moveDirection === 1 && this.heroView.x < 530) {
                        this.heroView.x += 3;
                        //右
                    }

                    if (this.enemyView.visible) {
                        if (this.enemyView.y < 550) {
                            this.enemyCalculate();
                        } else {
                            this.swapDone = false;
                            this.stage.swapChildren(this.enemyView, this.heroView);
                            this.enemyView.visible = false;
                            setTimeout(this.resetEnemyView.bind(this), this.randRange(300, 3000));
                        }
                    }

                }
            },

            gameReadyIn: function () {
                Hilo.Tween.from(this.readyView, {x: -140}, {
                    duration: 200,
                    ease: Hilo.Ease.Quad.EaseIn,
                    onComplete: function () {
                        this.state = 'ready';
                    }.bind(this)
                });
            },

            gameReadyOut: function () {
                if (this.state === 'ready' && !this.tweening) {
                    this.tweening = true;
                    Hilo.Tween.to(this.readyView, {x: this.width}, {
                        duration: 100,
                        ease: Hilo.Ease.Quad.EaseOut
                    });

                    this.heroView.x = (this.width - this.heroView.width) >> 1;
                    this.heroView.y = this.heroY;
                    this.heroView.visible = true;
                    Hilo.Tween.from(this.heroView, {y: 550}, {
                        duration: 200,
                        ease: Hilo.Ease.Quad.EaseIn,
                        delay: 100,
                        onComplete: function () {
                            this.gameStart();
                        }.bind(this)
                    });
                }
            },

            gameStart: function () {
                this.tweening = false;
                this.readyView.visible = false;
                this.overView.visible = false;
                this.overView.x = 160;
                this.overView.y = 160;
                this.enemyView.visible = false;
                setTimeout(this.resetEnemyView.bind(this), this.randRange(300, 3000));
                this.state = 'playing';
            },

            gameOverIn: function (offset) {
                if (this.state !== 'over') {
                    //设置当前状态为结束over
                    this.state = 'over';

                    this.score = 0;
                    this.updateScoreView();

                    this.moveDirection = 0;

                    this.enemyView.swapState();

                    if (offset >= 0) {
                        this.heroView.swapState();
                    } else if (offset < 0) {
                        this.heroView.swapState(1);
                    }

                    this.overView.visible = true;
                    Hilo.Tween.from(this.overView, {x: 600}, {
                        duration: 200,
                        ease: Hilo.Ease.Quad.EaseIn
                    });
                }
            },

            gameOverOut: function () {
                if (this.state === 'over' && !this.tweening) {
                    this.tweening = true;
                    Hilo.Tween.to(this.overView, {x: -300}, {
                        duration: 100,
                        ease: Hilo.Ease.Quad.EaseOut
                    });

                    this.enemyView.visible = false;
                    this.enemyView.swapState();

                    this.heroView.swapState();
                    this.heroView.x = (this.width - this.heroView.width) >> 1;
                    this.heroView.y = this.heroY;
                    this.heroView.visible = true;
                    Hilo.Tween.from(this.heroView, {y: 550}, {
                        duration: 200,
                        ease: Hilo.Ease.Quad.EaseIn,
                        delay: 100,
                        onComplete: function () {
                            this.gameStart();
                        }.bind(this)
                    });
                }
            },

            updateScoreView: function () {
                this.scoreView.setText(this.score);
                //设置当前分数的位置
                this.scoreView.x = this.width - this.scoreView.width - 5;
                this.scoreView.y = this.height - 25;
            },

            randRange: function (minNum, maxNum) {
                return (Math.floor(Math.random() * (maxNum - minNum + 1)) + minNum);
            },

            enemyCalculate: function () {
                var scaleFactor = (this.enemyView.y - this.groundY) / (this.heroY - this.groundY);

                this.enemyView.scaleX = this.enemyView.scaleY = scaleFactor;

                var offset = this.heroView.x - this.enemyView.x;
                var speed = Math.floor(0.6 + (2 * scaleFactor));
                speed += (this.score / 8);
                if (offset > 0) {
                    this.enemyView.x += speed;
                } else if (offset < 0) {
                    this.enemyView.x -= speed;
                }

                this.enemyView.y += (0.1 + (1 * scaleFactor));

                if ((this.enemyView.y >= (this.heroY - 2)) && !this.swapDone) {
                    if (Math.abs(offset) < 40) {
                        this.gameOverIn(offset);
                    } else {
                        this.swapDone = true;
                        this.stage.swapChildren(this.enemyView, this.heroView);
                        this.score++;
                        this.updateScoreView();
                    }
                }
            }
        };

        return game;
    }
);

