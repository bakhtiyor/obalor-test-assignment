<?php

namespace App\Repository\Eloquent;

use App\Models\Customer;
use App\Repository\MigrateCustomerRepositoryInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PragmaRX\Countries\Package\Countries;
use Validator;

class MigrateCustomerRepository implements MigrateCustomerRepositoryInterface
{

    public function migrateDataToCustomers($file)
    {
        try{
            if (!file_exists($file))
                throw new Exception("File $file doesn't exist. Please check and provide a valid path to your CSV file.");

            print("A migration process is started\n");
            $objPHPExcel = IOFactory::load("./public/templates/customers_template.xlsx");
            $countries = new Countries();
            $countryList = $countries->all()->pluck('iso_a3', 'name_en')->toArray();

            $validationRules = array(
                'email'=>'required|email:rfc,dns',
                'age'=>'required|integer|between:19,99'
            );

            if (($handle = fopen($file, "r")) !== FALSE) {
                $rowNumber = 1; $excelRowNum = 2; $hasValidationError = false;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    print('.');
                    if ($rowNumber > 1){
                        $fullname = (isset($data[1])) ? explode(' ', $data[1]) : array();

                        $row = array(
                            'id'=>(isset($data[0])) ? $data[0] : '',
                            'name'=>(isset($fullname[0])) ? $fullname[0] : '',
                            'surname'=>(isset($fullname[1])) ? $fullname[1] : '',
                            'email'=>(isset($data[2])) ? $data[2] : '',
                            'age'=>(isset($data[3])) ? $data[3] : '',
                            'location'=>(isset($data[4]) && !empty($data[4]) && isset($countryList[$data[4]])) ? $data[4] : 'Unknown',
                            'country_code'=>(isset($data[4]) && !empty($data[4]) && isset($countryList[$data[4]])) ? $countryList[$data[4]] : null
                        );
                        $validator = Validator::make($row, $validationRules);
                        if ($validator->fails()) {
                            $hasValidationError = true;
                            $errorField = null;
                            $errors = $validator->errors();
                            if ($errors->has('email')) {
                                $errorField = 'email';
                            }else if ($errors->has('age')) {
                                $errorField = 'age';
                            }
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excelRowNum, $row['id']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excelRowNum, $row['name']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excelRowNum, $row['email']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excelRowNum, $row['age']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excelRowNum, $row['location']);
                            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excelRowNum, $errorField);
                            $excelRowNum++;
                        }else{
                            Customer::updateOrCreate(
                                [
                                    'name'=>$row['name'],
                                    'surname'=>$row['surname'],
                                    'email'=>$row['email'],
                                    'age'=>$row['age'],
                                    'location'=>$row['location'],
                                    'country_code'=>$row['country_code']
                                ],
                                []
                            );
                        }
                    }
                    $rowNumber++;
                }

                fclose($handle);

                if ($hasValidationError){
                    $objWriter = IOFactory::createWriter($objPHPExcel, 'Xlsx');
                    $filename = "customer_migration_errors_".date('Ymd_His').".xlsx";
                    $filepath = "./public/migration-reports/$filename";
                    $objWriter->save($filepath);

                    return "\nMigration of data from $file into a customers table is completed and errors are reported in public/migration-reports/$filename";
                }else{
                    return "\nMigration of data from $file into a customers table is completed successully";
                }
            }else{
                throw new Exception("Error occured when opening a file");
            }
        }catch (Exception $exception){
            return $exception->getMessage();
        }
    }
}
