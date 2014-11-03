<?php


class TestFullClass
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TestNestedObject
     */
    private $nested;

    /**
     * @var TestNestedObject[]
     */
    private $nestedArray;

    /**
     * @var NonExistentClass[]
     */
    private $nonExistentClass;

    /**
     * @var array
     */
    private $array = array();

    /**
     * @var int
     */
    private $private = 0;

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

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return int
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @param \NonExistentClass[] $nonExistantClass
     *
     * @return $this
     */
    public function setNonExistentClass($nonExistantClass)
    {
        $this->nonExistentClass = $nonExistantClass;
        return $this;
    }

    /**
     * @return \NonExistantClass[]
     */
    public function getNonExistentClass()
    {
        return $this->nonExistentClass;
    }
}
 