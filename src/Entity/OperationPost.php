<?php

namespace Fogio\Repository\Entity;

use Fogio\Repository\OnSaveInterface;
use Fogio\Util\MiddlewareProcess;

class OperationPost implements OnSaveInterface
{

    public function onSave(MiddlewareProcess $process)
    {
        $process();

        if ($process->operation->is) {
            $process->operation->entityPost = $process->repository->fetch(['entity_id' => $process->result->id]);
        }
    }

}
