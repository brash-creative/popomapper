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
     * @return array|mixed
     * @throws MapperException
     */
    public function dataToArray($data)
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

        return $data;
    }

    /**
     * @param  $data
     * @param  $object
     *
     * @return mixed
     * @throws MapperException
     */
    public function mapSingle($data, $object)
    {
        $data       = $this->dataToArray($data);
        $className  = get_class($object);
        $reflection = new \ReflectionClass($object);
        $nameSpace  = $reflection->getNamespaceName();

        foreach ($data as $key => $value) {
            if (false === isset($this->inspectedParameters[$className][$key])) {
                $this->inspectedParameters[$className][$key]    = $this->inspectParameter($reflection, $key);
            }

            list($settable, $type, $setter) = $this->inspectedParameters[$className][$key];

            if (true === $settable) {
                if (null === $type || 'mixed' == $type) {
                    $this->setParameter($object, $key, $value, $setter);
                    continue;
                }

                if (true === $this->isSimpleType($type)) {
                    settype($value, $type);
                    $this->setParameter($object, $key, $value, $setter);
                    continue;
                }

                /**
                 * More complex and user defined types
                 */
                $objectArray    = null;
                $subType        = null;
                $child          = null;

                if (substr($type, -2) == '[]') {
                    $objectArray    = array();
                    $subType        = substr($type, 0, -2);
                } elseif (substr($type, -1) == ']') {
                    list($propertyType, $subType) = explode('[', substr($type, 0, -1));
                    $objectArray    = new $propertyType();
                } elseif ($type == 'ArrayObject' || true === is_subclass_of($type, 'ArrayObject')) {
                    $objectArray    = new $type();
                }

                if (null !== $objectArray) {
                    if (false === $this->isSimpleType($subType)) {
                        $subType    = $this->getFullType($subType, $nameSpace);
                    }

                    if (false === class_exists($subType)) {
                        if (true === $this->getDebug()) {
                            throw new MapperException('Class ' . $subType . ' does not exist');
                        }
                        continue;
                    }

                    $child  = $this->mapMulti($value, $objectArray, $subType);
                } elseif (true === $this->isFlatType(gettype($value))) {
                    if (null !== $value) {
                        $type   = $this->getFullType($type, $nameSpace);
                        $child  = new $type($value);
                    }
                } else {
                    $type   = $this->getFullType($type, $nameSpace);

                    if (false === class_exists($type)) {
                        if (true === $this->getDebug()) {
                            throw new MapperException('Class ' . $type . ' does not exist');
                        }

                        continue;
                    }

                    $child  = new $type();
                    $this->mapSingle($value, $child);
                }

                $this->setParameter($object, $key, $child, $setter);
            }
        }

        return $object;
    }

    /**
     * @param      $data
     * @param      $array
     * @param null $object
     *
     * @return array
     */
    public function mapMulti($data, $array, $object = null)
    {
        $data   = $this->dataToArray($data);

        foreach ($data as $key => $value) {
            if (null === $object) {
                $array[$key]    = $value;
            } elseif (true === $this->isFlatType(gettype($value))) {
                if (null === $value) {
                    $array[$key]    = null;
                } else {
                    $array[$key]    = new $object($value);
                }
            } else {
                $array[$key]    = $this->mapSingle($value, new $object());
            }
        }

        return $array;
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
     * @param $type
     * @param $nameSpace
     *
     * @return string
     */
    public function getFullType($type, $nameSpace)
    {
        if ($type{0} != '\\') {
            if ($nameSpace != '') {
                $type = '\\' . $nameSpace . '\\' . $type;
            }
        }
        return $type;
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
        $type   = strtolower($type);

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
        $type   = strtolower($type);

        return $type == 'null'
        || $type == 'mixed'
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
