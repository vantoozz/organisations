<?php

namespace App;

use App\Exceptions\InvalidArgumentException;

/**
 * Class RelationType
 * @package App
 */
class RelationType
{
    const PARENT = 'parent';
    const SISTER = 'sister';
    const DAUGHTER = 'daughter';

    /**
     * @var array
     */
    private static $types = [self::PARENT, self::SISTER, self::DAUGHTER];

    /**
     * @var string
     */
    private $type;

    /**
     * RelationType constructor.
     * @param $type
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function __construct($type)
    {
        if (!in_array($type, self::$types, true)) {
            throw new InvalidArgumentException('Bad relation type: ' . $type);
        }

        $this->type = $type;
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        return self::$types;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
