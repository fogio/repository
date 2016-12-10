<?php

namespace Fogio\Repository;

use Fogio\Util\MiddlewareProcess as Process;

interface OnFetchAllInterface
{
    public function onFetchAll(Process $process);
}
