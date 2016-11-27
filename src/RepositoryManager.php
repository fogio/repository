<?php

namespace Fogio\Repository;

use Fogio\Db\Db;
use Fogio\Container\ContainerTrait;
use Fogio\Util\MiddlewareProcess as Process;

class RepositoryManager
{
    use ContainerTrait;

    /**
     * @var Db 
     */
    protected $_db;

    /* setup */

    public function setDb(Db $db)
    {
        $this->_db = $db;

        return $this;
    }

    public function getDb()
    {
        return $this->_db;
    }

    public function setExtensions($extensions)
    {
        $this->_ext = $extensions;
        
        return $this;
    }

    public function getExtensions()
    {
        return $this->_ext;
    }

    /* provide */

    protected function provideExtensions()
    {
        return [];
    }
    
    /* lazy */
    
    protected function __ext()
    {
        return $this->setExtensions($this->provideExtensions())->getExtensions();
    }


}
