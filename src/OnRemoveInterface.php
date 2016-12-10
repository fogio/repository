<?php

namespace Fogio\Repository;

use Fogio\Util\MiddlewareProcess as Process;

interface OnRemoveInterface
{
    public function onRemove(Process $process);
}
