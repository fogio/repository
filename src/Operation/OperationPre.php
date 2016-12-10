<?php

namespace Fogio\Repository\Operation;

use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess;

class OperationPre implements OnSaveInterface, OnRemoveInterface
{

    public function onSave(MiddlewareProcess $process)
    {
        if ($process->operation->is) {
            $process->operation->pre = $process->repository->fetch([':operation' => false] + $process->query);
        }

        $process();
    }

    public function onRemove(MiddlewareProcess $process)
    {
        if ($process->operation->is) {
            $process->operation->pre = $process->repository->fetch([':operation' => false] + $process->query);
        }

        $process();
    }

}