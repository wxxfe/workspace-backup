//导入Node.js的path模块
//var path = require('path');

//导入webpack整个模块
//var webpack = require('webpack');

//导入webpack内置的共用代码块插件,主要用来把多页面js中共用代码抽取出来独立生成单个js
var CommonsChunkPlugin = require("webpack/lib/optimize/CommonsChunkPlugin");

//导入ExtractTextPlugin插件,主要用来提取生成独立的CSS文件
var ExtractTextPlugin = require('extract-text-webpack-plugin');

//导出配置
module.exports = {

    // 入口文件配置,打包输出的来源
    // 多种写法 entry:'entry.js' or entry:{entry1:'entry1.js'} or entry:{entry1:['entry1a.js', ...]} or etc.
    // babel-polyfill用来转换ES2015新的对象和方法,每个入口配置数组第一个值必须是babel-polyfill,并且必须在入口文件代码的第一行import或require 'babel-polyfill'
    entry: {
        index: ['babel-polyfill', './src/index.js'],
        edit: ['babel-polyfill', './src/edit.js']
    },

    //输出配置
    //path 输出目录 如果出错,可以用path.resolve(__dirname, 'build')转换成绝对路径
    //publicPath 开发代码中使用到的url的拼接处理,通常是图片地址, url目录前缀或完整网址url前缀'http://cdn.com/'
    //filename 输出js文件名,[name]对应entry对象键名,也可以指定名字
    output: {
        path: 'build',
        publicPath: './build/',
        filename: '[name].js'
    },

    //路径解析配置
    resolve: {
        //自行补全路径中文件的后缀, 第一个是空字符串，对应不需要后缀的情况
        extensions: ['', '.webpack.js', '.web.js', '.js', '.jsx']
    },

    //模块
    module: {
        //loaders: 加载器
        // [
        //     {
        //         test:正则表达式,匹配的文件名则使用这个加载器。
        //         include: 匹配的目录则进一步处理
        //         exclude: 匹配的目录则排除
        //         loader: `!`用于分隔loader
        //     }
        // ]
        loaders: [
            {
                //加载css资源,默认写法loader:'style-loader!css-loader' css为Internal内部形式
                // ExtractTextPlugin插件写法用于生成独立的css文件,用于external link形式
                test: /\.css$/,
                loader: ExtractTextPlugin.extract('style-loader', 'css-loader')
            },
            {
                //加载图片资源,如果图片小于limit值直接生成`base64` 格式的`dataUrl`,否则输出图片,name参数指定输出目录和图片名
                test: /\.(jpe?g|png|gif|svg)$/i,
                loader: 'url-loader?limit=8192&name=img/[name].[ext]'
            },
            {
                //用babel转译器加载有es2015和jsx语法的js,输出为es5语法的js,注意只是语法转译,如果有用新ES的对象或属性方法还需要babel-polyfill
                test: /\.jsx?$/,
                exclude: /(node_modules|lib)/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015', 'react'],
                    plugins: ['transform-class-properties', 'transform-object-rest-spread']
                }
            }

        ]
    },

    //script引入js类库，通过require或import的方式来使用，却不希望webpack把它编译到输出文件中。
    //比如不想这么用 const $ = window.jQuery 而是这么用 const $ = require("jquery") or import $ from "jquery"; 则配置"jquery": "jQuery"
    //键名是require或from时的字符串,键值是js内的全局变量名
    externals: {
        'react': 'React',
        'react-dom': 'ReactDOM',
        'baidu-hmt': 'window._hmt',
        'lrz': 'lrz',
        'iscroll': 'IScroll',
        'zepto': 'Zepto',
        'fabric': 'fabric',
        'react-slick': 'Slider'
    },

    plugins: [
        //输出独立的css文件,用于external link形式,如果有多个入口JS共用的CSS,会生成commons.css
        new ExtractTextPlugin('[name].css'),

        //把entry中配置的多个js中共用代码提取生成为单个js, 多参数写法 new CommonsChunkPlugin("commons", "commons.js")
        new CommonsChunkPlugin({
            name: "commons",
            filename: "commons.js"
        })

        //ProvidePlugin的作用就是在开发代码内不需要require('react')或import ... from ... 也能使用React
        // ,new webpack.ProvidePlugin({
        //     React: 'react',
        //     ReactDOM: 'react-dom'
        // })

        //压缩代码,命令行的 webpack -p 会默认使用这个插件压缩代码
        // ,new webpack.optimize.UglifyJsPlugin({
        //     compress: {
        //         warnings: false
        //     }
        // })

    ],

    //生成source-map,调试使用
    //devtool: 'cheap-module-eval-source-map',

    //webpack-dev-server的配置, 也有对应的命名行参数形式
    devServer: {
        contentBase: './',
        host: 'h5.baofeng.com',
        port: 9090, //默认8080
        inline: true, //可以监控js变化,自动刷新页面
        historyApiFallback: true,
        progress: true,
        colors: true
    }

}