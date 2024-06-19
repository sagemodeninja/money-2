const fs = require('fs')
const path = require('path')

class FrameworkCompilerPlugin {
    constructor(options = {}) {
        this.options = { source: 'src', options };
    }

    apply(compiler) {
        const pluginName = 'FrameworkCompilerPlugin'
        const { webpack } = compiler
        const { Compilation } = webpack
        const { RawSource } = webpack.sources

        compiler.hooks.thisCompilation.tap(pluginName, (compilation) => {
            compilation.hooks.processAssets.tap(
                {
                    name: pluginName,
                    stage: Compilation.PROCESS_ASSETS_STAGE_ADDITIONAL
                },
                (assets) => {
                    const dir = path.join(this.options.source, 'api/controllers')
                    
                    fs.readdir(dir, (err, files) => {
                        if (err) {
                            throw err;
                        }

                        const controllers = files.reduce((result, file) => {
                            const {name, ext} = path.parse(file)
                            if (ext == '.php' && name.endsWith('-controller')) {
                                const identifier = name.slice(0, -11).replace(/-/g, '')
                                const className = name.split('-').map(w => w[0].toUpperCase() + w.slice(1)).join('')

                                result = { ...result, [identifier]: className }
                            }

                            return result;
                        }, {})

                        compilation.emitAsset(
                            'api/controllers/controller_cache.json',
                            new RawSource(JSON.stringify(controllers))
                        )
                    })
                }
            )
        })
    }
}

module.exports = FrameworkCompilerPlugin