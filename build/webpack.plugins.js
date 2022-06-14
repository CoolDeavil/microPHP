const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyPlugin = require('copy-webpack-plugin');

exports.buildPlugs = () => {
    
    return  [
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
            // filename: "./css/[name].[hash].css",
            filename: "./css/[name].min.css",
        }),
    ]
}
