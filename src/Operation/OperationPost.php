<?php

namespace Fogio\Repository\Operation;

use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess;

class OperationPost implements OnSaveInterface, OnRemoveInterface
{

    public function onSave(MiddlewareProcess $process)
    {
        $process();

        if ($process->operation->is) {
            $process->operation->post = $process->repository->fetch([':operation' => false] + $process->query);
        }
    }

    public function onRemove(MiddlewareProcess $process)
    {
        $process();

        if ($process->operation->is) {
            $process->operation->post = $process->repository->fetch([':operation' => false] + $process->query);
        }
    }

}
