上线说明：
必须的文件和文件夹
index.html
edit.html
lib
build

访问url
http://sports.baofeng.com/m/topic_share_flags/index.html?topicflagsreplyid=1


开发说明：

`npm install`

more see packge.json and webpack.config.js

or

`npm init -y`

# install 
`npm install --save-dev webpack style-loader css-loader file-loader url-loader babel-loader babel-core babel-polyfill babel-preset-es2015 babel-plugin-transform-class-properties babel-plugin-transform-object-rest-spread babel-preset-react react react-dom webpack-dev-server extract-text-webpack-plugin`

# make a .babelrc (config file) with the preset
`echo '{ "presets": ["es2015", "react"], "plugins": ["transform-class-properties","transform-object-rest-spread"] }' > .babelrc`

if use webpack, webpack.config.js babel-loader also need add
                query: {
                    presets: ['es2015', 'react'],
                    plugins: ['transform-class-properties', 'transform-object-rest-spread']
                }

# view output
`./node_modules/.bin/webpack --watch`


