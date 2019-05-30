<?php

namespace App\Hydrators;

/**
 * Interface HydratorInterface
 * @package App\Hydrators
 */
interface HydratorInterface
{

    /**
     * @param mixed $resource
     * @return mixed
     */
    public function extract($resource);

    /**
     * @param mixed $data
     * @return mixed
     */
    public function hydrate($data);
}
