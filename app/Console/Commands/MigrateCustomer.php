<?php

namespace App\Console\Commands;

use App\Repository\MigrateCustomerRepositoryInterface;
use Illuminate\Console\Command;

class MigrateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:customer {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates data from a CSV file into a customers table';

    private $migrateCustomerRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MigrateCustomerRepositoryInterface $migrateCustomerRepository)
    {
        parent::__construct();
        $this->migrateCustomerRepository = $migrateCustomerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('file');
        print($this->migrateCustomerRepository->migrateDataToCustomers($file));
        return 0;
    }
}
