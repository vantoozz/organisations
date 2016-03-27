<?php

namespace App;

use App\Collections\OrganisationsCollection;
use App\Exceptions\InvalidArgumentException;

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
     * @var OrganisationsCollection
     */
    protected $parents;

    /**
     * Organisation constructor.
     * @param $title
     */
    public function __construct($title)
    {
        $this->title = (string)$title;
        $this->parents = new OrganisationsCollection();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param Organisation $parent
     * @throws InvalidArgumentException
     */
    public function addParent(Organisation $parent)
    {
        $this->parents->push($parent);
    }

    /**
     * @return OrganisationsCollection
     */
    public function getParents()
    {
        return $this->parents;
    }
}
