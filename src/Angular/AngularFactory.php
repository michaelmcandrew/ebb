<?php
namespace Ebb\Angular;

use Ebb\InterfaceFactory;
use Ebb\Generator;

class AngularFactory extends InterfaceFactory
{
    public $templateDefinitions = [
        'model' => [
            'template' => 'angular/entity.model.ts.twig',
            'filename' => 'app/entities/{{entity.names.kebab}}.model.ts'
        ],
        'component' => [
            'template' => 'angular/entity/entity.component.ts.twig',
            'filename' => 'app/entities/{{entity.names.kebab}}/{{entity.names.kebab}}.component.ts'
        ],
        'componentHTML' => [
            'template' => 'angular/entity/entity.component.html.twig',
            'filename' => 'app/entities/{{entity.names.kebab}}/{{entity.names.kebab}}.component.html'
        ],
        'componentTest' => [
            'template' => 'angular/entity/entity.component.spec.ts.twig',
            'filename' => 'app/entities/{{entity.names.kebab}}/{{entity.names.kebab}}.component.spec.ts'
        ],
        'componentCSS' => [
            'template' => 'angular/entity/entity.component.css.twig',
            'filename' => 'app/entities/{{entity.names.kebab}}/{{entity.names.kebab}}.component.css'
        ]
    ];

    public function generate()
    {
        foreach ($this->entities as $entity) {
            $componentDir = "{$this->outputDir}/app/{$entity->names['kebab']}";
            if (!is_dir($componentDir)) {
                mkdir($componentDir);
            }
            foreach (array_keys($this->templateDefinitions) as $def) {
                $this->generator->generate($def, [
                    'entity' => $entity,
                    'fields' => $entity->fields
                ]);
            }
        }
    }


    public function generateComponentDirectory($entity)
    {
        $componentDir = "{$this->outputDir}/app/{$entity->names['kebab']}";
        if (!is_dir($componentDir)) {
            mkdir($componentDir);
        }
    }

    public function generateComponentFile($entity)
    {
        $filename = "{$this->outputDir}/app/{$entity->names['kebab']}/{$entity->names['kebab']}.component.ts";
        $output = $this->templates['component']->render([
            'name' => $name,
            'fields' => $fields,
        ]);
        file_put_contents($filename, $output);
    }

    public function generateComponentHTML($entity)
    {
        $filename = "{$this->outputDir}/app/{$entity->names['kebab']}/{$entity->names['kebab']}.component.html";
        $output = $this->templates['componentHTML']->render([
            'name' => $name,
            'fields' => $fields,
        ]);
        file_put_contents($filename, $output);
    }

    public function generateComponentTest($entity)
    {
        $filename = "{$this->outputDir}/app/{$entity->names['kebab']}/{$entity->names['kebab']}.component.spec.ts";
        $output = $this->templates['componentTest']->render([
            'name' => $name,
            'fields' => $fields,
        ]);
        file_put_contents($filename, $output);
    }

    public function generateComponentCSS($entity)
    {
        $filename = "{$this->outputDir}/app/{$entity->names['kebab']}/{$entity->names['kebab']}.component.css";
        $output = $this->templates['componentCSS']->render([
            'name' => $name,
            'fields' => $fields,
        ]);
        file_put_contents($filename, $output);
    }
}
