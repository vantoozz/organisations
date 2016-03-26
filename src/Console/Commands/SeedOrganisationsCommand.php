<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config;
use PDO;

/**
 * Class SeedOrganisationsCommand
 * @package App\Console\Commands
 */
class SeedOrganisationsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'organisations:seed {count=1000 : Count of organisations to seed}';

    /**
     * @var string
     */
    protected $description = 'Seeds fake organisations';
    /**
     * @var Connection
     */
    private $db;
    /**
     * @var Generator
     */
    private $faker;

    /**
     * SeedOrganisationsCommand constructor.
     * @param Connection $db
     * @param Generator $faker
     */
    public function __construct(Connection $db, Generator $faker)
    {
        parent::__construct();
        $this->db = $db;
        $this->faker = $faker;
    }

    /**
     *
     */
    public function handle()
    {
        $count = (int)$this->argument('count');
        for ($i = 0; $i < $count; $i++) {
            try {
                $this->seedOrganisation();
            } catch (DBALException $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function seedOrganisation()
    {
        $title = $this->faker->company . ' ' . $this->faker->companySuffix . ', ' . $this->faker->city;
        $title .= ', ' . mt_rand(1, 99999);

        $this->db->executeQuery(
            'INSERT INTO `organisations` (`title`) VALUES (:title);',
            ['title' => $title],
            [\PDO::PARAM_STR]
        );
        $id = (int)$this->db->lastInsertId();

        if (mt_rand(1, 100) === 1) {
            return;
        }

        for ($n = mt_rand(1, 3); $n >= 0; $n--) {
            $parent_id = mt_rand(1, max(2, round($id / 20)));
            $this->db->executeQuery(
                'INSERT INTO `relations` (`organisation_id`, `parent_id`) VALUES (:id, :parent_id);',
                ['id' => $id, 'parent_id' => $parent_id],
                [\PDO::PARAM_INT, \PDO::PARAM_INT]
            );
        }

        $this->info($title);
    }
}
