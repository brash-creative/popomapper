<?php

include 'DocBlocTestClass.php';
include 'TestParametersClass.php';
include 'TestNestedObject.php';
include 'TestFullClass.php';

class MapperTest extends PHPUnit_Framework_TestCase
{
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
        $mapper     = new \Brash\PopoMapper\Mapper();
        $mapper->dataToArray(1);
    }

    public function testParseDocBloc()
    {
        $mapper             = new \Brash\PopoMapper\Mapper();
        $object             = new DocBlocTestClass();
        $reflection         = new ReflectionClass($object);
        $intMethod          = $reflection->getMethod('setId');
        $stringMethod       = $reflection->getMethod('setString');
        $arrayMethod        = $reflection->getMethod('setArray');
        $objectMethod       = $reflection->getMethod('setObject');
        $boolMethod         = $reflection->getMethod('setBoolean');
        $floatMethod        = $reflection->getMethod('setFloat');
        $nullMethod         = $reflection->getMethod('setNull');
        $nestMethod         = $reflection->getMethod('setNested');
        $nestArrayMethod    = $reflection->getMethod('setNestedArray');

        $this->assertEquals('int', $mapper->getPropertyType($intMethod->getDocComment()));
        $this->assertEquals('string', $mapper->getPropertyType($stringMethod->getDocComment()));
        $this->assertEquals('array', $mapper->getPropertyType($arrayMethod->getDocComment()));
        $this->assertEquals('object', $mapper->getPropertyType($objectMethod->getDocComment()));
        $this->assertEquals('bool', $mapper->getPropertyType($boolMethod->getDocComment()));
        $this->assertEquals('float', $mapper->getPropertyType($floatMethod->getDocComment()));
        $this->assertNull($mapper->getPropertyType($nullMethod->getDocComment()));
        $this->assertEquals('\TestNestedObject', $mapper->getPropertyType($nestMethod->getDocComment()));
        $this->assertEquals('\TestNestedObject[]', $mapper->getPropertyType($nestArrayMethod->getDocComment()));
    }

    public function testSimpleType()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $result1    = $mapper->isSimpleType('bool');
        $result2    = $mapper->isSimpleType('ArrayObject');

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    public function testFlatType()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $result1    = $mapper->isFlatType('bool');
        $result2    = $mapper->isFlatType('mixed');
        $result3    = $mapper->isFlatType('ArrayObject');

        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertFalse($result3);
    }

    public function testSetParameter()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestParametersClass();
        $mixObject  = new ArrayObject();

        $mapper->setParameter($object, 'id', 1, null);
        $mapper->setParameter($object, 'name', 'test', 'setName');
        $mapper->setParameter($object, 'mixed', 'mixed', 'setMixed');
        $mapper->setParameter($object, 'objectMixed', $mixObject, 'setObjectMixed');

        $this->assertEquals(1, $object->id);
        $this->assertEquals('test', $object->getName());
        $this->assertEquals('mixed', $object->getMixed());
        $this->assertInstanceOf('ArrayObject', $object->getObjectMixed());
    }

    public function testNonExistantParameter()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestParametersClass();
        $reflection = new ReflectionClass($object);

        $result     = $mapper->inspectParameter($reflection, 'nonExistantParameter');

        $this->assertTrue(is_array($result));
        $this->assertEquals(array(false, null, null), $result);
    }

    public function testNoSetterButPrivateParameterExists()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestParametersClass();
        $reflection = new ReflectionClass($object);

        $result     = $mapper->inspectParameter($reflection, 'privateParameter');

        $this->assertTrue(is_array($result));
        $this->assertEquals(array(false, null, null), $result);
    }

    public function testNoSetterButPublicParameterExists()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestParametersClass();
        $reflection = new ReflectionClass($object);

        $result     = $mapper->inspectParameter($reflection, 'id');

        $this->assertTrue(is_array($result));
        $this->assertEquals(array(true, 'int', null), $result);
    }

    public function testSetterPrivateParameter()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestParametersClass();
        $reflection = new ReflectionClass($object);

        $result     = $mapper->inspectParameter($reflection, 'name');

        $this->assertTrue(is_array($result));
        $this->assertEquals(array(true, 'string', 'setName'), $result);
    }

    public function testPrivateSetterProtectedParameter()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestParametersClass();
        $reflection = new ReflectionClass($object);

        $result     = $mapper->inspectParameter($reflection, 'protectedParameter');

        $this->assertTrue(is_array($result));
        $this->assertEquals(array(false, null, null), $result);
    }

    public function testMapSingle()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestFullClass();
        $array      = array(
            'id'        => 1,
            'name'      => 'test',
            'private'   => 2,
            'date'      => '2014-10-01 00:00:00',
            'nested'    => array(
                'id'    => 100,
                'name'  => 'nested test'
            ),
            'nestedArray'   => array(
                array(
                    'id'    => 1000,
                    'name'  => 'nested array test 1'
                ),
                array(
                    'id'    => 2000,
                    'name'  => 'nested array test 2'
                )
            ),
            'nonExistentClass'  => array(
                array('id'    => 1)
            ),
            'array' => array(1,2,3),
            'erroneous' => 'should be forgotten'
        );

        $result         = $mapper->mapSingle($array, $object);
        $nestedArray    = $result->getNestedArray();
        $array          = $result->getArray();

        $this->assertInstanceOf('TestFullClass', $result);
        $this->assertInstanceOf('TestNestedObject', $result->getNested());
        $this->assertEquals('test', $result->getName());
        $this->assertEquals(0, $result->getPrivate());
        $this->assertInstanceOf('DateTime', $result->getDate());
        $this->assertTrue(is_array($result->getNestedArray()));
        $this->assertInstanceOf('TestNestedObject', $nestedArray[0]);
        $this->assertInstanceOf('TestNestedObject', $nestedArray[1]);
        $this->assertTrue(is_array($result->getArray()));
        $this->assertEquals(2, $array[1]);
    }

    public function testMapMulti()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $object     = new TestFullClass();
        $array      = array(
            array(
                'id'        => 1,
                'name'      => 'test 1',
                'nested'    => array(
                    'id'    => 100,
                    'name'  => 'nested test 1'
                )
            ),
            array(
                'id'        => 2,
                'name'      => 'test 2',
                'nested'    => array(
                    'id'    => 200,
                    'name'  => 'nested test 2'
                )
            ),
        );

        $result = $mapper->mapMulti($array, new ArrayObject(), $object);

        $this->assertInstanceOf('ArrayObject', $result);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf('TestFullClass', $result[0]);
        $this->assertInstanceOf('TestFullClass', $result[1]);
    }

    /**
     * @expectedException        \Brash\PopoMapper\MapperException
     * @expectedExceptionMessage Class \NonExistentClass does not exist
     */
    public function testDebug()
    {
        $mapper     = new \Brash\PopoMapper\Mapper();
        $mapper->setDebug(true);

        $object     = new TestFullClass();
        $array      = array(
            'id'        => 1,
            'name'      => 'test',
            'private'   => 2,
            'nested'    => array(
                'id'    => 100,
                'name'  => 'nested test'
            ),
            'nestedArray'   => array(
                array(
                    'id'    => 1000,
                    'name'  => 'nested array test 1'
                ),
                array(
                    'id'    => 2000,
                    'name'  => 'nested array test 2'
                )
            ),
            'nonExistentClass'  => array(
                array('id'    => 1)
            ),
            'array' => array(1,2,3),
            'erroneous' => 'should be forgotten'
        );

        $mapper->mapSingle($array, $object);
    }
}
