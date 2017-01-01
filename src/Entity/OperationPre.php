<?php

namespace Fogio\Repository\Entity;

use Fogio\Middleware\Process;

class OperationPre
{

    public function onSave(Process $process)
    {
        $this->pre($process);

        $process();
    }

    public function onRemove(Process $process)
    {
        $this->pre($process);

        $process();
    }

    protected function pre($process)
    {
        if (!$process->operation->is) {
            return;
        }

        $process->operation->entityPre = null;

        if (array_key_exists('entity_id', $process->query)) {
            $process->operation->entityPre = $process->repository->fetch(['entity_id' => $process->query['entity_id']]);
        } elseif (array_key_exists('entity_origin', $process->query)) {
            $process->operation->entityPre = $process->repository->fetch(['entity_origin' => $process->query['entity_origin']]);
        }
    }

}
