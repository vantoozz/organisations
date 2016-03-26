<?php

namespace App;

/**
 * Class Organisation
 * @package App
 */
class Organisation
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $id;

    /**
     * Organisation constructor.
     * @param $title
     */
    public function __construct($title)
    {
        $this->title = (string)$title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }
}
