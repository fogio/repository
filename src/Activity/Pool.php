<?php

namespace Fogio\Repository\Activity;

use Fogio\Middleware\Process;
use Fogio\Middleware\MiddlewareTrait;

class Pool
{
    use MiddlewareTrait;

    public function onFetch(Process $process)
    {
        $process->prepend($this->getActivitiesWithMethod($process->getMethod()));
        $process();
    }

    public function onFetchAll(Process $process)
    {
        $process->prepend($this->getActivitiesWithMethod($process->getMethod()));
        $process();
    }

    public function onSave(Process $process)
    {
        $process->prepend($this->getActivitiesWithMethod($process->getMethod()));
        $process();
    }

    public function onRemove(Process $process)
    {
        $process->prepend($this->getActivitiesWithMethod($process->getMethod()));
        $process();
    }

}
