<?php

namespace Fogio\Repository\Operation;

use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess;

class EntityOperationPost implements OnSaveInterface
{

    public function onSave(MiddlewareProcess $process)
    {
        $process();

        if ($process->operation->is) {
            $process->operation->entityPost = $process->repository->fetch(['entity_id' => $process->result->id]);
        }
    }

}
