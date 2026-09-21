const miniCssExtractPlugin = require('mini-css-extract-plugin');
const progressBarPlugin = require('progress-bar-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

// const fontminPlugin = require('fontmin-webpack');

module.exports = (env, argv) => {

	// Setup ENV
	process.env.NODE_ENV = argv.mode;

	// Set PostCSS Config
	const postCssConfig = './config/postcss.config.js'

	// Create Plugins Array
	const plugins = [
		new miniCssExtractPlugin({
			filename: 'css/style.css',
		}),
		new progressBarPlugin({
			summary: env && env.stats 
				? env.stats 
				: true
		})
	];

	if(argv.mode !== 'production') {
		// Add Browser Sync
		plugins.push(new BrowserSyncPlugin({
			proxy: 'http://hiyield.localhost',
			open: false,
			port: 3666,
			notify: false,
			files: ['../../**/*.php', './**/*.css', './**/*.js' ]
		}));
	}

	// Return Webpack Configuration
	return {
		watchOptions: {
			ignored: /node_modules/
		},
		plugins,
		module: {
			rules: [
				// Webpack Folder Loader
				{
					test: /index\.js$/,
					exclude: /node_modules/,
					use: ['babel-loader', 'astroturf/loader'],
				},
				
				// Tailwind Loader
				{
					test: /tailwind\.css/,
					use: [
						miniCssExtractPlugin.loader,
						{
							loader: 'css-loader',
							options: {
								importLoaders: 1,
								url: false
							}
						},
						{
							loader: 'postcss-loader',
							options: {
								postcssOptions: {
									config: postCssConfig								
								},
							},
						},
					]
				},

				// CSS Loader ***
				{
					test: /\.css$/i,
					exclude: [
						/tailwind\.css/,
						/vendor/
					],
					use: [
						{
							loader: 'file-loader',
							options: {
								outputPath: 'css',
								name: '[name].[ext]'
							}
						}
					]
				},

				// JS Loader ***
				{
					test: /\.js$/,
					exclude: [
						/index\.js$/,
						/node_modules/,
						/vendor/
					],
					use: [
						{ 
							loader: 'file-loader',
							options: {
								outputPath: 'js',
								name: '[name].[ext]'
							}
						}
					],
				},

				// Image Loader
				{
					test: /\.(png|jpe?g|gif|svg)$/i,
					exclude: /fonts/,
					use: [
						{ 
							loader: 'file-loader',
							options: {
								outputPath: 'imgs',
								name: '[folder]/[name].[ext]'
							}
						},
						'img-loader'
					]
				},

				// Font Loader
				{
					test: /\.(eot|ttf|woff|woff2|svg)$/i,
					exclude: /imgs/,
					use: [
						{ 
							loader: 'file-loader',
							options: {
								outputPath: 'fonts',
								name: '[folder]/[name].[ext]'
							}
						}
					]
				},

				// Vendor Loader
				{
					test: /vendor\/.*$/i,
					use: [
						{ 
							loader: 'file-loader',
							options: {
								outputPath: 'vendor',
								name: '[folder]/[name].[ext]'
							}
						}
					]
				}
			]
		},
		output: {
			filename: './js/script.js',
		}
	}
}