<?php


class DocBlocTestClass
{
    /**
     * @var int
     */
    private $id         = 0;

    /**
     * @var string
     */
    private $string     = '';

    /**
     * @var array
     */
    private $array      = array();

    /**
     * @var bool
     */
    private $boolean    = true;

    /**
     * @var object
     */
    private $object;

    /**
     * @var float
     */
    private $float;

    /**
     * @var
     */
    private $null;

    /**
     * @var \TestNestedObject
     */
    private $nested;

    /**
     * @var \TestNestedObject[]
     */
    private $nestedArray;

    /**
     * @param array $array
     *
     * @return $this
     */
    public function setArray($array)
    {
        $this->array = $array;
        return $this;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @param bool $boolean
     *
     * @return $this
     */
    public function setBoolean($boolean)
    {
        $this->boolean = $boolean;
        return $this;
    }

    /**
     * @return bool
     */
    public function getBoolean()
    {
        return $this->boolean;
    }

    /**
     * @param float $float
     *
     * @return $this
     */
    public function setFloat($float)
    {
        $this->float = $float;
        return $this;
    }

    /**
     * @return float
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setNull($null)
    {
        $this->null = $null;
        return $this;
    }

    public function getNull()
    {
        return $this->null;
    }

    /**
     * @param object $object
     *
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $string
     *
     * @return $this
     */
    public function setString($string)
    {
        $this->string = $string;
        return $this;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param \TestNestedObject $nested
     *
     * @return $this
     */
    public function setNested($nested)
    {
        $this->nested = $nested;
        return $this;
    }

    /**
     * @return \TestNestedObject
     */
    public function getNested()
    {
        return $this->nested;
    }

    /**
     * @param \TestNestedObject[] $nestedArray
     *
     * @return $this
     */
    public function setNestedArray($nestedArray)
    {
        $this->nestedArray = $nestedArray;
        return $this;
    }

    /**
     * @return \TestNestedObject[]
     */
    public function getNestedArray()
    {
        return $this->nestedArray;
    }
}
