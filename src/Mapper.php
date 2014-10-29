<?php

namespace Brash\PopoMapper;

class Mapper
{
    /**
     * @var array
     */
    private $inspectedProperties = array();

    /**
     * @var bool
     */
    private $debug   = false;

    /**
     * @param bool $debug
     *
     * @return $this
     */
    public function setDebug($debug = false)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param $data
     *
     * @return mixed
     * @throws MapperException
     */
    public function parseJson($data)
    {
        if (null === $array = json_decode($data, true)) {
            throw new MapperException("Invalid JSON string provided", 400);
        }

        return $array;
    }

    /**
     * @param $data
     *
     * @return array
     */
    public function checkIfMulti($data)
    {
        if (count($data) == count($data, COUNT_RECURSIVE)) {
            return false;
        }
        return true;
    }

    /**
     * @param $data
     * @param $object
     *
     * @return array|object
     */
    public function map($data, $object)
    {
        if (true === $this->checkIfMulti($data)) {
            return $this->mapSingle($data, $object);
        }
        return $this->mapMulti($data, $object);
    }

    /**
     * @param $data
     * @param $object
     *
     * @return object
     * @throws MapperException
     */
    public function mapSingle($data, $object)
    {
        if (true === is_string($data)) {
            $data   = $this->parseJson($data);
        }

        if (true === is_object($data)) {
            $data   = (array) $data;
        }

        if (false === is_array($data)) {
            throw new MapperException('Invalid data passed to mapper');
        }

        $className  = get_class($object);
        $reflection = new \ReflectionClass($object);
        $nameSpace  = $reflection->getNamespaceName();

        foreach ($data as $key => $value) {
            if (false === isset($this->inspectedProperties[$className][$key])) {

            }
        }

        return $object;
    }

    public function inspectParameter($reflection, $key)
    {

    }

    /**
     * @param $data
     * @param $object
     *
     * @return array
     */
    public function mapMulti($data, $object)
    {
        $maps   = array();

        return $maps;
    }
}
 