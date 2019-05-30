<?php

namespace App;

/**
 * Class Relation
 * @package App
 */
class Relation
{
    /**
     * @var Organisation
     */
    private $from;
    /**
     * @var Organisation
     */
    private $to;
    /**
     * @var RelationType
     */
    private $relationType;

    /**
     * Relation constructor.
     * @param Organisation $from
     * @param Organisation $to
     * @param RelationType $relationType
     */
    public function __construct(Organisation $from, Organisation $to, RelationType $relationType)
    {
        $this->from = $from;
        $this->to = $to;
        $this->relationType = $relationType;
    }

    /**
     * @return Organisation
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return Organisation
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return RelationType
     */
    public function getRelationType()
    {
        return $this->relationType;
    }
}
