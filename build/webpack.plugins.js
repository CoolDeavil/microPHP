const path = require("path");

const webpack = require("webpack");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyPlugin = require('copy-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

exports.buildPlugs = (mode) => {
    let filename_ = '';
    if(mode){
        filename_ = "./css/[name].[fullhash].min.css";
    }else{
        filename_ = "./css/[name].min.css";
    }

    return  [

        new HtmlWebpackPlugin({
            inject: false,
            filename: '../Micro/Views/partials/styles.twig',
            templateContent: ({htmlWebpackPlugin}) => `${htmlWebpackPlugin.files.css.map(file => `${file}`, )}`
        }),
        new HtmlWebpackPlugin({
            inject: false,
            filename: '../Micro/Views/partials/scripts.twig',
            templateContent: ({htmlWebpackPlugin}) => `${htmlWebpackPlugin.files.js.map(file => `${file}`, )}`
        }),

        // new BrowserSyncPlugin({
        //         proxy: 'http://192.168.18.18',
        //         host: '192.168.18.18',
        //         port: 3000,
        //         open: false,
        //         files: [
        //             {
        //                 match: [
        //                     '**/*.php',
        //                     '**/*.twig',
        //                 ],
        //                 // eslint-disable-next-line no-unused-vars
        //                 fn: function(event, file) {
        //                     if (event === "change") {
        //                         const bs = require('browser-sync').get('bs-webpack-plugin');
        //                         bs.reload();
        //                     }
        //                 }
        //             }
        //         ]
        //     },
        //     {
        //         reload: true,
        //         //reload: false
        //     }),

        new CopyPlugin({
            patterns:[
                {from: './assets/template/map.php', to: path.resolve(__dirname,'../public') },
                {from: './assets/template/slave.php', to: path.resolve(__dirname,'../public') },
                {from: './assets/template/index.php', to: path.resolve(__dirname,'../public') },
                {from: './assets/template/favicon.ico', to:path.resolve(__dirname,'../public') },
                {from: './assets/template/.htaccess', to: path.resolve(__dirname,'../public') },
            ]
        }),

        new MiniCssExtractPlugin({
            filename: filename_
        }),
    ]
}
