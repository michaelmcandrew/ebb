<?php
namespace Ebb;

use Ebb\InterfaceFactory;
use Ebb\Generator;

class CiviCRMSchemaFactory extends InterfaceFactory
{

    public function generate()
    {
        $this->mkdir("{$this->outputDir}");
        file_put_contents("php://stdout", json_encode($this->entities, JSON_PRETTY_PRINT)."\n");
    }
}
