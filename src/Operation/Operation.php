<?php

namespace Fogio\Repository;

use Fogio\Repository\OnFetchInterface;
use Fogio\Repository\OnFetchAllInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Repository\OnRemoveInterface;
use Fogio\Util\MiddlewareProcess as Process;

class Operation implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveAllInterface
{

    public function onFetchAll(Process $process)
    {
        $process->operation = (object)['is' => $process->fdq[':operation'] !== false];

        if ($process) {

        }

        $process();


    }

    public function onInsertAll(Process $process)
    {
        $time = time();
        foreach ($process->rows as $k => $row) {
            if (!array_key_exists($field, $row)) {
                $process->rows[$k][$field] = $time;
            }
        } 
        $process();
    }

    protected function getField(Process $process)
    {
        if ($this->field === null) {
            $this->field = "{$process->table->getName()}_insert";
        }

        return $this->field;
    }

}
