const path = require('path');
const fs = require('fs');

const postcss = require('postcss');
const px2rem = require('postcss-px2rem');

var sass = require('node-sass');

let compScssPath = path.resolve(__dirname, '../src/scss/components/base/index.scss')



let result = sass.renderSync({
			  file: compScssPath
			});

var s = result.css.toString()

let newCssText = postcss().use(px2rem({remUnit: 75})).process(s).css;
console.log(newCssText)

