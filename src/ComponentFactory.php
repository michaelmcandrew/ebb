<?php
namespace Ebb;

use \Psr\Log\LoggerInterface;

class ComponentFactory
{
    public function __construct(RestApi $api, LoggerInterface $log)
    {
        $this->api = $api;
        $this->log = $log;
        $this->loadEntities();

        foreach ($this->entities as $entityName => $entity) {
            try {
                $component = new Component($entityName, $entity);
                $this->components[$component->names['kebab']] = $component;
            } catch (\Exception $e) {
                $this->log->warning($e->getMessage());
            }
        }
    }

    protected function loadEntities()
    {
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
        $this->entities = $entities;
    }

    protected function mapRelationships()
    {
        foreach ($component['fields'] as $field) {
        }
    }

    // private function loadComponents()
    // {
    //     // API calls to gather all info needed to create components
    //
    //     // Generate names for components
    //
    //         $this-
    //     }
    //
    //
    //     // Get many to one relationships
    //     foreach ($this->components as &$component) {
    //         foreach ($component['fields'] as $field) {
    //             if (!empty($field['FKApiName'])) {#
    //                 $component['rels']['mto'] = [$field['FKApiName']];
    //             }
    //         }
    //     }

        //
        //
        //
        // foreach ($entities['values'] as $entity) {
        //     $component['name']['upper_camel'] = ucfirst(Str::camel($entity));
        //     $component['name']['lower_camel'] = lcfirst(Str::camel($entity));
        //     $component['name']['kebab'] = Str::kebab($entity);
        //     $component['name']['sentence_case'] = ucfirst(str_replace('-', ' ', Str::kebab($entity)));
        //     // $fields = $api->query($entity, 'getfields')['values'];
        //     // foreach ($fields as $k => &$field) {
        //     //     if ((empty($field['entity']))) {
        //     //         unset($fields[$k]);
        //     //     } elseif ($field['entity'] != $entity) {
        //     //         unset($fields[$k]);
        //     //     }
        //     //     // Clean types
        //     //     if (!isset($field['type'])) {
        //     //         $field['type'] = 2;
        //     //     }
        //     //     if (isset($types[$field['type']])) {
        //     //         $field['ts_type'] = $types[$field['type']];
        //     //     } else {
        //     //         echo "Could not find type for {$entity}.{$field['name']}\n";
        //     //         var_dump($field);
        //     //     }
        //     // }
        //     $this->components[$entity] = $component;
        // }
    // }
}
