<?php

namespace Fogio\Repository;

use Fogio\Util\MiddlewareProcess as Process;

interface OnSaveInterface
{
    public function onSave(Process $process);
}
