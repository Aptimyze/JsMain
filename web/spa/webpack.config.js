var webpack = require("webpack");
var path = require("path");
var HtmlWebpackPlugin = require('html-webpack-plugin');
var combineLoaders = require('webpack-combine-loaders');
var ExtractTextPlugin = require("extract-text-webpack-plugin");
var staticServer = require("./src/common/constants/apiServerConstants");
var SRC_DIR = path.resolve(__dirname,"src");
var DIST_DIR = null;
var fileNameConfig = '';
var publicPathConfig = '';
const NameAllModulesPlugin = require('name-all-modules-plugin');
if(process.env.NODE_ENV === 'production')
 {
   DIST_DIR = path.resolve(__dirname,"dist");
   fileNameConfig = "[name].bundle.js";
   publicPathConfig = staticServer.STATIC_SERVER+'/spa/dist/';
 }
 else {
   DIST_DIR = path.resolve(__dirname,"dist");
   fileNameConfig = "[name].bundle.js";
   publicPathConfig = '/spa/dist/';

}
config = {
  entry:
  {
    app: SRC_DIR,
    vendor: ['react', 'react-dom','axios','redux','react-redux']
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
      template: path.join(SRC_DIR, 'template.html'),
      filename: path.join(DIST_DIR, 'index.html'),
      inject: 'body',
  }),
  new ExtractTextPlugin({
    filename: '[name].style.css',
    allChunks: true
  }),
  new webpack.LoaderOptionsPlugin({
    options: {
     disableDotRule: true
    }
  }),
   new webpack.NamedModulesPlugin(),
   new webpack.optimize.CommonsChunkPlugin({
       name: 'runtime'
   }),
   new NameAllModulesPlugin(),
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

devServer: {
     historyApiFallback: {
       disableDotRule: true,
     },
   },

}

module.exports = config;
