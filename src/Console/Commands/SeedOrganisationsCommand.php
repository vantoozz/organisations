<?php

namespace App\Console\Commands;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config;
use Illuminate\Database\ConnectionInterface;
use PDOException;

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
     * @var ConnectionInterface
     */
    private $db;
    /**
     * @var Generator
     */
    private $faker;

    /**
     * SeedOrganisationsCommand constructor.
     * @param ConnectionInterface $db
     * @param Generator $faker
     */
    public function __construct(ConnectionInterface $db, Generator $faker)
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
        $bar = $this->output->createProgressBar($count);

        for ($i = 0; $i < $count; $i++) {
            try {
                $this->seedOrganisation();
            } catch (PDOException $e) {
//                $this->error($e->getMessage());
            }
            $bar->advance();
        }
        $bar->finish();
    }

    /**
     *
     */
    private function seedOrganisation()
    {
        $title = $this->faker->company . ' ' . $this->faker->companySuffix . ', ' . $this->faker->city;
        $title .= ', ' . mt_rand(1, 99999);
        $this->db->insert('INSERT INTO `organisations` (`title`) VALUES (:title);', ['title' => $title]);
        $id = $this->db->selectOne('SELECT id FROM organisations WHERE title = :title;', ['title' => $title])->id;

        if (mt_rand(1, 100) === 1) {
            return;
        }

        for ($n = mt_rand(1, 3); $n >= 0; $n--) {
            $parent_id = mt_rand(1, max(2, round($id / 20)));
            $this->db->insert(
                'INSERT INTO `relations` (`organisation_id`, `parent_id`) VALUES (:id, :parent_id);',
                ['id' => $id, 'parent_id' => $parent_id]
            );
        }
    }
}
