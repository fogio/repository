<?php

namespace Fogio\Repository;

use Fogio\Container\Container;
use Fogio\Container\ContainerTrait;
use Fogio\Middleware\MiddlewareTrait;

class Repository
{
    use ContainerTrait;
    use MiddlewareTrait;

    protected $_container;

    /* setup */

    public function setRepositoryContainer(Container $container)
    {
        $this->_container = $container;

        return $this;
    }

    public function getRepositoryContainer()
    {
        return $this->_container;
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

    /* read */
    
    public function fetch(array $query)
    {
        return $this->process('onFetch', [
            'repository' => $this,
            'query' => $param,
            'result' => (object)['meta' => (object)['query' => $query], 'result' => null],
        ])->result;
    }

    public function fetchAll(array $query)
    {
        return $this->process('onFetchAll', [
            'repository' => $this,
            'query' => $param,
            'result' => (object)['meta' => (object)['query' => $query], 'result' => null],
        ])->result;
    }

    /* write */

    public function save(array $entity)
    {
        return $this->process('onSave', [
            'repository' => $this,
            'entity' => $entity,
            'result' => (object)['meta' => (object)['entity' => $entity], 'result' => null],
        ])->result;
    }

    public function remove(array $entity)
    {
        return $this->process('onRemove', [
            'repository' => $this,
            'entity' => $entity,
            'result' => (object)['meta' => (object)['entity' => $entity], 'result' => null],
        ])->result;
    }

    /* lazy */

    protected function __name()
    {
        return $this->setName($this->provideName())->getName();
    }

    protected function __init()
    {
        foreach ($this->getActivitiesWithMethod('onExtend') as $activity) {
            $activity->onExtend($this);
        }
    }

}
