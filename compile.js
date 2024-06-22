const fs = require('fs')
const path = require('path')

const DIRECTORY = 'src/views'

class Compiler {
    static watch() {
        if (!fs.existsSync(DIRECTORY)){
            console.error('/views directory not found!')
        }
        
        const files = fs.readdirSync(DIRECTORY, {withFileTypes: true, recursive: true})
        const assets = Compiler.resolveAssets(files)

        Compiler.generateViews(assets)
        
        assets.forEach(({file}) => {
            fs.watchFile(file, {interval: 500}, () => {
                console.log('Compiling views...')
                Compiler.generateViews(assets)
            })
        })
    }

    static generateViews(assets) {
        const views = assets.filter(a => a.type === 'view')

        views.forEach(view => {
            const layouts = assets.filter(a => a.type === 'layout' && view.path.startsWith(a.path))

            let current = null;

            [view, ...layouts].forEach(asset => {
                const contents = fs.readFileSync(asset.file, 'utf8')
                current = this.resolveView(contents, current)
            })

            const opath = path.join('src/gen/views', view.path.replace(DIRECTORY, ''));
            const output = path.join(opath, 'index.php')

            if (!fs.existsSync(opath)) {
                fs.mkdirSync(opath, {recursive: true})
            }

            fs.writeFileSync(
                output,
                current.contents
            )

            console.log(`Generate '${output}'.`)
        })
    }

    static resolveAssets(files) {
        const results = []
        
        const targets = ['view.php', 'layout.php']
        const assets = files.filter(f => f.isFile() && targets.includes(f.name))

        for (const asset of assets) {
            results.push({
                type: asset.name.slice(0, -4),
                path: asset.path,
                file: path.join(asset.path, asset.name)
            })
        }

        return results.reverse()
    }

    static resolveView(contents, children) {
        const SECTION_PATTERN = /@section\s+"([^"]+)"\s*{([^}]*)}/g
        const matches = contents.matchAll(SECTION_PATTERN)

        // Parse section tokens
        const sections = []
        for (const match of matches) {
            sections.push({
                name: match[1],
                contents: match[2]
            })
        }

        // Remove section tokens
        contents = contents.replace(SECTION_PATTERN, '')

        // Replace @renderChildren w/ children's content.
        contents = contents.replace(
            '@renderChildren()',
            children?.contents ?? ''
        )

        // Replace @renderSection w/ children's section content.
        contents = contents.replace(
            /@renderSection\("([^"]+)"\)/g,
            (_, name) => {
                const section = children.sections.find(s => s.name === name)
                return section?.contents ?? ''
            }
        )

        return {
            sections,
            contents
        }
    }
}

Compiler.watch()