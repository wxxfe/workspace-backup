上线说明：
只需要把dist文件夹里面的所有文件上传到访问地址的最子级目录

比如访问地址是http://sports.baofeng.com/m/topic_share_flags/

则是把dist文件夹里面的所有文件上传topic_share_flags文件夹里面

访问url
http://sports.baofeng.com/m/topic_share_flags/index.html?topicflagsreplyid=1


开发说明：

`npm install`

more see packge.json and webpack.config.js

or

`npm init -y`

# install 
`npm install --save-dev webpack webpack-dev-server webpack-browser-plugin html-webpack-plugin style-loader css-loader extract-text-webpack-plugin file-loader url-loader image-webpack-loader babel-loader babel-core babel-polyfill babel-preset-es2015 babel-plugin-transform-class-properties babel-plugin-transform-object-rest-spread babel-preset-react react react-dom`

# make a .babelrc (config file) with the preset
`echo '{ "presets": ["es2015", "react"], "plugins": ["transform-class-properties","transform-object-rest-spread"] }' > .babelrc`

if use webpack, webpack.config.js babel-loader also need add
                query: {
                    presets: ['es2015', 'react'],
                    plugins: ['transform-class-properties', 'transform-object-rest-spread']
                }

# view output
`./node_modules/.bin/webpack --watch`


