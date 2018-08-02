<?php
namespace Ebb;

use Illuminate\Support\Str;

class Entity
{

    private $log;

    public function __construct($name, $entity, $log)
    {
        $this->log = $log;
        $this->setNames($name);
        $this->setFields($entity);
        $this->setLabelField();
    }

    private function setNames($name)
    {
        $this->name = $this->names['original'] = $name;
        $this->names['upperCamel'] = ucfirst(Str::camel($name));
        $this->names['lowerCamel'] = lcfirst(Str::camel($name));
        $this->names['kebab'] = Str::kebab($name);
        $this->names['sentence'] = ucfirst(str_replace('-', ' ', Str::kebab($name)));
    }

    private function setFields($entity)
    {
        $this->original = $entity;
        if (empty($entity['fields'])) {
            throw new \Exception("No fields defined for entity {$this->names['original']}.");
        }
        foreach ($entity['fields'] as $original) {
            try {
                if (!empty($original['entity']) && $original['entity'] == $this->names['original']) {
                    $field = new Field($original, $this);
                    $this->fields[$field->getName()] = $field;
                } else {
                    throw new \Exception("No matching entity field for {$original['name']} ({$this->names['original']}).");
                }
            } catch (\Exception $e) {
                $this->log->info($e->getMessage());
            }
        }
        if (!isset($this->fields)) {
            throw new \Exception("No fields with 'entity' defined for entity {$this->names['original']}.");
        }
    }

    function setLabelField(){
        $choices = ['display_name', 'title', 'label'];
        foreach($choices as $choice){
            if(isset($this->fields[$choice])){
                $this->labelField = $choice;
                return;
            }
        }
        $this->labelField = false;
        return;
    }
}
