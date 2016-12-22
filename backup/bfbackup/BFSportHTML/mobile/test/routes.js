'use strict';

const glob = require('glob');

const render = require('koa-ejs');
const proxy = require('koa-proxy');

const path = require('path');
const fs = require('fs');

const postcss = require('postcss');
const px2rem = require('postcss-px2rem');

const sass = require('node-sass');

module.exports = (router, app, compsDir, compName, htmlFileName) => {
	let compDir = compsDir + '/' + compName;
    render(app, {
        root: compsDir,
        layout: false,
        viewExt: 'html',
        cache: false,
        debug: true
    });
    
    router.get('/comptest', function*() {
    		let compHtmlPath = compDir + '/demo/' + (htmlFileName ? htmlFileName : 'index') + '.html';
    		let compScssPath = compDir + '/index.scss';
    		let flexibleCssPath = path.resolve(compsDir, '../../../vendor/flexible/flexible.css');
    		let flexibleJsPath = path.resolve(compsDir, '../../../vendor/flexible/flexible.js');
    		
    		let compJsPathRelative = compName  + '/index.js';
    		console.log(compHtmlPath)
    		let htmlSrc = fs.readFileSync(compHtmlPath, 'utf-8')
    		let flexibleCssSrc = fs.readFileSync(flexibleCssPath, 'utf-8')
    		let flexibleJsSrc = fs.readFileSync(flexibleJsPath, 'utf-8')
    		
    		// 引入依赖的js
    		let dependJsArr = [], dependJs = '';
    		try {
    			let descJson = require(compDir + '/demo/index.json');
	    		if(descJson && descJson.dependencies && descJson.dependencies.length > 0) {
	    			for(var i = 0, len = descJson.dependencies.length;i < len;i++) {
	    				dependJsArr.push('<script src="' + descJson.dependencies[i] + '"></script>');
	    			}
	    		}
    		} catch(e) {
    		}
    		dependJs = dependJsArr.join('');
    		
    		let newCssText = '';
    		let exists = fs.existsSync(compScssPath);
    		if(exists) {
    			// 编译sass
			let result = sass.renderSync({
				  file: compScssPath
				});
			newCssText = postcss().use(px2rem({remUnit: 75})).process(result.css.toString()).css;
    		}
    		
    		
    		this.body = ['<!DOCTYPE html>',
						'<html>',
						'    <head>',
						'        <meta charset="utf-8">',
						'        <meta content="yes" name="apple-mobile-web-app-capable">',
						'        <meta content="yes" name="apple-touch-fullscreen">',
						'        <meta content="telephone=no,email=no" name="format-detection">',
						'		 <style>' + flexibleCssSrc + '</style>',
						'        <script>' + flexibleJsSrc + '</script>',
						'        <style>' + newCssText  + '</style>',
						dependJs,
						'        <script src="' + compJsPathRelative  + '"></script>',
						'        <title>组建测试</title>',
						'    </head>',
						'    <body>',
						'	 	 ' + htmlSrc,
						'    </body>',
						'</html>'].join("");
    });
};
