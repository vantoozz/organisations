<?php

namespace App\Console\Commands;

use App\Hydrators\RelationsCollection\JsonRelationsCollectionHydrator;
use App\Repositories\Organisations\OrganisationsRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config;

/**
 * Class CreateOrganisationsCommand
 * @package App\Console\Commands
 */
class GetOrganisationRelationsCommand extends Command
{
    const BUFFER_SIZE = 1000;
    /**
     * @var string
     */
    protected $signature = 'organisations:relations {title : Organisation title}';

    /**
     * @var string
     */
    protected $description = 'Get organisation relations';

    /**
     * @param OrganisationsRepositoryInterface $repository
     * @param JsonRelationsCollectionHydrator $hydrator
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function handle(OrganisationsRepositoryInterface $repository, JsonRelationsCollectionHydrator $hydrator)
    {
        $title = $this->argument('title');

        $relations = $repository->getRelationsByTitle($title, 1);

        $this->info($hydrator->extract($relations));
    }
}
