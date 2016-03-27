<?php

namespace App\Console\Commands;

use App\Exceptions\NotFoundException;
use App\Repositories\Relations\RelationsRepositoryInterface;
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
     * @param RelationsRepositoryInterface $repository
     * @throws NotFoundException
     */
    public function handle(RelationsRepositoryInterface $repository)
    {
        $title = $this->argument('title');

        $data = $repository->getOrganisationRelations($title, 1);
    }
}
