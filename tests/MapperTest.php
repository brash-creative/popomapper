<?php


class MapperTest extends PHPUnit_Framework_TestCase
{
    public function testMultiCheckWithOneDimension()
    {
        $test       = array('name' => 'test 1');
        $mapper     = new \Brash\PopoMapper\Mapper();
        $result     = $mapper->checkIfMulti($test);

        $this->assertFalse($result);
    }

    public function testMultiCheckWithMulti()
    {
        $test       = array(
            array('name' => 'test 1'),
            array('name' => 'test 2')
        );

        $mapper     = new \Brash\PopoMapper\Mapper();
        $result     = $mapper->checkIfMulti($test);

        $this->assertTrue($result);
    }

    /**
     * @expectedException        \Brash\PopoMapper\MapperException
     * @expectedExceptionMessage Invalid JSON string provided
     */
    public function testParseJsonFails()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $mapper->parseJson('Test');
    }

    public function testParseJsonSuccess()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $result     = $mapper->parseJson('{"test":1}');

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('test', $result);
        $this->assertEquals(1, $result['test']);
    }

    /**
     * @expectedException        \Brash\PopoMapper\MapperException
     * @expectedExceptionMessage Invalid data passed to mapper
     */
    public function testInvalidData()
    {
        $object     = new stdClass();
        $mapper     = new \Brash\PopoMapper\Mapper();
        $mapper->mapSingle(1, $object);
    }
}
 