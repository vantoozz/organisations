<?php

namespace App\Collections;

use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Collection;

/**
 * Class TypedCollection
 * @package App\Collections
 */
abstract class TypedCollection extends Collection
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @param array $items
     * @throws InvalidArgumentException
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->checkType($item);
        }

        parent::__construct($items);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function put($key, $value)
    {
        $this->checkType($value);
        return parent::put($key, $value);
    }

    /**
     * @param mixed $value
     * @param null $key
     * @return $this
     * @throws InvalidArgumentException
     */
    public function prepend($value, $key = null)
    {
        $this->checkType($value);
        return parent::prepend($value, $key);
    }

    /**
     * @param mixed $value
     * @return $this
     * @throws InvalidArgumentException
     */
    public function push($value)
    {
        $this->checkType($value);
        return parent::push($value);
    }

    /**
     * @param $item
     * @throws InvalidArgumentException
     */
    protected function checkType($item)
    {
        if ($item instanceof $this->type) {
            return;
        }

        throw new InvalidArgumentException(__CLASS__ . ' accepts elements of type ' . $this->type . ' only');
    }
}
