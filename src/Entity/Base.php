<?php

namespace Fogio\Repository\Entity;

use Fogio\Db\Table\Table;
use Fogio\Repository\OnFetchAllInterface;
use Fogio\Repository\OnFetchInterface;
use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess as Process;
use LogicException;

class Base implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveInterface
{

    public function onFetch(Process $process)
    {
        $this->fetch($process, 'fetch');
    }

    public function onFetchAll(Process $process)
    {
        $this->fetch($process, 'fetchAll');
    }

    public function onSave(Process $process)
    {
        /* @var $table Table */
        $table = f()->db->entity;

        $process->entity = (object)[
            'id' => null,
        ];

        if (array_key_exists('entity_id', $process->param)) { // by entity_id
            if (!ctype_digit($process->param['entity_id'])) {
                throw new LogicException();
            }
            $process->entity->id = $table->fetchVal([':select' => 'entity_id', 'entity_id' => $process->param['entity_id']]);
            if (!$process->entity->id) {
                $process->result->error = 'Entity with entity_id = `' . ((string)$process->param['entity_id']) . '` not found';
                $process->result->result = false;
                return;
            }
        }
        else if (array_key_exists('entity_origin', $process->param)) { // by entity_origin
            if (!is_string($process->param['entity_origin'])) {
                throw new LogicException();
            }
            $process->entity->id = $table->fetchVal([':select' => 'entity_id', 'entity_origin' => (string)$process->param['entity_origin']]);
            if (!$process->entity->id) {
                $process->result->error = 'Entity with entity_origin = `' . ((string)$process->param['entity_id']) . '` not found';
                $process->result->result = false;
                return;
            }
        }

        $process->entity->new = $process->result->new = !$process->entity->id;

        // create entity record
        $entity = [];
        foreach ($table->getFields() as $field) {
            if (array_key_exists($field, $process->param) && $field != 'entity_id' && $field != 'entity_type') {
                $entity[$field] = $process->param[$field];
            }
        }

        if (!$process->entity->id) {
            $entity['entity_type'] = $process->repository->getName();
            $table->insert($entity);
            $process->entity->id = $table->getDb()->lastInsertId();
        }
        else {
            $table->update($entity, ['entity_id' => $process->entity->id]);
        }

        $process->result->id = $process->entity->id;

        $process();
    }

    public function onRemove(Process $process)
    {
        /* @var $table Table */
        $table = f()->db->entity;

        if (array_key_exists('entity_id', $process->param)) { // by entity_id
            if (!ctype_digit($process->param['entity_id'])) {
                throw new LogicException();
            }
            $process->entity->id = $process->param['entity_id'];
        } elseif (array_key_exists('entity_origin', $process->param)) { // by entity_origin
            if (!is_string($process->param['entity_origin'])) {
                throw new LogicException();
            }
            $process->entity->id = $table->fetchVal([':select' => 'entity_id', 'entity_origin' => $process->param['entity_origin']]);
        } else {
            throw new LogicException('Expected `entity_id` or `entity_origin` param');
        }

        $table->delete(['entity_id' => $process->entity->id]);

        $process();
    }

    protected function fetch(Process $process, $method)
    {
        $table = f()->db->entity;
        $proces->param[':select'] = array_merge((array)$process->param[':select'], $table->getFields());
        $proces->param[':from'] = "{$table->getName()}";

        $process();

        $process->result->result = $table->$method($process->param);
    }

}
