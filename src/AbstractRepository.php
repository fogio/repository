<?php

namespace Fogio\Repository;

use Fogio\Db\Db;
use Fogio\Container\ContainerTrait;
use Fogio\Util\MiddlewareProcess as Process;

class AbstractRepository
{
    use ContainerTrait;

    /**
     * @var Db 
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
    
    public function getFetcher()
    {
        return [':select' => $this->_fields, ':from' => $this->_name];
    }

    public function fetch($fdq)
    {
        return (new Process($this->_extFetch, 'onFetch', ['repository' => $this, 'fdq' => $fdq + $this->getFetcher()]))->__invoke()->result;
    }

    public function fetchAll($fdq)
    {
        return (new Process($this->_extFetchAll, 'onFetchAll', ['repository' => $this, 'fdq' => $fdq + $this->getFetcher()]))->__invoke()->result;
    }

    public function count($params = null, $expr = '*')
    {
        return $this->_db->count($fdq + [':from' => $this->getName()], $expr);
    }

    /* write */

    public function save(array $data, array $fdq)
    {
        return (new Process($this->_extSave, 'onSave', ['repository' => $this, 'data' => $data, 'fdq' => $fdq]))->__invoke()->result;
    }

    public function remove(array $fdq)
    {
        return (new Process($this->_extRemove, 'onRemove', ['repository' => $this, 'fdq' => $fdq]))->__invoke()->result;
    }

    /* extension */

    protected function onFetch(Process $process)
    {
        $process->result = $this->getDb()->fetch($process->fdq);
        $process();
    }

    protected function onFetchAll(Process $process)
    {
        $process->result = $this->getDb()->fetchAll($process->fdq);
        $process();
    }

    protected function onInsert(Process $process)
    {
        $process->result = $this->getDb()->insert($this->_name, $process->row);
        $process();
    }

    protected function onInsertAll(Process $process)
    {
        $process->result = $this->getDb()->insertAll($this->_name, $process->rows);
        $process();
    }

    protected function onUpdate(Process $process)
    {
        $process->result = $this->getDb()->update($this->_name, $process->data, $process->fdq);
        $process();
    }

    protected function onDelete(Process $process)
    {
        $process->result = $this->getDb()->delete($this->_name, $process->fdq);
        $process();
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

    protected function __fields()
    {
        return $this->setFields($this->provideFields())->getFields();
    }

    protected function __links()
    {
        return $this->setLinks($this->provideLinks())->getLinks();
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

    protected function __extInsert()
    {
        return $this->_extIndex('Insert', OnInsertInterface::class);
    }

    protected function __extInsertAll()
    {
        return $this->_extIndex('InsertAll', OnInsertAllInterface::class);
    }

    protected function __extUpdate()
    {
        return $this->_extIndex('Update', OnUpdateInterface::class);
    }

    protected function __extDelete()
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
