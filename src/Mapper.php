<?php

namespace Brash\PopoMapper;

class Mapper
{
    /**
     * @var array
     */
    private $inspectedParameters = array();

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
            if (false === isset($this->inspectedParameters[$className][$key])) {
                $this->inspectedParameters[$className][$key]    = $this->inspectParameter($reflection, $key);
            }

            list($settable, $type, $setter) = $this->inspectedParameters[$className][$key];

            if (true === $settable) {

            }
        }

        return $object;
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

    /**
     * @param \ReflectionClass $reflection
     * @param                  $key
     *
     * @return bool
     */
    public function inspectParameter(\ReflectionClass $reflection, $key)
    {
        $settings   = array(false, null, null);

        if (true === $reflection->hasProperty($key)) {
            $setter     = 'set' . ucfirst($key);

            if (true === $reflection->hasMethod($setter)) {
                $method     = $reflection->getMethod($setter);

                if (true === $method->isPublic()) {
                    $docBloc    = $method->getDocComment();
                    $type       = $this->getPropertyType($docBloc);

                    if (null === $type) {
                        return array(true, null, $method->getName());
                    }

                    return array(true, $type, $method->getName());
                }
            }

            $property   = $reflection->getProperty($key);

            if (true === $property->isPublic()) {
                $docBloc    = $property->getDocComment();
                $type       = $this->getPropertyType($docBloc);

                if (null === $type) {
                    return array(true, null, null);
                }

                return array(true, $type, null);
            }
        }

        return $settings;
    }

    /**
     * @param $docBlock
     *
     * @return null|string
     */
    public function getPropertyType($docBlock)
    {
        $type       = null;
        $docBlock   = substr($docBlock, 3, -2);
        $pattern    = '/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m';

        if (preg_match_all($pattern, $docBlock, $matches)) {
            if (true === array_key_exists('value', $matches) && count($matches['value']) > 0) {
                list($type) = explode(' ', $matches['value'][0]);

                return $type;
            }
        }

        return $type;
    }

    /**
     * Checks if the given type is a "simple type"
     *
     * @param string $type type name from gettype()
     *
     * @return boolean True if it is a simple PHP type
     */
    public function isSimpleType($type)
    {
        return $type == 'string'
        || $type == 'boolean' || $type == 'bool'
        || $type == 'integer' || $type == 'int'
        || $type == 'float' || $type == 'array' || $type == 'object';
    }

    /**
     * Checks if the given type is a type that is not nested
     * (simple type except array and object)
     *
     * @param string $type type name from gettype()
     *
     * @return boolean True if it is a non-nested PHP type
     */
    public function isFlatType($type)
    {
        return $type == 'NULL'
        || $type == 'string'
        || $type == 'boolean' || $type == 'bool'
        || $type == 'integer' || $type == 'int'
        || $type == 'float';
    }

    /**
     * @param $object
     * @param $key
     * @param $value
     * @param $setter
     */
    public function setParameter($object, $key, $value, $setter)
    {
        if ($setter === null) {
            $object->$key   = $value;
        } else {
            $object->{$setter}($value);
        }
    }
}
 