/*
 * 通过node server.dev.js 命令 启动此服务
 * server.dev.js基于webpack.config.js配置做了特殊处理
 * 主要是为了自动刷新和热更新, 这个服务只是在内存中生成缓存文件,不会在硬盘中生成文件。
 */

//导入相关模块插件配置等
const webpack = require('webpack');
const webpackDevServer = require('webpack-dev-server');
const config = require('./webpack.config.js');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const WebpackBrowserPlugin = require('webpack-browser-plugin');

//server主机地址,端口等配置
const host = 'localhost';
const port = 8181;
const url = `http://${host}:${port}/`;

//为了热更新,必须在入口文件前面加上这两个东西
for (let prop in config.entry) {
    config.entry[prop].unshift(
        `webpack-dev-server/client?${url}`,
        'webpack/hot/dev-server'
    );
}

//server的资源路径,因为这个服务生成的文件是在内存中,这个和基础配置不同
config.output.publicPath = url;

//生成的HTML路径和默认配置不同,直接生成在当前目录,默认配置因为项目结构和output.path的原因,需要生成到上级目录
for (let prop in config.plugins) {
    let obj = config.plugins[prop];
    if (obj instanceof HtmlWebpackPlugin) {
        obj.options.filename = String(obj.options.filename).replace('../', '');
        obj.options.libJS = [
            '/lib/js/react-with-addons.js',
            '/lib/js/react-dom.js',
            '/lib/js/lrz.all.bundle.js'
        ];
        // console.log(config.plugins[prop]);
    }
}

//加入热更新插件,无错误插件,全局变量定义插件,服务启动后自动在浏览器中打开连接的插件
config.plugins.push(
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
    new webpack.DefinePlugin({
        'process.env': {
            'NODE_ENV': JSON.stringify('development')
        }
    }),
    new WebpackBrowserPlugin(
        {
            port: port,
            url: `http://${host}`
        }
    )
);

//debug
config.debug = true;
//生成source-map的方式,用于调试。webpack -d 命令会使用此设置
//config.devtool = 'cheap-module-eval-source-map';
config.devtool = 'source-map';

//服务器,第一个参数传入webpack配置
//因为这个服务不会在硬盘创建目录和文件contentBase指定为源码目录,这样才能读取到一些非生成的源码资源
//hot: true开启热更新 inline: true开启自动刷新
const server = new webpackDevServer(webpack(config), {
    contentBase: "src/",
    hot: true,
    inline: true,
    historyApiFallback: true,
    progress: true,
    stats: {colors: true} // 用颜色标识
});

//启动服务器
server.listen(port, host, function (err) {
    if (err) {
        console.log(err);
    }
});
