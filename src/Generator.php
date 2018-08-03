<?php
namespace Ebb;

use \Psr\Log\LoggerInterface;

class Generator
{
    /**
     * entities to be used in interface generation
     * @var Entity[]
     */
    public $entities = [];

    private $api;

    private $log;

    public function __construct(RestApi $api, LoggerInterface $log)
    {
        $this->api = $api;
        $this->log = $log;
    }

    /**
     * loads entities from the API and adds valid entities to the
     * $this->entities array
     */
    public function run()
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
                $this->entities[$entity->name] = $entity;
            } catch (\Exception $e) {
                $this->log->notice($e->getMessage());
            }
        }
    }

    public function dump()
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}
