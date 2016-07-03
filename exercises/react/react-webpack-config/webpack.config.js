module.exports = {
    
    entry: './src/entry.js', //入口

    output: { //输出
        path: 'dist',
        filename: 'bundle.js'
    },
	
	resolve: {
        extensions: ["", ".webpack.js", ".web.js", ".jsx", ".js"]
    },
	
    module: { 
        loaders: [ //加载器
            {
				test: /\.css$/, 
				loader: 'style-loader!css-loader' 
			},
			{
				test: /\.(jpe?g|png|gif|svg)$/i, 
				loader: 'url-loader?limit=8192'
			},
            {
				test: /\.jsx?$/,
				exclude: /(node_modules)/, 
				loader: 'babel-loader', 
				query: { 
					presets: ['es2015', 'react'] 
				} 
			}  
        ]
    },
	
	 externals: {
        "react": "React",
        "react-dom": "ReactDOM"
    }

}