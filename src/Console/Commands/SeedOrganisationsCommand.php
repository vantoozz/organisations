<?php

namespace App\Console\Commands;

use App\Collections\OrganisationsCollection;
use App\Exceptions\InvalidArgumentException;
use App\Organisation;
use App\Repositories\Organisations\OrganisationsRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Faker\Generator as FakerGenerator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config;

/**
 * Class SeedOrganisationsCommand
 * @package App\Console\Commands
 */
class SeedOrganisationsCommand extends Command
{
    const BUFFER_SIZE = 1000;
    /**
     * @var string
     */
    protected $signature = 'organisations:seed {count=10000 : Count of organisations to seed}';

    /**
     * @var string
     */
    protected $description = 'Seeds fake organisations';
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var FakerGenerator
     */
    private $faker;

    /**
     * @var int
     */
    private $maxId = 0;
    /**
     * @var OrganisationsRepositoryInterface
     */
    private $repository;

    /**
     * SeedOrganisationsCommand constructor.
     * @param Connection $db
     * @param FakerGenerator $faker
     * @param OrganisationsRepositoryInterface $repository
     */
    public function __construct(Connection $db, FakerGenerator $faker, OrganisationsRepositoryInterface $repository)
    {
        parent::__construct();
        $this->db = $db;
        $this->faker = $faker;
        $this->repository = $repository;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handle()
    {
        $collection = new OrganisationsCollection();
        $count = (int)$this->argument('count');
        $this->maxId = $this->getMaxId();

        $bufferSize = ceil(self::BUFFER_SIZE / 50);

        $collection->push(new Organisation('Seeded organisation'));
            
        while (--$count >= 0) {
            $collection->push($this->createOrganisation());
            if ($bufferSize <= $collection->count()) {
                $collection = $this->flushCollection($collection);
                $bufferSize = self::BUFFER_SIZE;
            }
        }

        $this->flushCollection($collection);
    }

    /**
     * @return int
     */
    private function getMaxId()
    {
        try {
            $id = (int)$this->db->executeQuery('SELECT max(`id`) FROM `organisations`;')->fetchColumn();
        } catch (DBALException $e) {
            $this->error($e->getMessage());
            $id = 0;
        }
        return $id;
    }

    /**
     * @return Organisation
     */
    private function createOrganisation()
    {
        $title = $this->faker->company . ' ' . $this->faker->companySuffix . ', ' . $this->faker->city;
        $title .= ', ' . mt_rand(1, 99999);

        $organisation = new Organisation($title);

        foreach ([95, 60, 33, 5, 2] as $chance) {
            if (mt_rand(1, 100) >= $chance) {
                break;
            }
            /** @noinspection DisconnectedForeachInstructionInspection */
            $this->addParent($organisation);
        }

        $this->info('Created organisation ' . $organisation->getTitle());

        return $organisation;
    }

    /**
     * @param Organisation $organisation
     */
    private function addParent(Organisation $organisation)
    {
        try {
            $title = $this->db->executeQuery(
                'SELECT `title` FROM `organisations` WHERE id >= :id LIMIT 1',
                [mt_rand(0, (int)(0.2 * $this->maxId))],
                [\PDO::PARAM_INT]
            )->fetchColumn();
        } catch (DBALException $e) {
            $this->error($e->getMessage());
            return;
        }

        if (false === $title) {
            return;
        }

        try {
            $organisation->addParent(new Organisation($title));
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @param OrganisationsCollection $collection
     * @return OrganisationsCollection
     * @throws \App\Exceptions\InvalidArgumentException
     */
    private function flushCollection(OrganisationsCollection $collection)
    {
        $this->repository->store($collection);
        $this->maxId = $this->getMaxId();
        $this->info('Organisations saved');
        return new OrganisationsCollection();
    }
}
