<?php

namespace Brash\PopoMapper;

class MapUtil
{
    /**
     * @param $data
     * @param $object
     *
     * @return array|object
     */
    public static function map($data, $object)
    {
        $mapper = new Mapper();
        return $mapper->mapSingle($data, $object);
    }

    /**
     * @param $data
     * @param $collectionType
     * @param $object
     *
     * @return array
     */
    public static function mapMulti($data, $collectionType, $object)
    {
        $mapper = new Mapper();
        return $mapper->mapMulti($data, $collectionType, $object);
    }
}
