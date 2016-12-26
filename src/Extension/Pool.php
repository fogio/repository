<?php

namespace Fogio\Repository\Extension;

use Fogio\Middleware\Process;

class Pool
{
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
