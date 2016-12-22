
'use strict';

// load native modules
let http = require('http')
let path = require('path')
let util = require('util')

// load 3rd modules
let koa = require('koa')
let router = require('koa-router')()
let serve = require('koa-static')
let colors = require('colors')
let open = require('open')

// load local modules
let pkg = require('../package.json')
//let env = process.argv[2] || process.env.NODE_ENV
let compName = process.argv[2] || process.env.NODE_ENV
//let dev = 'production' !== env
//let viewDir = dev ? 'src' : 'assets'
let compsDir = path.resolve(__dirname, '../src/scss/components')

let htmlFileName = process.argv[3]

// load routes
let routes = require('./routes')

// init framework
let app = koa()

colors.setTheme({
    silly: 'rainbow',
    input: 'grey',
    verbose: 'cyan',
    prompt: 'grey',
    info: 'green',
    data: 'grey',
    help: 'cyan',
    warn: 'yellow',
    debug: 'blue',
    error: 'red'
})

// basic settings
app.keys = [pkg.name, pkg.description]
app.proxy = true

// global events listen
app.on('error', (err, ctx) => {
    err.url = err.url || ctx.request.url
    console.error(err.stack, ctx)
})

// handle favicon.ico
app.use(function*(next) {
    if (this.url.match(/favicon\.ico$/)) this.body = ''
    yield next
})

// logger
app.use(function*(next) {
    console.log(this.method.info, this.url)
    yield next
})

// use routes
routes(router, app, compsDir, compName, htmlFileName)
app.use(router.routes())

// handle static files
app.use(serve(compsDir, {
    maxage: 0
}))

app = http.createServer(app.callback())

app.listen(pkg.test.localServer.port, () => {
    let url = util.format('http://%s:%d', '127.0.0.1', pkg.test.localServer.port)

    console.log('Listening at %s', url)

    open(url + '/comptest')
})
