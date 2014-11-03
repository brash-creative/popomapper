<?php

namespace Brash\PopoMapper;

class MapUtil
{
    /**
     * @var \Brash\PopoMapper\Mapper
     */
    private static $mapper;

    /**
     * @param $data
     * @param $object
     *
     * @return array|object
     */
    public static function map($data, $object)
    {
        if (null === self::$mapper) {
            self::$mapper = new Mapper();
        }
        return self::$mapper->map($data, $object);
    }
}
