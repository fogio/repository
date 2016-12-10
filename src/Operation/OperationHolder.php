<?php

namespace Fogio\Repository;

use \Fogio\Util\MiddlewareProcess as Process;

class OperationHolder implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveInterface
{

    protected $_operations;

    public function setOperations(array $operations)
    {
        $this->_operations = array_merge(
            [(new OperationPre())->setOperationHolder($this)],
            $operations,
            [(new OperationPost())->setOperationHolder($this)]
        );

        return $this;
    }

    public function onFetchAll(Process $process)
    {
        $this->operation($process);
    }

    public function onFetch(Process $process)
    {
        $this->operation($process);
    }

    public function onSave(Process $process)
    {
        $this->operation($process);
    }

    public function onRemove(Process $process)
    {
        $this->operation($process);
    }

    protected function operation(Process $process)
    {
        $process->operation = (object)['is' => $process->query[':operation'] !== false];

        if ($process->operation->is) {
            $process->prepend($this->_operations);
        }

        $process();
    }

}
