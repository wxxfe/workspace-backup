const path = require('path');
const webpack = require('webpack');
const webpackDevServer = require('webpack-dev-server');
const config = require('./webpack.config.js');
const WebpackBrowserPlugin = require('webpack-browser-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');

const host = 'localhost';
const port = 8080;
const url = `http://${host}:${port}/`;

for (let prop in config.entry) {
    config.entry[prop].unshift(
        `webpack-dev-server/client?${url}`,
        'webpack/hot/dev-server'
    );
}

config.output.publicPath = url;


for (let prop in config.plugins) {
    let obj = config.plugins[prop];
    if (obj instanceof HtmlWebpackPlugin) {
        obj.options.filename = String(obj.options.filename).replace('../', '');
        // console.log(config.plugins[prop]);
    }
}

config.plugins.push(
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
    new WebpackBrowserPlugin(
        {
            port: port,
            url: `http://${host}`
        }
    )
);

config.devtool = 'cheap-module-eval-source-map';

const server = new webpackDevServer(webpack(config), {
    contentBase: "./src",
    hot: true,
    inline: true,
    historyApiFallback: true,
    progress: true,
    stats: {colors: true} // 用颜色标识
});

server.listen(port, host, function (err) {
    if (err) {
        console.log(err);
    }
});
