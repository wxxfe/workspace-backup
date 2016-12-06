module.exports = function(grunt) {

    //静态资源地址
    var staticPath = 'http://static.sports.baofeng.com/';
    //环境
    var environment = 'development';

    var crypto = require('crypto');

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
    function getFilePath(block){
        var f = 'dist/' + environment + '/' + block.dest;
        var hash = md5(f);
        var prefix = hash.slice(0,8);
        var tmp = block.dest.split('/');
        tmp[tmp.length-1] = prefix + '.' + tmp[tmp.length-1];    
        var final_name = tmp.join('/');
        final_name = staticPath + environment + '/' + final_name;
        return final_name;
    }

    /**
     *  获得静态图片目录路径
     *  @return string 路径地址
     */
    function getStaticImagesPath(w){
        return 'http://static.sports.baofeng.com/'+ w +'/images/';
    }

    function cmd(username,environment){
        if(environment == 'production'){
            return 'ansible-playbook ./build/playbook_static_online.yml --extra-vars "user='+ username +' env='+ environment +'"';
        }else{
            return 'ansible-playbook ./build/playbook.yml --extra-vars "user='+ username +' env='+ environment +'"';
        }
    }

    function productionCmd(username,environment){
        if(environment == 'production'){
            return 'ansible-playbook ./build/playbook_production.yml --extra-vars "user='+ username +' env='+ environment +'"';
        }else{
            return 'echo 1';
        }
    }


    function getConfig(environment,username){

        return {
            pkg: grunt.file.readJSON('package.json'),
            
            concat : {
                dist : {
                    files : [
                        {
                            src : [
                                'src/scripts/lib/require.js',
                                'src/scripts/lib/jquery-1.12.1.min.js',
                                'src/scripts/lib/jquery.easing.min.js',
                                'src/scripts/lib/jquery.lazyload.min.js'
                            ],
                            dest : 'dist/'+ environment +'/scripts/lib/jquery.min.js'
                        }
                    ]
                }
            },

            //添加md5版本号
            rev : {

                options : {
                    algorithm : 'md5',
                    length : 8
                },

                assets : {
                    src : ['dist/'+ environment +'/scripts/**/*.js','!dist/'+ environment +'/scripts/lib/jquery.min.js','dist/'+ environment +'/css/*.css']
                }

            },

            usebanner : {
                dist : {
                    options : {
                        position : 'top',
                        banner : '/* <%= pkg.name %> - version <% pkg.version %> - ' +
                                    '<%= grunt.template.today("yyyy-mm-dd hh:mm:ss") %>' + 
                                    '<%= pkg.description %> */\n'
                    },
                    files : {
                        src : ['dist/'+ environment +'/scripts/*.js','dist/'+ environment +'/css/*.css']
                    }
                }
            },

            //JS文件合并压缩
            requirejs : {

                compile : {
                    options : {

                        baseUrl : './src/scripts',

                        dir : './dist/'+ environment +'/scripts',
                        
                        optimize : 'uglify2',

                        uglify2 : {
                            mangle : true
                        },

                        modules : [
                            {name : 'index'},
                            {name : 'event'},
                            {name : 'matchVideo'},
                            {name : 'justLogin'},
                            {name : 'matchCourse'},
                            {name : 'matchLive'},
                            {name : 'matchEnd'},
                            {name : 'matchNostarted'},
                            {name : 'notFoundError'},
                            {name : 'videoList'},
                            {name : 'newsList'}
                        ]

                        //fileExclusionRegExp : /^(lib|components)/

                    }
                }

            },

            clean : {
                dist : ['./dist/'+ environment +'/**'],
                scripts : ['./dist/'+ environment +'/scripts/lib','./dist/'+ environment +'/scripts/components']
            },

            copy : {

                html : {
                    files : [{
                        expand : true,
                        cwd : './',
                        src : ['*.html'],
                        dest : 'dist/' + environment + '/'
                    }]
                },
                css : {
                    files : [{
                        expand : true,
                        cwd : './src/css',
                        src : ['*.css'],
                        dest : 'dist/' + environment + '/css/'
                    }]
                },
                image : {
                    files : [{
                        expand : true,
                        cwd : './src/images',
                        src : ['**'],
                        dest : 'dist/' + environment + '/images/'
                    }]
                },
                player : {
                    files : [{
                        expand : true,
                        cwd : './src/player',
                        src : ['**'],
                        dest : 'dist/' + environment + '/player/'
                    }]
                },
                fonts : {
                    files : [{
                        expand : true,
                        cwd : './src/fonts',
                        src : ['**'],
                        dest : 'dist/' + environment + '/fonts/'
                    }]
                },
                scripts : {
                    files : [{
                        expand : true,
                        cwd : './src/scripts/lib/',
                        src : ['hilo.swf','hilo-*.js'],
                        dest : 'dist/' + environment + '/scripts/lib/'
                    }]
                }

            },

            useminPrepare : {
                html : 'dist/'+ environment +'/*.html'
            },

            usemin : {
                html : 'dist/'+ environment +'/*.html',
                options : {
                    blockReplacements : {
                        css : function(block){

                            var filePath = getFilePath(block);

                            return '<link rel="stylesheet" href="'+ filePath +'" />';

                        },

                        js : function(block){
                            
                            var filePath = getFilePath(block);

                            if(block.dest.indexOf('jquery.min') > -1){
                                filePath = staticPath + environment + '/' + block.dest;
                            }

                            return '<script src="'+ filePath +'"></script>';

                        }
                    }
                }
            },

            exec : {
                createConfig : {
                    command : 'node ./build/resourceScript.js ' + environment
                },
                static : {
                    command : cmd(username,environment)
                },
                production : {
                    command : productionCmd(username,environment)
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
                                match: /src\/player\//g,
                                replacement: function(){
                                    return 'http://static.sports.baofeng.com/'+ environment +'/player/';
                                }
                            }
                        ]
                    },
                    files: [
                        {expand: true, flatten: true, src: ['dist/' + environment + '/scripts/notFoundError.js'],dest : 'dist/' + environment + '/scripts/'},
                        {expand: true, flatten: true, src: [
                        'dist/' + environment + '/scripts/event.js',
                        'dist/' + environment + '/scripts/index.js',
                        'dist/' + environment + '/scripts/matchEnd.js'
                        ],dest : 'dist/' + environment + '/scripts/'}
                    ]
                }
            },

            less : {
                production : {
                    files : [
                        {'src/css/components.css' : 'src/less/components.less'},
                        {'src/css/index.css' : 'src/less/index.less'},
                        {'src/css/event.css' : 'src/less/event.less'},
                        {'src/css/match-live.css' : 'src/less/match-live.less'},
                        {'src/css/match-live-mobile.css' : 'src/less/match-live-mobile.less'},
                        {'src/css/ranking.css' : 'src/less/ranking.less'},
                        {'src/css/match-end.css' : 'src/less/match-end.less'},
                        {'src/css/match-nostarted.css' : 'src/less/match-nostarted.less'},
                        {'src/css/news-list.css' : 'src/less/news-list.less'},
                        {'src/css/news-detail.css' : 'src/less/news-detail.less'},
                        {'src/css/download-app.css' : 'src/less/download-app.less'},
                        {'src/css/not-found-error.css' : 'src/less/not-found-error.less'},
                        {'src/css/video-list.css' : 'src/less/video-list.less'},
                        {'src/css/share.css' : 'src/less/share.less'}
                    ],
                    options : {
                        compress : true,
                        modifyVars : {
                            imgPath : getStaticImagesPath(environment)
                        }
                    }
                }
            }

        };
            
    }

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        //Less
        less : {
            development : {
                files : [
                    {'src/css/components.css' : 'src/less/components.less'},
                    {'src/css/index.css' : 'src/less/index.less'},
                    {'src/css/event.css' : 'src/less/event.less'},
                    {'src/css/match-live.css' : 'src/less/match-live.less'},
                    {'src/css/match-live-mobile.css' : 'src/less/match-live-mobile.less'},
                    {'src/css/match-nostarted.css' : 'src/less/match-nostarted.less'},
                    {'src/css/ranking.css' : 'src/less/ranking.less'},
                    {'src/css/match-end.css' : 'src/less/match-end.less'},
                    {'src/css/news-list.css' : 'src/less/news-list.less'},
                    {'src/css/news-detail.css' : 'src/less/news-detail.less'},
                    {'src/css/match-video.css' : 'src/less/match-video.less'},
                    {'src/css/download-app.css' : 'src/less/download-app.less'},
                    {'src/css/not-found-error.css' : 'src/less/not-found-error.less'},
                    {'src/css/video-list.css' : 'src/less/video-list.less'},
                    {'src/css/share.css' : 'src/less/share.less'}
                ],
                options : {
                    compress : true,
                    modifyVars : {
                        imgPath : '"../images/"'
                    }
                }
            }
        },


        //Watch
        watch : {
            lc : {
                files : ['src/less/**/*.less'],
                tasks : ['less']
            }
        }
    });

    grunt.event.on('watch',function(action,filepath,target){
        grunt.log.writeln(target + ': ' + filepath + ' has ' + action);
    });

    // 加载包含 "uglify" 任务的插件。
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-requirejs');
    grunt.loadNpmTasks('grunt-rev');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-usemin');
    grunt.loadNpmTasks('grunt-exec');
    grunt.loadNpmTasks('grunt-replace');

    // 默认被执行的任务列表。
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('wl', ['watch']);
    grunt.registerTask('bl', ['less']);
    grunt.registerTask('release',function(evn,username){

        var ENV = {d : 'development',t : 'testing',p : 'production'};

        if(arguments.length < 2){
            grunt.log.writeln('请选择要发布的环境：\ndevelopment[d] - 开发环境\ntest[t] - 测试环境\nproduction[p] - 生产环境');
            grunt.log.writeln('请输入登录服务器的用户名!');
            return false;
        }else{
            environment = ENV[evn];
            grunt.initConfig(getConfig(environment,username));
        }

        grunt.task.run(['clean:dist','requirejs','replace','clean:scripts','less','concat','copy','usemin','rev','usebanner','exec']);

    });

};
