<?php
    namespace Framework\Views;

    class ViewSection
    {
        public string $name;
        public string $content;

        public function __construct(string $name, string $content)
        {
            $this->name = $name;
            $this->content = $content;
        }

        public static function parse(string $source)
        {
            $sections = [];

            if (preg_match_all(SECTION_PATTERN, $source, $matches, PREG_SET_ORDER))
            {
                foreach ($matches as $match)
                {
                    $section = new ViewSection($match[1], $match[2]);
                    array_push($sections, $section);
                }
            }

            return $sections;
        }
    }
?>