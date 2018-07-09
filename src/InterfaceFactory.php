<?php
namespace Ebb;

use \Psr\Log\LoggerInterface;

abstract class InterfaceFactory
{
    /**
     * entities to be used in interface generation
     * @var Entity[]
     */
    public $entities = [];

    public $templateFiles = [];

    public function __construct(RestApi $api, Generator $generator, $outputDir, LoggerInterface $log)
    {
        $this->api = $api;
        $this->log = $log;
        $this->generator = $generator;
        $this->generator->init($this->templateDefinitions, $outputDir);
        $this->outputDir = $outputDir;
        $this->loadEntities();
    }

    /**
     * loads entities from the API and adds valid entities to the
     * $this->entities array
     */
    protected function loadEntities()
    {
        // Query API for valid entities
        $entityGet = $this->api->query('Entity', 'get');
        $entityList = array_diff($entityGet['values'], $entityGet['deprecated']);
        foreach ($entityList as $entityName) {
            $fields = $this->api->query($entityName, 'getfields')['values'];
            $entities[$entityName]['fields'] = $fields;

            // Find many to one relationships
            foreach ($fields as $field) {
                if (!empty($field['FKApiName'])) {#
                    $entities[$entityName]['rels']['mto'][$field['name']] = $field['FKApiName'];
                }
            }
        }

        // Record corresponding one to many relationships
        foreach ($entities as $entityName => $entity) {
            if (!empty($entity['rels']['mto'])) {
                foreach ($entity['rels']['mto'] as $field => $relatedEntity) {
                    $entities[$relatedEntity]['rels']['otm'][$entityName][] = $field;
                }
            }
        }

        // Create entity objects and add them to $this->entities
        foreach ($entities as $entityName => $entity) {
            try {
                $entity = new Entity($entityName, $entity, $this->log);
                $this->entities[$entity->names['kebab']] = $entity;
            } catch (\Exception $e) {
                $this->log->warning($e->getMessage());
            }
        }
    }

    abstract protected function generate();
}
