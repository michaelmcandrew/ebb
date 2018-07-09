<?php
namespace Ebb;

use Illuminate\Support\Str;

class Field
{
    public function __construct($original, Entity $entity)
    {
        $this->name = $original['name'];
        $this->entity = $entity;
        $this->type = CiviTypes::$translation[$original['type']];
        if (isset($original['label'])) {
            $label = ucfirst(strtolower($original['label']));
        } elseif (isset($original['title'])) {
            $label = ucfirst(strtolower($original['title']));
        } else {
            throw new \Exception("Could not find label for {$original['name']} ({$entity->names['original']})");
        }
        if (strpos($label, $entity->names['sentence']) === 0) {
            $label = ucfirst(trim(substr($label, strlen($entity->names['sentence']))));
        }
        if ($label == '') {
            $label = 'Name';
        }
        $this->label = $label;
    }

    public function getName()
    {
        return $this->name;
    }
}
