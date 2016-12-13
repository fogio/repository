<?php

namespace Fogio\Repository\Entity;

use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess;

class OperationPre implements OnSaveInterface, OnRemoveInterface
{

    public function onSave(MiddlewareProcess $process)
    {
        $this->pre();

        $process();
    }

    public function onRemove(MiddlewareProcess $process)
    {
        $this->pre();

        $process();
    }

    protected function pre()
    {
        if (!$process->operation->is) {
            return;
        }

        $process->operation->entityPre = null;

        if (array_key_exists('entity_id', $process->query)) {
            $process->operation->entityPre = $process->repository->fetch(['entity_id' => $process->query]);
        } elseif (array_key_exists('entity_origin', $process->query)) {
            $process->operation->entityPre = $process->repository->fetch(['entity_origin' => $process->query]);
        }
    }

}
