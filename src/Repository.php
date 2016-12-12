<?php

namespace Fogio\Repository;

use Fogio\Container\ContainerTrait;

class Repository
{
    use ContainerTrait;
    use ExtensionTrait;

    /**
     * @var RepositoryManager
     */
    protected $_manager;

    /* setup */

    public function setRepositoryManager(Container $manager)
    {
        $this->_manager = $manager;

        return $this;
    }

    public function getRepositoryManager()
    {
        return $this->_manager;
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


    /* provide */

    protected function provideName()
    {
        return lcfirst((new \ReflectionClass($this))->getShortName());
    }

    protected function provideKey()
    {
        return $this->_name . '_id';
    }

    /* read */
    
    public function fetch(array $query)
    {
        return $this->_process('Fetch', $query);
    }

    public function fetchAll(array $query)
    {
        return $this->_process('FetchAll', $query);
    }

    public function count($query)
    {
        return $this->fetchAll($query)->meta->all;
    }

    /* write */

    public function save(array $entity)
    {
        return $this->_process('Save', $entity);
    }

    public function remove(array $entity)
    {
        return $this->_process('Remove', $entity);
    }

    protected function _process($id, $param)
    {
        return (new Process($this->{"_ext$id"}, "on$id", [
            'id' => $id,
            'repository' => $this,
            'param'  => $param,
            'result' => (object)['meta' => (object)['param' => $param], 'result' => null],
        ]))->__invoke()->result;
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

}
