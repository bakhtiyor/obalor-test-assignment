<?php

namespace App\Repository;

interface MigrateCustomerRepositoryInterface
{
    public function migrateDataToCustomers($file);
}
