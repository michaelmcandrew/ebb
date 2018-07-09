<?php
namespace Ebb;

use Illuminate\Support\Str;

class Field
{
    public function __construct($original)
    {
        $this->name = $original['name'];
        $this->type = CiviTypes::$translation[$original['type']];
        if (isset($original['label'])) {
            $this->label = $original['label'];
        } elseif (isset($original['title'])) {
            $this->label = $original['title'];
        }
    }

    public function getName()
    {
        return $this->name;
    }
}
