<?php

namespace Fogio\Repository;

trait ExtensionTrait
{
    /* setup */

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

    protected function provideExtensions()
    {
        return [];
    }

    /* lazy */

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
        return $this->_extIndex('Delete', OnRemoveInterface::class);
    }

    protected function _extIndex($type, $interface)
    {
        $index = "_ext$type";
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
