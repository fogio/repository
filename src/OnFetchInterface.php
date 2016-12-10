<?php

namespace Fogio\Repository;

use Fogio\Util\MiddlewareProcess as Process;

interface OnFetchInterface
{
    public function onFetch(Process $process);
}
