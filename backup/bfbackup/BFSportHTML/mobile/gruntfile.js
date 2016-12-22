module.exports = function (grunt) {

    //静态资源地址
    var staticPath = 'http://static.sports.baofeng.com/msports/';
    //环境
    var environment = 'development';

    var crypto = require('crypto');

    var path = require('path');
    var fs = require('fs');

    var px2rem = require('postcss-px2rem');

    /**
     * 生成MD5
     * @param string filepath 文件地址
     * @return string
     */
    function md5(filepath) {
        var hash = crypto.createHash('md5');
        grunt.log.verbose.write('Hashing ' + filepath + '...');
        hash.update(grunt.file.read(filepath));
        return hash.digest('hex');
    }

    /**
     * 返回静态文件线上地址
     * @block object 文件信息
     * @return string 地址
     */
    function getFilePath(block) {
        var f = 'dist/' + environment + '/' + block.dest;
        var hash = md5(f);
        var prefix = hash.slice(0, 8);
        var tmp = block.dest.split('/');
        tmp[tmp.length - 1] = prefix + '.' + tmp[tmp.length - 1];
        var final_name = tmp.join('/');
        final_name = staticPath + environment + '/' + final_name;
        return final_name;
    }

    /**
     *  获得静态图片目录路径
     *  @return string 路径地址
     */
    function getStaticImagesPath(w) {
        return 'http://static.sports.baofeng.com/msports/' + w + '/images/';
    }

    /**
     *  获得字体目录路径
     *  @return string 路径地址
     */
    function getFontsPath(w) {
        return 'http://static.sports.baofeng.com/msports/' + w + '/fonts/';
    }

    function cmd(username, environment) {
        if (environment == 'production') {
            return 'ansible-playbook ./build/playbook_static_online.yml --extra-vars "user=' + username + ' env=' + environment + '"';
        } else {
            return 'ansible-playbook ./build/playbook.yml --extra-vars "user=' + username + ' env=' + environment + '"';
        }
    }

    function productionCmd(username, environment) {
        if (environment == 'production') {
            return 'ansible-playbook ./build/playbook_production.yml --extra-vars "user=' + username + ' env=' + environment + '"';
        } else if (environment == 'testing') {
            return 'ansible-playbook ./build/playbook_testing.yml --extra-vars "user=' + username + ' env=' + environment + '"';
        } else {
            return 'echo 1';
        }
    }

    function getConfig(environment, username) {

        return {
            pkg: grunt.file.readJSON('package.json'),

            //添加md5版本号
            rev: {

                options: {
                    algorithm: 'md5',
                    length: 8
                },

                assets: {
                    src: [
                        'dist/' + environment + '/scripts/**/*.js', '!dist/' + environment + '/scripts/lib/require.js',
                        'dist/' + environment + '/css/*.css'
                    ]
                }

            },

            usebanner: {
                dist: {
                    options: {
                        position: 'top',
                        banner: '/* <%= pkg.name %> - version <% pkg.version %> - ' +
                        '<%= grunt.template.today("yyyy-mm-dd hh:mm:ss") %>' +
                        '<%= pkg.description %> */\n'
                    },
                    files: {
                        src: ['dist/' + environment + '/scripts/*.js', 'dist/' + environment + '/css/*.css']
                    }
                }
            },

            //JS文件合并压缩
            requirejs: {

                compile: {
                    options: {

                        baseUrl: './src/scripts',

                        dir: './dist/' + environment + '/scripts',

                        optimize: 'uglify2',

                        uglify2: {
                            mangle: true
                        },

                        modules: [
                            {name: 'gallery'}
                            , {name: 'news'}
                            , {name: 'program'}
                            , {name: 'videocollection'}
                            , {name: 'videoshare'}
                            , {name: 'matchnotstarted'}
                            , {name: 'matchongoing'}
                            , {name: 'matchfinished'}
                            , {name: 'post_share'}
                            , {name: 'topic_share'}
                            , {name: 'special-column'}
                            , {name: 'special-topic'}
                            , {name: 'zaixianopenapp'}
                            , {name: 'nodata'}
                            , {name: 'not-found'}
                        ]

                        //fileExclusionRegExp : /^(lib|components)/

                    }
                }

            },

            clean: {
                dist: ['./dist/' + environment + '/**'],
                scripts: ['./dist/' + environment + '/scripts/lib', './dist/' + environment + '/scripts/components']
            },

            copy: {

                html: {
                    files: [
                        {
                            expand: true,
                            cwd: './',
                            src: ['*.html'],
                            dest: 'dist/' + environment + '/'
                        }
                    ]
                },
                css: {
                    files: [
                        {
                            expand: true,
                            cwd: './src/css',
                            src: ['*.css'],
                            dest: 'dist/' + environment + '/css/'
                        }
                    ]
                },
                image: {
                    files: [
                        {
                            expand: true,
                            cwd: './src/images',
                            src: ['**'],
                            dest: 'dist/' + environment + '/images/'
                        }
                    ]
                },
                fonts: {
                    files: [
                        {
                            expand: true,
                            cwd: './src/fonts',
                            src: ['**'],
                            dest: 'dist/' + environment + '/fonts/'
                        }
                    ]
                },
                componentsJs: {
                    files: [
                        {
                            expand: true,
                            cwd: './src/scss',
                            src: ['components/**/*.js'],
                            dest: './src/scripts/'
                        }
                    ]
                },
                scripts: {
                    files: [
                        {
                            expand: true,
                            cwd: './src/scripts/lib/',
                            src: ['require.js'],
                            dest: 'dist/' + environment + '/scripts/lib/'
                        }
                    ]
                }

            },

            useminPrepare: {
                html: 'dist/' + environment + '/*.html'
            },

            usemin: {
                html: 'dist/' + environment + '/*.html',
                options: {
                    blockReplacements: {
                        css: function (block) {

                            var filePath = getFilePath(block);

                            return '<link rel="stylesheet" href="' + filePath + '" />';

                        },

                        js: function (block) {

                            var filePath = getFilePath(block);

                            if (block.dest.indexOf('require') > -1) {
                                filePath = staticPath + environment + '/' + block.dest;
                            }

                            return '<script src="' + filePath + '"></script>';

                        },

                        inline: function (block) {
                            var filepath = path.resolve(__dirname, block.src[0]);
                            var f = fs.readFileSync(filepath, 'utf-8');
                            if (filepath.endsWith('.css')) {
                                return '<style>' + f + '</style>';
                            } else if (filepath.endsWith('.js')) {
                                return '<script>' + f + '</script>';
                            }
                            return '';
                        }
                    }
                }
            },

            exec: {
                createConfig: {
                    command: 'node ./build/resourceScript.js ' + environment
                },
                static: {
                    command: cmd(username, environment)
                },
                production: {
                    command: productionCmd(username, environment)
                }

            },

            replace: {
                dist: {
                    options: {
                        patterns: [
                            {
                                match: /src\/images\//g,
                                replacement: getStaticImagesPath(environment)
                            },
                            {
                                match: /\.\.\/fonts\//g,
                                replacement: getFontsPath(environment)
                            }
                        ]
                    },
                    files: [
                        {expand: true, flatten: true, src: ['dist/'+ environment +'/css/*.css'], dest: 'dist/' + environment + '/css/'}
                    ]
                }
            },

            sass: {
                dist: {
                    options: {
                        style: 'expand'
                    },
                    files: {
                        'src/css/gallery.css': 'src/scss/pages/gallery/index.scss'
                        , 'src/css/news.css': 'src/scss/pages/news/index.scss'
                        , 'src/css/program.css': 'src/scss/pages/program/index.scss'
                        , 'src/css/videocollection.css': 'src/scss/pages/videocollection/index.scss'
                        , 'src/css/videoshare.css': 'src/scss/pages/videoshare/index.scss'
                        , 'src/css/matchnotstarted.css': 'src/scss/pages/matchnotstarted/index.scss'
                        , 'src/css/matchongoing.css': 'src/scss/pages/matchongoing/index.scss'
                        , 'src/css/matchfinished.css': 'src/scss/pages/matchfinished/index.scss'
                        , 'src/css/ranking.css': 'src/scss/pages/ranking/index.scss'
                        , 'src/css/feedback.css': 'src/scss/pages/feedback/index.scss'
                        , 'src/css/post_share.css': 'src/scss/pages/post_share/index.scss'
                        , 'src/css/topic_share.css': 'src/scss/pages/topic_share/index.scss'
                        , 'src/css/special-column.css': 'src/scss/pages/special-column/index.scss'
                        , 'src/css/special-topic.css': 'src/scss/pages/special-topic/index.scss'
                        , 'src/css/nodata.css': 'src/scss/pages/nodata/index.scss'
                        , 'src/css/not-found.css': 'src/scss/pages/not-found/index.scss'
                        , 'src/css/feedback-info.css': 'src/scss/pages/feedback-info/index.scss'
                    }
                }
            },

            postcss: {
                options: {
                    processors: [
                        px2rem({remUnit: 75, threeVersion: true,})
                    ]
                },
                dist: {
                    src: [
                        'src/css/gallery.css'
                        , 'src/css/news.css'
                        , 'src/css/program.css'
                        , 'src/css/videocollection.css'
                        , 'src/css/videoshare.css'
                        , 'src/css/matchnotstarted.css'
                        , 'src/css/matchongoing.css'
                        , 'src/css/matchfinished.css'
                        , 'src/css/ranking.css'
                        , 'src/css/feedback.css'
                        , 'src/css/post_share.css'
                        , 'src/css/topic_share.css'
                        , 'src/css/special-column.css'
                        , 'src/css/special-topic.css'
                        , 'src/css/nodata.css'
                        , 'src/css/not-found.css'
                        , 'src/css/feedback-info.css'
                    ]
                }
            },

            cssmin: {
                target: {
                    files: [
                        {
                            expand: true,
                            cwd: 'src/css',
                            src: ['*.css', '!*.min.css'],
                            dest: 'src/css',
                            ext: '.css'
                        }
                    ]
                }
            }

        };

    }

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            dist: {
                options: {
                    style: 'expand',
                },
                files: {
                    'src/css/gallery.css': 'src/scss/pages/gallery/index.scss'
                    , 'src/css/news.css': 'src/scss/pages/news/index.scss'
                    , 'src/css/program.css': 'src/scss/pages/program/index.scss'
                    , 'src/css/videocollection.css': 'src/scss/pages/videocollection/index.scss'
                    , 'src/css/videoshare.css': 'src/scss/pages/videoshare/index.scss'
                    , 'src/css/matchnotstarted.css': 'src/scss/pages/matchnotstarted/index.scss'
                    , 'src/css/matchongoing.css': 'src/scss/pages/matchongoing/index.scss'
                    , 'src/css/matchfinished.css': 'src/scss/pages/matchfinished/index.scss'
                    , 'src/css/ranking.css': 'src/scss/pages/ranking/index.scss'
                    , 'src/css/feedback.css': 'src/scss/pages/feedback/index.scss'
                    , 'src/css/post_share.css': 'src/scss/pages/post_share/index.scss'
                    , 'src/css/topic_share.css': 'src/scss/pages/topic_share/index.scss'
                    , 'src/css/special-column.css': 'src/scss/pages/special-column/index.scss'
                    , 'src/css/special-topic.css': 'src/scss/pages/special-topic/index.scss'
                    , 'src/css/nodata.css': 'src/scss/pages/nodata/index.scss'
                    , 'src/css/not-found.css': 'src/scss/pages/not-found/index.scss'
                    , 'src/css/feedback-info.css': 'src/scss/pages/feedback-info/index.scss'
                }
            }
        },

        postcss: {
            options: {
                processors: [
                    px2rem({remUnit: 75, threeVersion: true,})
                ]
            },
            dist: {
                src: [
                    'src/css/gallery.css'
                    , 'src/css/news.css'
                    , 'src/css/program.css'
                    , 'src/css/videocollection.css'
                    , 'src/css/videoshare.css'
                    , 'src/css/matchnotstarted.css'
                    , 'src/css/matchongoing.css'
                    , 'src/css/matchfinished.css'
                    , 'src/css/ranking.css'
                    , 'src/css/feedback.css'
                    , 'src/css/post_share.css'
                    , 'src/css/topic_share.css'
                    , 'src/css/special-column.css'
                    , 'src/css/special-topic.css'
                    , 'src/css/nodata.css'
                    , 'src/css/not-found.css'
                    , 'src/css/feedback-info.css'
                ]
            }
        },

        //Watch
        watch: {
            css: {
                files: ['src/scss/**/*.scss'],
                tasks: ['sass', 'postcss']
            }
        }
    });

    grunt.event.on('watch', function (action, filepath, target) {
        grunt.log.writeln(target + ': ' + filepath + ' has ' + action);
    });

    // 加载包含 "uglify" 任务的插件。
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-requirejs');
    grunt.loadNpmTasks('grunt-rev');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-usemin');
    grunt.loadNpmTasks('grunt-exec');
    grunt.loadNpmTasks('grunt-replace');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    // 默认被执行的任务列表。
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('bl', ['sass', 'postcss']);
    grunt.registerTask('wl', ['watch']);
    grunt.registerTask('bl', ['sass']);
    grunt.registerTask('release', function (evn, username) {

        var ENV = {d: 'development', t: 'testing', p: 'production'};

        if (arguments.length < 2) {
            grunt.log.writeln('请选择要发布的环境：\ndevelopment[d] - 开发环境\ntest[t] - 测试环境\nproduction[p] - 生产环境');
            grunt.log.writeln('请输入登录服务器的用户名!');
            return false;
        } else {
            environment = ENV[evn];
            grunt.initConfig(getConfig(environment, username));
        }

        grunt.task.run([
            'clean:dist', 'copy:componentsJs', 'requirejs', 'clean:scripts', 'sass', 'postcss', 'cssmin',
            'copy', 'replace', 'usemin', 'rev', 'usebanner', 'exec'
        ]);

    });

};
