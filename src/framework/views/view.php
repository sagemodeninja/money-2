<?php
    namespace Framework\Views;

    const SECTION_PATTERN = '/@section\s+"([^"]+)"\s*{([^}]*)}/';

    class View
    {
        public readonly string $file;
        public readonly ?View $children;

        public array $sections;
        public string $content;

        public function __construct(string $file, ?View $children = null)
        {
            $this->file = $file;
            $this->children = $children;

            $this->parse();
        }

        public function render()
        {
            ob_start();
            eval("?>$this->content<?php ");
            return ob_get_clean();
        }

        public function renderSection(string $name)
        {
            $matches = array_filter($this->sections, function ($s) use ($name) {
                return $s->name == $name;
            });

            $section = reset($matches);

            if ($section)
            {
                eval('?>' . $section->content . '<?php ');
            }
        }

        private function parse()
        {
            $source = file_get_contents($this->file);

            $this->sections = ViewSection::parse($source);
            $this->content = $this->parseContent($source);
        }

        private function parseContent(string $source)
        {
            $content = preg_replace(SECTION_PATTERN, '', $source);

            # Transform @renderChildren()
            $content = str_replace(
                '@renderChildren()',
                '<?php echo $this->children->render(); ?>',
                $content
            );

            # Transform @renderSection()
            $pathattern = '/@renderSection\("([^"]+)"\)/';
            $content = preg_replace(
                $pathattern,
                '<?php $this->children->renderSection("$1"); ?>',
                $content
            );

            return $content;
        }
    }
?>