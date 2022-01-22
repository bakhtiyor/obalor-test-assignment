<?php

namespace App\Console\Commands;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('file');
        print("Migrate data from $file into a customers table");
        return 0;
    }
}
