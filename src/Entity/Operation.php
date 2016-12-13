<?php

namespace Fogio\Repository\Entity;

use Fogio\Repository\Extension\Pool;
use Fogio\Repository\OnFetchAllInterface;
use Fogio\Repository\OnFetchInterface;
use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess as Process;

class Operation extends Pool implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveInterface
{
    public function setExtensions($extensions)
    {
        return parent::setExtensions(array_merge(
            [new OperationPre()],
            $operations,
            [new OperationPost()]
        ));
    }

    protected function pool(Process $process)
    {
        $process->operation = (object)['is' => $process->param[':operation'] !== false];

        if ($process->operation->is) {
            parent::pool($process);
        }
        else {
            $process();
        }

    }

}
