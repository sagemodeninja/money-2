const fs = require('fs')
const path = require('path')

class FrameworkCompilerPlugin {
    constructor(options = {}) {
        this.options = { source: 'src', options };
    }

    apply(compiler) {
        const pluginName = 'FrameworkCompilerPlugin'
        const { RawSource } = compiler.webpack.sources

        /* Generate controller cache */
        compiler.hooks.thisCompilation.tap(pluginName, (compilation) => {
            compilation.hooks.processAssets.tapAsync(
                {
                    name: pluginName,
                    stage: compiler.webpack.Compilation.PROCESS_ASSETS_STAGE_OPTIMIZE_INLINE
                },
                (_, callback) => {
                    const dir = path.join(this.options.source, 'api/controllers')
                    const files = fs.readdirSync(dir)

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

                    callback()
                }
            )
        })
    }
}

module.exports = FrameworkCompilerPlugin