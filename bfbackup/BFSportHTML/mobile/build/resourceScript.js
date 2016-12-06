var arguments = process.argv;
if(arguments.length == 2){
    console.log('未加环境参数');
    return false;
}

var env = arguments.splice(2);

var distPath = './dist/' + env;

var configPath = './dist/resource.json';

var config = {

    development : [],

    testing : [],

    production : []

};

var fs = require('fs'),
    path = require('path');
var cheerio = require('cheerio');

var hasConfig = fs.existsSync(configPath);

if(hasConfig){
    var configData = fs.readFileSync(configPath,'utf8');
    if(configData == '') return;
    config = JSON.parse(configData);
}

config[env] = [];

function getResources(jqueryObj,key){

    var $ = jqueryObj;

    var page = {
        page : key,
        css : [],
        js : []
    };

    var cssTag = $('link');
    var jsTag = $('script');
    cssTag.each(function(){
        page.css.push(decodeURIComponent($(this).attr('href')));
    });

    jsTag.each(function(){
        if($(this).attr('src') != undefined){
            page.js.push(decodeURIComponent($(this).attr('src')));
        }
    });

    config[env].push(page);

}

var files = fs.readdirSync(distPath);

files.forEach(function(file){
    if(path.extname(file) == '.html'){
        var fileName = path.basename(file,'.html');
        var data = fs.readFileSync(distPath + '/' + file,'utf8');
        var $ = cheerio.load(data);
        getResources($,fileName);
    }
});

fs.writeFileSync(configPath,JSON.stringify(config,'','\t'),'utf8');

