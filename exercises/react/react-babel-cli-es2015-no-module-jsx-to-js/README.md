`npm init -y`

# install the cli and this preset
`npm install --save-dev babel-cli babel-preset-es2015 babel-preset-react react react-dom`

# make a .babelrc (config file) with the preset
`echo '{ "presets": ["es2015", "react"] }' > .babelrc`

# view output
`./node_modules/.bin/babel --presets es2015,react --watch src/ --out-dir dist/`
