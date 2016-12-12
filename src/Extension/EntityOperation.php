<?php

namespace Fogio\Repository\Extension;

use Fogio\Repository\OnFetchAllInterface;
use Fogio\Repository\OnFetchInterface;
use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Repository\Operation\EntityOperationPost;
use Fogio\Repository\Operation\EntityOperationPre;
use Fogio\Util\MiddlewareProcess as Process;

class EntityOperation extends Pool implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveInterface
{
    public function setExtensions($extensions)
    {
        return parent::setExtensions(array_merge(
            [new EntityOperationPre()],
            $operations,
            [new EntityOperationPost()]
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
