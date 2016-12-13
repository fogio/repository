<?php

namespace Fogio\Repository\Entity;

use Fogio\Repository\Extension\Table;
use Fogio\Repository\OnFetchAllInterface;
use Fogio\Repository\OnFetchInterface;
use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;

class Main implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveInterface
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
        $this->fetch($process, 'fetch');
    }

    public function onFetchAll(Process $process)
    {
        $this->fetch($process, 'fetchAll');
    }

    public function onSave(Process $process)
    {
        $table = $this->getTable($process);
        $key   = $table->getKey();
        $main  = [];

        foreach ($table->getFields() as $field) {
            if (array_key_exists($field, $process->param) && $field != $key) {
                $main[$field] = $process->param[$field];
            }
        }

        if ($process->entity->new) {
            $main[$key] = $process->entity->id;
            $table->insert($main);
        }
        else {
            $table->update($main, [$key => $process->entity->id]);
        }
    }

    public function onRemove(Process $process)
    {
        $table = $this->getTable($process);
        $key   = $table->getKey();

        $table->delete([$key => $process->entity->id]);
    }

    protected function fetch(Process $process, $method)
    {
        $table = f()->db->entity;
        $proces->param[':select'] = \array_merge((array)$process->param[':select'], $table->getFields());
        $proces->param[':from'] = "{$table->getName()}";

        $process();

        $process->result->result = $table->{$method}($process->param);
    }

}
