const path = require('path')
const autoload = require('./autoload')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CopyPlugin = require('copy-webpack-plugin')
const FrameworkCompilerPlugin = require('./framework-compiler')

module.exports = (_, {mode}) => {
    const isDevelopment = mode === 'development'

    return {
        entry: autoload.loadEntries('./src'),
        output: {
            filename: isDevelopment ? '[name].js' : '[name].[contenthash].js',
            path: path.resolve(__dirname, 'build'),
            clean: true,
        },
        plugins: [
            ...autoload.loadViews('./src/gen/views'),
            new MiniCssExtractPlugin({
                filename: isDevelopment ? '[name].css' : '[name].[contenthash].css'
            }),
            new CopyPlugin({
                patterns: [
                    './src/.htaccess',
                    './src/index.php',
                    { from: './src/framework', to: 'framework' },
                    { from: './src/api', to: 'api' },
                    { from: './src/static', to: 'static' }
                ]
            }),
            new FrameworkCompilerPlugin()
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
                '@': path.resolve(__dirname, 'src/scripts'),
            }
        },
        devtool: isDevelopment ? 'inline-source-map' : false
    }
}