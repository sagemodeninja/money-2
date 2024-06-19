const fs = require('fs');
const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');

const fileTest = /^(view|layout)\.php(\.ts)?$/;
const scriptTest = /\.ts$/;

function lookupFiles(directory, route = '') {
    const files = [];
    const fileList = fs.readdirSync(directory);
        
    fileList.forEach(file => {
        const name = path.join(route, file);
        const filePath = path.join(directory, file);

        if (fs.statSync(filePath).isDirectory()) {
            const subFiles = lookupFiles(filePath, name);
            files.push(...subFiles);
        }

        if (fileTest.test(file)) {
            const type = getFileType(file)
            
            files.push({
                route,
                type,
                path: './' + filePath,
                chunk: path.join(route, 'index')
            });
        }
    })

    return files;
}

function getFileType(file) {
    if (file === 'layout.php')
        return 'layout'

    if (scriptTest.test(file))
        return 'entry'

    return 'view'
}

function loadViews(directory) {
    const files = lookupFiles(directory);

    return files
            .filter(file => file.type === 'view')
            .map(file => {
                return new HtmlWebpackPlugin({
                    template: file.path,
                    filename: path.join('views', file.route, 'index.php'),
                    chunks: [file.chunk]
                })
            });
}

function loadEntries(directory) {
    const files = lookupFiles(directory);

    return files
            .filter(file => file.type === 'entry')
            .reduce((entries, file) => {
                return { ...entries, [file.chunk]: file.path }
            }, {});
}

function loadLayouts(directory) {
    const files = lookupFiles(directory);

    return files
            .filter(file => file.type === 'layout')
            .reduce((patterns, file) => {
                const layout = path.join(file.route, 'layout.php')
                return [ ...patterns, {
                    from: file.path,
                    to: layout
                }]
            }, []);
}

module.exports = {
    loadViews,
    loadEntries,
    loadLayouts
}