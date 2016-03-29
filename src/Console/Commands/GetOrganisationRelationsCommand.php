<?php

namespace App\Console\Commands;

use App\Hydrators\RelationsCollection\ArrayRelationsCollectionHydrator;
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
    protected $signature = 'organisations:relations {title : Organisation title} {--p|page=1 : Page}';

    /**
     * @var string
     */
    protected $description = 'Get organisation relations';

    /**
     * @param OrganisationsRepositoryInterface $repository
     * @param ArrayRelationsCollectionHydrator $hydrator
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function handle(OrganisationsRepositoryInterface $repository, ArrayRelationsCollectionHydrator $hydrator)
    {
        $title = $this->argument('title');

        $relations = $repository->getRelationsByTitle($title, (int)$this->option('page'));

        $this->info(json_encode($hydrator->extract($relations)));
    }
}
