<?php

namespace App\Console\Commands;

use App\DataProviders\Organisations\OrganisationsDataProviderInterface;
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
     * @param OrganisationsDataProviderInterface $dataProvider
     * @throws \App\Exceptions\NotFoundException
     */
    public function handle(OrganisationsDataProviderInterface $dataProvider)
    {
        $title = $this->argument('title');

        $id = $dataProvider->getOrganisationId($title);
    }
}
