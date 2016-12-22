define(
    function () {
        
        var Asset = Hilo.Class.create({
            Mixes: Hilo.EventMixin,

            queue: null,
            ground: null,
            numberGlyphs: null,
            number: null,
            go_txt: null,
            over_txt: null,
            football: null,
            heroAtlas: null,
            enemyAtlas: null,
            heroStop: null,
            enemyStop: null,

            load: function () {
                var resources = [
                    {id: 'ground', src: 'src/images/not_found_error/ground.png'},
                    {id: 'go', src: 'src/images/not_found_error/go.png'},
                    {id: 'over', src: 'src/images/not_found_error/over.png'},
                    {id: 'football', src: 'src/images/not_found_error/football.png'},
                    {id: 'hero', src: 'src/images/not_found_error/hero.png'},
                    {id: 'enemy', src: 'src/images/not_found_error/enemy.png'},
                    {id: 'heroStop', src: 'src/images/not_found_error/hero1.png'},
                    {id: 'enemyStop', src: 'src/images/not_found_error/enemy1.png'},
                    {id: 'number', src: 'src/images/not_found_error/number.png'}
                ];

                this.queue = new Hilo.LoadQueue();
                this.queue.add(resources);
                this.queue.on('complete', this.onComplete.bind(this));
                this.queue.start();
            },

            onComplete: function (e) {
                this.ground = this.queue.get('ground').content;
                this.go_txt = this.queue.get('go').content;
                this.over_txt = this.queue.get('over').content;
                this.football = this.queue.get('football').content;
                this.heroStop = this.queue.get('heroStop').content;
                this.enemyStop = this.queue.get('enemyStop').content;

                this.heroAtlas = new Hilo.TextureAtlas({
                    image: this.queue.get('hero').content,
                    frames: [
                        [0, 0, 40, 99], [40, 0, 38, 95], [78, 0, 37, 95], [78, 0, 37, 95], [0, 99, 39, 95], [39, 99, 38, 95]
                    ],
                    sprites: {
                        running: [0, 1, 2, 3, 4, 5]
                    }
                });

                this.enemyAtlas = new Hilo.TextureAtlas({
                    image: this.queue.get('enemy').content,
                    frames: [
                        [0, 0, 55, 97], [55, 0, 48, 95], [55, 0, 48, 95], [103, 0, 55, 98], [103, 0, 55, 98], [0, 98, 48, 95], [0, 98, 48, 95], [48, 98, 56, 98], [48, 98, 56, 98]
                    ],
                    sprites: {
                        running: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                });

                var number = this.number = this.queue.get('number').content;
                this.numberGlyphs = {
                    0: {image: number, rect: [0, 0, 14, 20]},
                    1: {image: number, rect: [16, 0, 10, 20]},
                    2: {image: number, rect: [26, 0, 15, 20]},
                    3: {image: number, rect: [42, 0, 14, 20]},
                    4: {image: number, rect: [57, 0, 14, 20]},
                    5: {image: number, rect: [72, 0, 15, 20]},
                    6: {image: number, rect: [88, 0, 14, 20]},
                    7: {image: number, rect: [103, 0, 15, 20]},
                    8: {image: number, rect: [119, 0, 14, 20]},
                    9: {image: number, rect: [134, 0, 14, 20]}
                };

                this.queue.off('complete');
                this.fire('complete');
            }
        });

        return Asset;
    }
);
