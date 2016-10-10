分割代码，按需加载，参考React Router Examples的huge-apps

React on ES6+
https://babeljs.io/blog/2015/06/07/react-on-es6-plus

开发说明：

`npm install`

more see packge.json and webpack.config.js

or

`npm init -y`

# install 
`npm install --save-dev webpack webpack-dev-server webpack-browser-plugin html-webpack-plugin style-loader css-loader extract-text-webpack-plugin file-loader url-loader image-webpack-loader babel-loader babel-core babel-polyfill babel-preset-es2015 babel-plugin-transform-class-properties babel-plugin-transform-object-rest-spread babel-preset-react react react-dom react-router`

# make a .babelrc (config file) with the preset
`echo '{ "presets": ["es2015", "react"], "plugins": ["transform-class-properties","transform-object-rest-spread"] }' > .babelrc`

if use webpack, webpack.config.js babel-loader also need add
                query: {
                    presets: ['es2015', 'react'],
                    plugins: ['transform-class-properties', 'transform-object-rest-spread']
                }

# view output
`./node_modules/.bin/webpack --watch`



