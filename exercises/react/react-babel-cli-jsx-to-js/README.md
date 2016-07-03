`npm init -y`

# install the cli and this preset
`npm install --save-dev babel-cli babel-preset-react react react-dom`

# make a .babelrc (config file) with the preset
`echo '{ "presets": ["react"] }' > .babelrc`

# view output
`./node_modules/.bin/babel --presets react --watch src/ --out-dir dist/`
