`npm install`

more see packge.json and webpack.config.js

`npm init -y`

# install 
`npm install --save-dev webpack style-loader css-loader file-loader url-loader babel-loader babel-core babel-preset-es2015 babel-preset-react react react-dom`

# make a .babelrc (config file) with the preset
`echo '{ "presets": ["es2015", "react"] }' > .babelrc`

# view output
`./node_modules/.bin/webpack --watch`
