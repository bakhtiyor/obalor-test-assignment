<?php

namespace App\Repository\Eloquent;

use App\Models\Customer;
use App\Repository\MigrateCustomerRepositoryInterface;
use Exception;
use Validator;

class MigrateCustomerRepository implements MigrateCustomerRepositoryInterface
{

    public function migrateDataToCustomers($file)
    {
        // TODO: Implement validation of a location
        // TODO: Generate an Excel report file which contains validation errors
        try{
            if (!file_exists($file))
                throw new Exception("File $file doesn't exist. Please check and provide a valid path to your CSV file.");

            $validationRules = array(
                'email'=>'required|email:rfc,dns',
                'age'=>'required|integer|between:19,99'
            );

            if (($handle = fopen($file, "r")) !== FALSE) {
                $rowNumber = 1;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($rowNumber > 1){
                        $fullname = (isset($data[1])) ? explode(' ', $data[1]) : array();
                        $row = array(
                            'name'=>(isset($fullname[0])) ? $fullname[0] : '',
                            'surname'=>(isset($fullname[1])) ? $fullname[1] : '',
                            'email'=>(isset($data[2])) ? $data[2] : '',
                            'age'=>(isset($data[3])) ? $data[3] : '',
                            'location'=>(isset($data[4])) ? $data[4] : '',
                        );
                        $validator = Validator::make($row, $validationRules);
                        if ($validator->fails()) {
                            print_r($row);
                            $errors = $validator->errors();
                            if ($errors->has('email')) {
                                echo "incorrect email\n";
                            }else if ($errors->has('age')) {
                                echo "incorrect age\n";
                            }
                        }else{
                            Customer::updateOrCreate(
                                [
                                    'name'=>$row['name'],
                                    'surname'=>$row['surname'],
                                    'email'=>$row['email'],
                                    'age'=>$row['age'],
                                    'location'=>$row['location']
                                ],
                                []
                            );
                        }
                    }
                    $rowNumber++;
                }
                fclose($handle);
            }

            return "Migrate data from $file into a customers table from a Repository Pattern approach";
        }catch (Exception $exception){
            return $exception->getMessage();
        }
    }
}
