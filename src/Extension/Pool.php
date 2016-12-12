<?php

namespace Fogio\Repository\Extension;

use Fogio\Repository\ExtensionTrait;
use Fogio\Repository\OnFetchAllInterface;
use Fogio\Repository\OnFetchInterface;
use Fogio\Repository\OnRemoveInterface;
use Fogio\Repository\OnSaveInterface;
use Fogio\Repository\Operation\EntityOperationPost;
use Fogio\Repository\Operation\EntityOperationPre;
use Fogio\Util\MiddlewareProcess as Process;

class Pool implements OnFetchInterface, OnFetchAllInterface, OnSaveInterface, OnRemoveInterface
{
    use ExtensionTrait;

    public function onFetch(Process $process)
    {
        $this->pool($process);
    }

    public function onFetchAll(Process $process)
    {
        $this->pool($process);
    }

    public function onSave(Process $process)
    {
        $this->pool($process);
    }

    public function onRemove(Process $process)
    {
        $this->pool($process);
    }

    protected function pool(Process $process)
    {
        if (($pool = $this->{"_ext" . $process->id})) {
            $process->prepend($pool);
        }

        $process();
    }

}
