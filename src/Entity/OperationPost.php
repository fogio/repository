<?php

namespace Fogio\Repository\Entity;

use Fogio\Middleware\Process;

class OperationPost
{

    public function onSave(Process $process)
    {
        $process();

        if ($process->operation->is) {
            $process->operation->entityPost = $process->repository->fetch(['entity_id' => $process->result->id]);
        }
    }

}
