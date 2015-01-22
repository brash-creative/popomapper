<?php


class TestParametersClass
{
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $privateParameter = '';

    /**
     * @var string
     */
    protected $protectedParameter = '';

    /**
     * @var mixed
     */
    protected $mixed = null;

    /**
     * @var mixed
     */
    protected $objectMixed = null;

    protected $empty;

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
     * @param string $protectedParameter
     *
     * @return $this
     */
    private function setProtectedParameter($protectedParameter)
    {
        $this->protectedParameter = $protectedParameter;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtectedParameter()
    {
        return $this->protectedParameter;
    }

    /**
     * @param mixed $mixed
     *
     * @return $this
     */
    public function setMixed($mixed)
    {
        $this->mixed = $mixed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMixed()
    {
        return $this->mixed;
    }

    /**
     * @param mixed $objectMixed
     *
     * @return $this
     */
    public function setObjectMixed($objectMixed)
    {
        $this->objectMixed = $objectMixed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObjectMixed()
    {
        return $this->objectMixed;
    }

    /**
     * @param mixed $empty
     *
     * @return $this
     */
    public function setEmpty($empty)
    {
        $this->empty = $empty;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmpty()
    {
        return $this->empty;
    }
}
 