<?php

namespace Fogio\Repository;

use Fogio\Container\ContainerTrait;

class Repository extends Container
{
    /**
     * @var RepositoryManager
     */
    protected $_manager;

    /* setup */

    public function setRepositoryManager(RepositoryManager $manager)
    {
        $this->_manager = $manager;

        return $this;
    }

    public function getRepositoryManager()
    {
        return $this->_db;
    }

    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setExtensions($extensions)
    {
        // clean caches
        unset(
            $this->_extFetch, $this->_extFetchAll,
            $this->_extSave, $this->_extRemove,
            $this->_init // for onExtendInterface
        );

        $this->_ext = $extensions;
        
        return $this;
    }

    public function getExtensions()
    {
        return $this->_ext;
    }

    /* provide */

    protected function provideName() 
    {
        return lcfirst((new \ReflectionClass($this))->getShortName());
    }

    protected function provideExtensions()
    {
        return [];
    }
    
    /* read */
    
    public function fetch($query)
    {
        return (new Process($this->_extFetch, 'onFetch', [
            'repository' => $this,
            'query' => $query,
            'result' => (object)['query' => $query, 'result' => null]
        ]))->__invoke()->result;
    }

    public function fetchAll($query)
    {
        return (new Process($this->_extFetchAll, 'onFetchAll', [
            'repository' => $this,
            'query' => $query,
            'result' => (object)['query' => $query, 'result' => null]
        ]))->__invoke()->result;
    }

    public function count($query)
    {
        return $this->fetchAll($query)->meta->all;
    }

    /* write */

    public function save(array $entity)
    {
        return (new Process($this->_extSave, 'onSave', ['repository' => $this, 'entity' => $entity]))->__invoke()->result;
    }

    public function remove(array $entity)
    {
        return (new Process($this->_extRemove, 'onRemove', ['repository' => $this, 'entity' => $entity]))->__invoke()->result;
    }

    /* lazy */

    protected function __name()
    {
        return $this->setName($this->provideName())->getName();
    }

    protected function __key()
    {
        return $this->setKey($this->provideKey())->getKey();
    }

    protected function __ext()
    {
        return $this->setExtensions($this->provideExtensions())->getExtensions();
    }

    protected function __extFetch()
    {
        return $this->_extIndex('Fetch', OnFetchInterface::class);
    }

    protected function __extFetchAll()
    {
        return $this->_extIndex('FetchAll', OnFetchAllInterface::class);
    }

    protected function __extSave()
    {
        return $this->_extIndex('Update', OnUpdateInterface::class);
    }

    protected function __extRemove()
    {
        return $this->_extIndex('Delete', OnDeleteInterface::class);
    }

    protected function _extIndex($operation, $interface)
    {
        $index = "_ext$operation"; 
        $this->$index = [];
        foreach ($this->_extension as $extension) {
            if ($extension instanceof $interface) {
                $this->$index[] = $extension;
            }
        }
        $this->$index[] = $this;
        return $this->$index;
    }

    protected function __init()
    {
        foreach ($this->_ext as $extension) {
            if ($extension instanceof OnExtendInterface) {
                $extension->onExtend($this);
            }
        }
    }

}
