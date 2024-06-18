const path = require('path')
const autoDiscovery = require('./autoload')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CopyPlugin = require('copy-webpack-plugin')

module.exports = (_, {mode}) => {
    const isDevelopment = mode === 'development'
    
    return {
        entry: autoDiscovery.lookupEntries('./src'),
        output: {
            filename: isDevelopment ? '[name].js' : '[name].[contenthash].js',
            path: path.resolve(__dirname, 'build'),
            clean: true,
        },
        plugins: [
            ...autoDiscovery.lookupViews('./src'),
            new MiniCssExtractPlugin({
                filename: isDevelopment ? '[name].css' : '[name].[contenthash].css'
            }),
            new CopyPlugin({
                patterns: [
                    './src/.htaccess',
                    './src/index.php'
                ]
            }),
        ],
        module: {
            rules: [
                {
                    test: /\.ts$/,
                    use: 'ts-loader',
                    exclude: /node_modules/
                },
                {
                    test: /\.s[ac]ss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'sass-loader',
                    ],
                },
            ]
        },
        resolve: {
            extensions: ['.ts', '.js'],
            alias: {
                '@': path.resolve(__dirname, 'src'),
            }
        },
        devtool: isDevelopment ? 'inline-source-map' : false
    }
}