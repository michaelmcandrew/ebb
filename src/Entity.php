<?php
namespace Ebb;

use Illuminate\Support\Str;

class Entity
{
    public function __construct($name, $entity, $log)
    {
        $this->log = $log;
        $this->setNames($name);
        $this->setFields($entity);
    }

    private function setNames($name)
    {
        $this->names['original'] = $name;
        $this->names['upper_camel'] = ucfirst(Str::camel($name));
        $this->names['lower_camel'] = lcfirst(Str::camel($name));
        $this->names['kebab'] = Str::kebab($name);
        $this->names['sentence'] = ucfirst(str_replace('-', ' ', Str::kebab($name)));
    }

    private function setFields($entity)
    {
        $this->original = $entity;
        if (empty($entity['fields'])) {
            throw new \Exception("No fields defined for entity '{$this->names['original']}'.");
        }
        foreach ($entity['fields'] as $original) {
            try {
                if (!empty($original['entity']) && $original['entity'] == $this->names['original']) {
                    $field = new Field($original);
                    $this->fields[$field->getName()] = $field;
                } else {
                    throw new \Exception('blah');
                }
            } catch (\Exception $e) {
                $this->log->info($e->getMessage());
            }
        }
        if (!isset($this->fields)) {
            throw new \Exception("No fields with 'entity' defined for entity '{$this->names['original']}'.");
        }
    }
}
