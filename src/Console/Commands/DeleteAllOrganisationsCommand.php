<?php

namespace App\Console\Commands;

use App\Repositories\Organisations\OrganisationsRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config;

/**
 * Class CreateOrganisationsCommand
 * @package App\Console\Commands
 */
class DeleteAllOrganisationsCommand extends Command
{
    const BUFFER_SIZE = 1000;
    /**
     * @var string
     */
    protected $signature = 'organisations:delete-all';

    /**
     * @var string
     */
    protected $description = 'Create organisations';

    /**
     * @param OrganisationsRepositoryInterface $repository
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function handle(OrganisationsRepositoryInterface $repository)
    {

        if (!$this->confirm('Delete all organisations?', true)) {
            $this->info('Nothing done');

            return;
        }

        $repository->deleteAll();
        
        $this->info('All done');
    }
}
