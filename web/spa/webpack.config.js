var webpack = require("webpack");
var path = require("path");
var HtmlWebpackPlugin = require('html-webpack-plugin');
var combineLoaders = require('webpack-combine-loaders');
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var SRC_DIR = path.resolve(__dirname,"src");;
var DIST_DIR = null;
var fileNameConfig = '';
var publicPathConfig = '';
if(process.env.NODE_ENV === 'production')
 {
   DIST_DIR = path.resolve(__dirname,"dist");
   fileNameConfig = "[name].[chunkhash].bundle.js";
   publicPathConfig = '/spa/dist/';
 }
 else {
   DIST_DIR = path.resolve(__dirname,"dist-dev");
   fileNameConfig = "[name].bundle.js";
   publicPathConfig = '/spa/dist-dev/';

 }
config = {
 entry:
 {
   app: SRC_DIR,
   vendor: ['react']
 },
 output: {
   path: DIST_DIR,
   publicPath: publicPathConfig,
   filename: fileNameConfig
 },
 plugins: [
   new webpack.optimize.CommonsChunkPlugin({
     name: "vendor",
     minChunks: Infinity,
     filename: fileNameConfig,
   }),
   new HtmlWebpackPlugin({
       template: path.join(__dirname, 'template.html'),
       filename: path.join(DIST_DIR, 'index.html'),
       inject: 'body',
   }),
   new ExtractTextPlugin({
     filename: '[name].style.css',
     allChunks: true
   }),
   // new CleanWebpackPlugin(['dist'])
 ],

 module:{
   loaders:[
     {
       test: /\.js$/,
       include: SRC_DIR,
       exclude: /node_modules/,
       loader: "babel-loader",
       query:{
         presets:['react','stage-2',['es2015',{modules: false}]],
       }
     },
     {
       test: /\.css$/,
       use: ExtractTextPlugin.extract({
         fallback: "style-loader",
         use: {
           loader: "css-loader",
           options: {
             sourceMap: true
           }
         }
       })
     },
     {
       test: /\.(jpe?g|png|gif|svg)$/i,
       loader: "file-loader?name=icons/[name].[ext]"
     }
   
   ]
 },
}

module.exports = config;