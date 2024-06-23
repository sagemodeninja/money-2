<?php
    namespace Framework\Api\Attributes;

    use Attribute;
    use ReflectionClass;

    #[Attribute(Attribute::TARGET_PARAMETER)]
    class FromBody {
        public string $class;

        public function __construct(string $class = "")
        {
            $this->class = $class;
        }

        public function parse(mixed $raw)
        {
            $class = $this->class;

            if (!empty($class))
            {
                $reflection = new ReflectionClass($class);
                $instance = $reflection->newInstance();
    
                foreach ($reflection->getProperties() as $property)
                {
                    $name = $property->getName();
                    if (array_key_exists($name, $raw)) {
                        $instance->{$name} = $raw[$name];
                    }
                }
    
                return $instance;
            }

            return $raw;
        }
    }
?>