<?php

namespace App\Console\Commands;

use App\Hydrators\OrganisationsCollection\JsonOrganisationsCollectionHydrator;
use App\Repositories\Organisations\OrganisationsRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config;

/**
 * Class CreateOrganisationsCommand
 * @package App\Console\Commands
 */
class CreateOrganisationsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'organisations:create {json : JSON encoded data}';

    /**
     * @var string
     */
    protected $description = 'Create organisations';

    /**
     * @param OrganisationsRepositoryInterface $repository
     * @param JsonOrganisationsCollectionHydrator $hydrator
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function handle(OrganisationsRepositoryInterface $repository, JsonOrganisationsCollectionHydrator $hydrator)
    {
        $json = $this->argument('json');
        $collection = $hydrator->hydrate($json);
        $repository->store($collection);
        $this->info('All done');
    }
}
