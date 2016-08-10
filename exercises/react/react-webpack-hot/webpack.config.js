const path = require('path');
const webpack = require('webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const config = {
    entry: {
        index: [path.resolve(__dirname, 'src/index.js')]
    },
    output: {
        path: path.resolve(__dirname, 'dist/build'),
        filename: '[name].[id].[hash].js',
        publicPath: './build/'
    },
    resolve: {
        extensions: ['', '.js', '.jsx']
    },
    module: {
        loaders: [
            {
                test: /\.js|jsx$/,
                exclude: /(node_modules|bower_components)/,
                loaders: ['babel']
            },
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract(
                    'style-loader',
                    'css-loader',
                    {
                        publicPath: "./"
                    }
                )
            },
            {
                test: /\.(jpe?g|png|gif|svg)$/i,
                loaders: [
                    'url?limit=1&name=img/[hash:8].[name].[ext]',
                    'image-webpack?{progressive:true, optimizationLevel: 7, interlaced: false, pngquant:{quality: "65-90", speed: 4}}'
                ]
            }
        ]
    },

    externals: {
        'react': 'React',
        'react-dom': 'ReactDOM'
    },

    plugins: [
        new ExtractTextPlugin('[name].[id].[hash].css'),
        new HtmlWebpackPlugin({
            template: 'src/index.tpl.html',
            inject: 'body',
            chunks: ['index'],
            filename: '../index.html',
            title: 'Webpack App'
        })
    ]
};

module.exports = config;
