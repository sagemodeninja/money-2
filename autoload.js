const fs = require('fs');
const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');

const scriptTest = /\.ts$/;

function lookupFiles(directory, mask) {
    const files = fs.readdirSync(directory, {withFileTypes: true,recursive: true})
    const filtered = files.filter(f => mask.test(f.name))
        
    return filtered.map(file => {
        const fpath = path.join(file.path, file.name)

        return {
            path: fpath,
            route: file.path,
            type: getFileType(file)
        }
    })
}

function getFileType(file) {
    if (file === 'layout.php')
        return 'layout'

    if (scriptTest.test(file))
        return 'entry'

    return 'view'
}

function loadEntries(directory) {
    const files = lookupFiles(directory, /^view.php.ts$/);

    return files
            .reduce((entries, file) => {
                const chunk = file.route.replace('src/', '') + '/index'
                const path = './' + file.path
                return { ...entries, [chunk]: path}
            }, {});
}

function loadViews(directory) {
    const files = lookupFiles(directory, /^index.php$/);

    return files
            .map(file => {
                return new HtmlWebpackPlugin({
                    template: file.path,
                    filename: file.path.replace('src/gen/', ''),
                    chunks: file.route.replace('src/gen/', '') + '/index'
                })
            });
}

module.exports = {
    loadEntries,
    loadViews
}