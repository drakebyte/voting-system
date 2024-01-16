const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserJSPlugin = require('terser-webpack-plugin');
const CSSMinimizerPlugin = require('css-minimizer-webpack-plugin');
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const SimpleProgressWebpackPlugin = require('simple-progress-webpack-plugin');

module.exports = {
	entry: {
		'scripts-site': './src/js/site.js',
		'styles-site': './src/scss/site.scss',
	},
	output: {
		filename: 'js/[name].min.js',
		path: path.resolve(__dirname, 'assets')
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env', '@babel/preset-react']
					}
				}
			},
			{
				test: /\.scss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					{
						loader: 'sass-loader'
					}
				]
			}
		]
	},
	optimization: {
		minimizer: [
			new TerserJSPlugin({}),
			new CSSMinimizerPlugin({
				test: /\.css$/
			})
		]
	},
	plugins: [
		new FixStyleOnlyEntriesPlugin(),
		new MiniCssExtractPlugin({
			filename: 'css/[name].min.css'
		}),
		new webpack.optimize.LimitChunkCountPlugin({
			maxChunks: 1
		}),
		new SimpleProgressWebpackPlugin({
			format: 'compact'
		})
	],
	devtool: 'source-map',
	stats: {
		warnings: false,
		hash: false,
		version: true,
		all: false,
		timings: true,
		errors: true,
		errorDetails: true,
		assets: true
	}
};
