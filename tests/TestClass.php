<?php


class TestClass
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
}
 