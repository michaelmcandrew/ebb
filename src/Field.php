<?php
namespace Ebb;

use Illuminate\Support\Str;

class Field
{
    public function __construct($original, Entity $entity)
    {
        $this->name = $original['name'];
        $this->type = CiviTypes::$translation[$original['type']];
        if (isset($original['label'])) {
            $this->label = ucfirst(strtolower($original['label']));
        } elseif (isset($original['title'])) {
            $this->label = ucfirst(strtolower($original['title']));
        } else {
            throw new \Exception("Could not find label for {$original['name']} ({$entity->name})");
        }
    }

    public function getName()
    {
        return $this->name;
    }
}
