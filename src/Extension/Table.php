<?php

namespace Fogio\Repository\Extension;

use Fogio\Db\Table\Table;
use Fogio\Repository\OnFetchAllInterface;
use Fogio\Repository\OnFetchInterface;
use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess as Process;
use LogicException;

class Table implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveInterface
{

    /* @var Table */
    protected $table;

    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function getTable(Process $process)
    {
        if ($this->table === null) {
            $name = $process->repository->getName();
            $this->table = f()->db->$name;
        }

        return $this->table;
    }

    public function onFetch(Process $process)
    {
        $this->fetch($process);
    }

    public function onFetchAll(Process $process)
    {
        $this->fetch($process);
    }

    public function onSave(Process $process)
    {
        /* @var $table Table */
        $table = $this->getTable($process);
        $key   = $table->getKey();

        $process->record = (object)[
            'id' => null,
        ];

        if (array_key_exists($key, $process->param)) { // by $key
            if (!ctype_digit($process->param[$key])) {
                throw new LogicException();
            }
            $process->record->id = $table->fetchVal([':select' => $key, $key => $process->param[$key]]);
            if (!$process->record->id) {
                $process->result->error = 'Entity with entity_id = `' . ((string)$process->param[$key]) . '` not found';
                $process->result->result = false;
                return;
            }
        }

        $process->record->new = $process->result->new = !$process->entity->id;

        // create entity record
        $record = [];
        foreach ($table->getFields() as $field) {
            if (array_key_exists($field, $process->param) && $field != $key) {
                $record[$field] = $process->param[$field];
            }
        }

        if (!$process->record->id) {
            $table->insert($record);
            $process->record->id = $table->getDb()->lastInsertId();
        }
        else {
            $table->update($record, [$key => $process->record->id]);
        }

        $process->result->id = $process->record->id;

        $process();
    }

    public function onRemove(Process $process)
    {
        $table = $this->getTable($process);
        $key   = $table->getKey();

        if (array_key_exists($key, $process->param)) { // by entity_id
            if (!ctype_digit($process->param[$key])) {
                throw new LogicException();
            }
            $process->record->id = $process->param[$key];
        } else {
            throw new LogicException("Expected `$key` param");
        }

        $table->delete(['$key' => $process->record->id]);

        $process();
    }

    protected function fetch(Process $process)
    {
        $table = $this->getTable($process);

        $proces->param[':join'][] = "JOIN `{$table->getName()}` ON (`{$table->getKey()}` = `entity_id`)";
        $proces->param[':select'] = array_merge((array)$process->param[':select'], $table->getFields());

        $process();
    }

}
