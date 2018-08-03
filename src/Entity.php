<?php
namespace Ebb;

use Illuminate\Support\Str;

class Entity
{
    private $log;

    public function __construct($name, $entity, $log)
    {
        $this->name = $name;
        $this->log = $log;
        $this->setFields($entity);
        $this->setLabelField();
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

    public function setLabelField()
    {
        $choices = ['display_name', 'title', 'label'];
        foreach ($choices as $choice) {
            if (isset($this->fields[$choice])) {
                $this->labelField = $choice;
                return;
            }
        }
        $this->labelField = false;
        return;
    }
}
