<?php
namespace Ebb;

use Illuminate\Support\Str;

class Component
{
    public function __construct($name, $entity)
    {
        $this->entity = $entity;
        $this->setNames($name);
        $this->setProperties();
    }

    private function setNames($name)
    {
        $this->name = $this->names['original'] = $name;
        $this->names['UpperCamel'] = ucfirst(Str::camel($name));
        $this->names['lowerCamel'] = lcfirst(Str::camel($name));
        $this->names['kebab'] = Str::kebab($name);
        $this->names['sentence'] = ucfirst(str_replace('-', ' ', Str::kebab($name)));
    }

    private function setProperties()
    {
        if (empty($this->entity['fields'])) {
            throw new \Exception("No fields defined for {$this->name}.");
        }
        foreach ($this->entity['fields'] as $field) {
            if (!empty($field['entity']) && $field['entity'] == $this->name) {
                $property = new Property($field['name']);
                $property->setType($field['type']);
                $property->setLabel($field['title']);
            }
        }
    }
}
