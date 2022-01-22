<?php

namespace App\Repository\Eloquent;

use App\Repository\MigrateCustomerRepositoryInterface;

class MigrateCustomerRepository implements MigrateCustomerRepositoryInterface
{

    public function migrateDataToCustomers($file)
    {
        // TODO: Implement migrateDataToCustomers() method.
        return "Migrate data from $file into a customers table from a Repository Pattern approach";
    }
}
