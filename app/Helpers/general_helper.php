<?php

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use App\Exceptions\Handler;
use App\Model\Vendors;
use App\Model\PtpSale;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

if (!function_exists('generatePassword')) {
    function generatePassword()
    {
        $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
        return substr($random, 0, 10);
    }
}

if (!function_exists('setBrandSession')) {
    function setBrandSession($id, $name)
    {
        Session()->put('brand_id', $id);
        Session()->put('brand_name', $name);
    }
}
if (!function_exists('checkDateRange')) {
    function checkDateRange($rangeType, $startDate, $endDate)
    {
        switch ($rangeType) {
            case 2:
                $valid_start = date('Y-m-d', strtotime('last Sunday', strtotime($startDate)));
                $valid_end = date('Y-m-d', strtotime('next Saturday', strtotime($startDate)));
                $day = strtolower(date('l', strtotime($startDate)));
                if ($day == 'sunday') {

                    $valid_start = date('Y-m-d', strtotime($startDate));
                }
                return $valid_start == $startDate && $valid_end == $endDate;
            case 3:
                $valid_start = date('Y-m-d', strtotime('first day of this month', strtotime($startDate)));
                $valid_end = date('Y-m-d', strtotime('last day of this month', strtotime($startDate)));
                return $valid_start == $startDate && $valid_end == $endDate;
            case 4:
                $valid_start = date('Y-m-d',  strtotime('first day of January', strtotime($startDate)));
                $valid_end = date('Y-m-d', strtotime('last day of December', strtotime($startDate)));
                return $valid_start == $startDate && $valid_end == $endDate;
            case 5:
                $valid_start = date('Y-m-d', strtotime('last Wednesday', strtotime($startDate)));
                $valid_end = date('Y-m-d', strtotime('next Tuesday', strtotime($startDate)));
                $day = strtolower(date('l', strtotime($startDate)));
                if ($day == 'wednesday') {

                    $valid_start = date('Y-m-d', strtotime($startDate));
                }
                return $valid_start == $startDate && $valid_end == $endDate;
            default:
                return true;
        }
    }
}
if (!function_exists('checkDateBussinessRange')) {
    function checkDateBussinessRange($rangeType, $startDate, $endDate)
    {
        switch ($rangeType) {
            case 1:
                $day = strtolower(date('l', strtotime($startDate)));
                $day_end = strtolower(date('l', strtotime($endDate)));
                if ($day == 'sunday') {

                    $valid_start = date('Y-m-d', strtotime($startDate));
                } else {
                    $valid_start = date('Y-m-d', strtotime('last Sunday', strtotime($startDate)));
                }
                if ($day_end == 'saturday') {

                    $valid_end = date('Y-m-d', strtotime($endDate));
                } else {
                    $valid_end = date('Y-m-d', strtotime('next Saturday', strtotime($endDate)));
                }
                return $valid_start == $startDate && $valid_end == $endDate;
            case 2:
                $valid_start = date('Y-m-d', strtotime('first day of this month', strtotime($startDate)));
                $valid_end = date('Y-m-d', strtotime('last day of this month', strtotime($endDate)));
                return $valid_start == $startDate && $valid_end == $endDate;
            case 3:
                $valid_start = date('Y-m-d',  strtotime('first day of January', strtotime($startDate)));
                $valid_end = date('Y-m-d', strtotime('last day of December', strtotime($startDate)));
                return $valid_start == $startDate && $valid_end == $endDate;
            case 5:
                $valid_start = date('Y-m-d', strtotime('last Wednesday', strtotime($startDate)));
                $valid_end = date('Y-m-d', strtotime('next Tuesday', strtotime($startDate)));
                $day = strtolower(date('l', strtotime($startDate)));
                if ($day == 'wednesday') {

                    $valid_start = date('Y-m-d', strtotime($startDate));
                }
                return $valid_start == $startDate && $valid_end == $endDate;
            default:
                return true;
        }
    }
}
if (!function_exists('checkOptionPermission')) {
    function checkOptionPermission($elements, $permission)
    {
        $status = false;
        foreach ($elements as $element) {
            $roles = App\Model\Role::findOrFail(Illuminate\Support\Facades\Auth::user()->roles()->get()->first()->role_id);
            $authorization = $roles->authorization()->get();
            if ($authorization->where('fk_module_id', $element)->where('fk_permission_id', $permission)->first()) {
                $status = true;
            }
        }
        return $status;
    }
}

if (!function_exists('checkUserPermission')) {
    function checkUserPermission($elements, $permission)
    {
        $status = false;
        foreach ($elements as $element) {
            $user = App\Model\User::findOrFail(Illuminate\Support\Facades\Auth::user()->user_id);
            $authorization = $user->authorization()->get();
            if ($authorization->where('fk_module_id', $element)->where('fk_permission_id', $permission)->first()) {
                $status = true;
            }
        }
        return $status;
    }
}

// Excel function to read file
if (!function_exists('getDataFromExcelFile')) {
    /**
     * @param $fileName
     * @return array
     */
    function getDataFromExcelFile($request, $dirToUploadFile)
    {
        $uploadPath = public_path('uploads/' . $dirToUploadFile . '/');
        $fileName = $request->getClientOriginalName();
        $uploadFilePathToRead = $uploadPath . $fileName;

        $request->move($uploadPath, $fileName);

        try {
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($uploadFilePathToRead);
            $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPhpSpreadSheet = $objReader->load($uploadFilePathToRead);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($uploadFilePathToRead, PATHINFO_BASENAME)
                . '": ' . $e->getMessage());
        }

        $sheet = $objPhpSpreadSheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $keys = array();
        $results = array();
        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, false, false);
            if ($row === 1) {
                foreach ($rowData[0] as $value) {
                    if (strpos($value, "Viewing") === 0) {
                        $Datainfo = explode("=", $value);

                        $date = str_replace("[", "", $Datainfo[1]);
                        $date = str_replace("]", "", $date);
                        $date = str_replace(" ", "", $date);
                        $date = explode("-", $date);
                        //for start date
                        if (strpos($date[0], "/") !== false) {
                            $date_array = explode("/", $date[0]);
                            $year = ($date_array[2] % 2000) + 2000;
                            // Test if string contains the word CA
                            if (strpos($fileName, '_CA') !== false) {
                                $create_date =  $year . "-" . $date_array[1] . "-" . $date_array[0];
                            } else {
                                if ($date_array[0] > 12) {
                                    $create_date =  $year . "-" . $date_array[1] . "-" . $date_array[0];
                                } else {
                                    $create_date =  $year . "-" . $date_array[0] . "-" . $date_array[1];
                                }
                            }
                            $ans['startdate'] = date("Y-m-d", strtotime($create_date));
                        } else {
                            $ans['startdate'] = date('Y-m-d', strtotime($date[0]));
                        }
                        if ($dirToUploadFile == "monthlyinventory") { //only work for inventory monthly report
                            if (strpos($date[1], "/") !== false) {
                                $date_array1 = explode("/", $date[1]);
                                $year = ($date_array1[2] % 2000) + 2000;
                                // Test if string contains the word CA
                                if (strpos($fileName, '_CA') !== false) {
                                    $create_date =  $year . "-" . $date_array1[1] . "-" . $date_array1[0];
                                } else {
                                    $create_date =  $year . "-" . $date_array1[0] . "-" . $date_array1[1];
                                }
                                $ans['enddate'] = date("Y-m-d", strtotime($create_date));
                            } else {
                                $ans['enddate'] = date('Y-m-d', strtotime($date[1]));
                            }
                        }
                    }
                }
            } elseif ($row === 2) {
                $keys = $rowData[0];
            } else {
                $record = array();
                foreach ($rowData[0] as $pos => $value) {
                    $record[RemOf(
                        Redundant(
                            RedundantAll(SpaceToUnderscore(PercentageToNull(DashToNull(RemoveBrakets($keys)))))
                        )
                    )[$pos]] = $value;
                    //$record[$keys[$pos]] = $value;
                }

                $results[] = $record;
            }
        }
        if (File::exists($uploadFilePathToRead)) {
            File::delete($uploadFilePathToRead);
        }
        unset($objPhpSpreadSheet);
        unset($uploadFilePathToRead);
        if (!empty($ans))
            return array($ans, $results);
        else {
            return $results;
        }
        // return $results;
    }
}
// Excel function to read file
if (!function_exists('getCategoryDataFromExcelFile')) {
    /**
     * @param $fileName
     * @return array
     */
    function getCategoryDataFromExcelFile($request, $dirToUploadFile)
    {
        $uploadPath = public_path('uploads/' . $dirToUploadFile . '/');
        // dd($dirToUploadFile);
        $fileName = $request->getClientOriginalName();
        $uploadFilePathToRead = $uploadPath . $fileName;

        $request->move($uploadPath, $fileName);

        try {
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($uploadFilePathToRead);
            $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPhpSpreadSheet = $objReader->load($uploadFilePathToRead);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($uploadFilePathToRead, PATHINFO_BASENAME)
                . '": ' . $e->getMessage());
        }

        $sheet = $objPhpSpreadSheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $keys = array();
        $results = array();

        for ($row = 1; $row <= $highestRow; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

            if ($row === 1) {
                $keys = $rowData[0];
            } else {
                $record = array();
                foreach ($rowData[0] as $pos => $value) {
                    $record[SpaceToUnderscore($keys)[$pos]] = $value;
                }

                $results[] = $record;
            }
        }
        if (File::exists($uploadFilePathToRead)) {
            File::delete($uploadFilePathToRead);
        }
        unset($objPhpSpreadSheet);
        unset($uploadFilePathToRead);
        return $results;
    }
}
// Excel function to read file for ptp
if (!function_exists('getPtpDataFromExcelFile')) {
    /**
     * @param $fileName
     * @return array
     */
    function getPtpDataFromExcelFile($request, $dirToUploadFile)
    {
        $uploadPath = public_path('uploads/' . $dirToUploadFile . '/');
        $fileName = $request->getClientOriginalName();
        $uploadFilePathToRead = $uploadPath . $fileName;
        $request->move($uploadPath, $fileName);

        try {

            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($uploadFilePathToRead);
            $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPhpSpreadSheet = $objReader->load($uploadFilePathToRead);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($uploadFilePathToRead, PATHINFO_BASENAME)
                . '": ' . $e->getMessage());
        }
        $sheet = $objPhpSpreadSheet->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $keys = array();
        $check = 0;
        $results = array();
        $count = 0;
        $record = array();
        $shipCogs = array();
        $reciptShip = array();
        $reciptDollar = array();
        $shippedUnit = array();
        $metrics = "";
        $category = "";
        $Vendor_name = NULL;
        for ($row = 1; $row <= $highestRow + 1; $row++) {
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

            if ($row === 1) {
                $keys = $rowData[0];
                $check = count($keys) - 3;
                for ($i = 0; $i < $check + 3; $i++) {

                    if ($i >= 3) {
                        $data = explode("-", $keys[$i]);
                        if (strlen($data[0]) > 3) {
                            if (File::exists($uploadFilePathToRead)) {
                                File::delete($uploadFilePathToRead);
                            }
                            unset($objPhpSpreadSheet);
                            unset($uploadFilePathToRead);
                            return false;
                        }
                        if (strlen($data[1]) > 2) {
                            if (File::exists($uploadFilePathToRead)) {
                                File::delete($uploadFilePathToRead);
                            }
                            unset($objPhpSpreadSheet);
                            unset($uploadFilePathToRead);
                            return false;
                        }
                    }
                }
                if (strtolower($keys[0]) == 'vendor'  && strtolower($keys[1]) === 'category'  && strtolower($keys[2]) == 'metric') {
                    continue;
                } else {
                    if (File::exists($uploadFilePathToRead)) {
                        File::delete($uploadFilePathToRead);
                    }
                    unset($objPhpSpreadSheet);
                    unset($uploadFilePathToRead);
                    return false;
                }
            } else {
                $count++;
                if ($count == 5) {
                    if ($row == 2) {
                        if (empty($Vendor_name) || is_null($Vendor_name) || empty($category) || is_null($category) || empty($shipCogs) || is_null($shipCogs)) {
                            if (File::exists($uploadFilePathToRead)) {
                                File::delete($uploadFilePathToRead);
                            }
                            unset($objPhpSpreadSheet);
                            unset($uploadFilePathToRead);
                            return false;
                        }
                    } else {
                        if (empty($Vendor_name) || is_null($Vendor_name) || empty($category) || is_null($category) || empty($shipCogs) || is_null($shipCogs)) {

                            // if (File::exists($uploadFilePathToRead)) {
                            //     File::delete($uploadFilePathToRead);
                            // }
                            // unset($objPhpSpreadSheet);
                            // unset($uploadFilePathToRead);
                            // return false;
                            continue;
                        }
                    }
                    $d = 3;
                    for ($i = 0; $i < $check; $i++) {
                        $data = explode("-", $keys[$d]);
                        $date1 = $data[0] . ' 01 ' . $data[1];
                        $z = date('Y-m-d', strtotime($date1));
                        $d++;
                        $dbData['fk_vendor_name'] = $Vendor_name;
                        $dbData['category_name'] = $category;
                        $dbData['shipped_cogs'] = $shipCogs[$i];
                        $dbData['shipped_units'] = $shippedUnit[$i];
                        $dbData['receipt_dollar'] = $reciptDollar[$i];
                        $dbData['receipt_shipped_units'] = $reciptShip[$i];
                        $dbData['ptp_date'] = $z;
                        $data = PtpSale::insertPtp($dbData);
                        //
                    }

                    $shipCogs = array();
                    $reciptShip = array();
                    $reciptDollar = array();
                    $shippedUnit = array();
                    $metrics = "";
                    $count = 1;
                    $Vendor_name = NULL;
                    $category = "";
                    $key = "";
                }
                $cat_count = 0;
                $q = 0;
                foreach ($rowData[0] as $pos => $value) {
                    if ($cat_count === 0) {
                        $Vendor_name = $value;
                    }

                    if ($category === "" && $cat_count === 1) {
                        $category = $value;
                        //continue;
                    }
                    $cat_count++;
                    if ($value === "Shipped COGS") {
                        $metrics = $value;
                        continue;
                    }
                    if ($value === "Receipt Shipped Units") {
                        $metrics = $value;
                        continue;
                    }
                    if ($value === "Receipt Dollars") {
                        $metrics = $value;
                        continue;
                    }
                    if ($value === "Shipped Units") {
                        $metrics = $value;
                        continue;
                    }
                    if ($metrics === "Shipped COGS") {
                        $shipCogs[sizeof($shipCogs)] = $value;
                    }
                    if ($metrics === "Receipt Shipped Units") {
                        $reciptShip[sizeof($reciptShip)] = $value;
                    }
                    if ($metrics === "Receipt Dollars") {
                        $reciptDollar[sizeof($reciptDollar)] = $value;
                    }
                    if ($metrics === "Shipped Units") {
                        $shippedUnit[sizeof($shippedUnit)] = $value;
                    }
                }
            }
        }
        if (File::exists($uploadFilePathToRead)) {
            File::delete($uploadFilePathToRead);
        }
        unset($objPhpSpreadSheet);
        unset($uploadFilePathToRead);
        return true;
    }
}
if (!function_exists('RemoveComma')) {
    function RemoveComma($value)
    {
        $result = str_replace(',', '', $value);
        return $result;
    }
}

if (!function_exists('RemoveVariations')) {
    function RemoveVariations($value)
    {

        $result = str_replace('%', '', $value);
        if ($value == '—') {
            $result = str_replace('—', '0', $value);
        }
        return $result;
    }
}
if (!function_exists('DashToNull')) {
    function DashToNull($value)
    {
        $result = str_replace('-', '', $value);
        return $result;
    }
}
if (!function_exists('PercentageToNull')) {
    function PercentageToNull($value)
    {
        $result = str_replace('%', '', $value);
        return $result;
    }
}
if (!function_exists('SlashToUnderscore')) {
    function SlashToUnderscore($value)
    {
        $result = str_replace('/', '_', $value);
        return $result;
    }
}
if (!function_exists('Redundant')) {
    function Redundant($value)
    {
        $result = str_replace('__', '_', $value);
        return $result;
    }
}
if (!function_exists('RedundantAll')) {
    function RedundantAll($value)
    {
        $result = str_replace('___', '_', $value);

        return $result;
    }
}
if (!function_exists('RemOf')) {
    function RemOf($value)
    {
        $result = str_replace('of', 'percentage', $value);

        return $result;
    }
}
if (!function_exists('SpaceToUnderscore')) {
    /**
     * This function is used to Remove Space and convert lower case
     * @param $value
     * @return array|mixed
     */
    function SpaceToUnderscore($value)
    {
        $result = str_replace(' ', '_', $value);
        $result = array_map('strtolower', $result);
        return $result;
    }
}
if (!function_exists('RemoveStricSign')) {
    /**
     * This function is used to Remove Space and convert lower case
     * @param $value
     * @return array|mixed
     */
    function RemoveStricSign($value)
    {
        $result = str_replace('*', '', $value);
        $result = array_map('strtolower', $result);
        return $result;
    }
}
if (!function_exists('RemoveBrakets')) {
    /**
     * This function is used to Remove Space and convert lower case
     * @param $value
     * @return array|mixed
     */
    function RemoveBrakets($value)
    {
        $result = str_replace('(', '', $value);
        $result = str_replace(')', '', $result);
        $result = array_map('strtolower', $result);
        return $result;
    }
}
if (!function_exists('RemoveDollarSign')) {
    /**
     * This function is used to Remove Dollar Sign
     * @param $value
     * @return mixed
     */
    function RemoveDollarSign($value)
    {
        $result = str_replace('$', ' ', $value);
        return $result;
    }
}
if (!function_exists('removeDollarCommaSpace')) {
    /**
     * This function is used to Remove Dollar Sign
     * @param $value
     * @return mixed
     */
    function removeDollarCommaSpace($value)
    {
        $result = str_replace('$', '', $value);
        $result = str_replace(',', '', $value);
        $result = str_replace(' ', '', $value);
        return $result;
    }
}
if (!function_exists('DateConversionExcelFile')) {
    /**
     * This function is used to Convert Excel Date into Unix Date
     * @param $value
     * @return false|string|null
     */
    function DateConversionExcelFile($value)
    {
        if (!empty($value)) {
            return gmdate("Y-m-d", ($value - 25569) * 86400);
        }
        return NULL;
    }
}
if (!function_exists('DateConversion')) {
    /**
     * @param $value
     * @return false|null|string
     */
    function DateConversion($value)
    {
        if (!empty($value)) {
            if (is_numeric($value)) {
                $result = gmdate("Y-m-d", ($value - 25569) * 86400);
            } else {
                $result = date('Y-m-d', strtotime($value));
            }
        } else {
            $result = NULL;
        }
        if ($result == '1970-01-01') {
            $date_array = explode("/",  $value);
            $year = ($date_array[2] % 2000) + 2000;
            $create_date =  $year . "-" . $date_array[1] . "-" . $date_array[0];
            $result = $create_date;
        }
        return $result;
    }
}
if (!function_exists('setMemoryLimitAndExeTime')) {
    function setMemoryLimitAndExeTime()
    {
        ini_set('max_execution_time', 0); //set max execution time
        ini_set("memory_limit", "-1"); // set memory limit
    }
}
//Po functions start here
if (!function_exists('uploadCsvFile')) {

    /**
     * @param $value
     */
    function uploadCsvFile($request, $dirToUploadFile)
    {
        $uploadPath = public_path('uploads/' . $dirToUploadFile . '/');
        $fileName = $request->getClientOriginalName();
        $uploadFilePathToRead = $uploadPath . $fileName;

        $request->move($uploadPath, $fileName);

        if (File::exists($uploadFilePathToRead)) {

            return $uploadFilePathToRead;
        }

        return false;
    }
    // here we use box/spout library for large files in PO
    if (!function_exists('readExcelFileSpout')) {

        /**
         * @param $value
         */
        function readExcelFileSpout($uploadFilePathToRead)
        {
            $reader = ReaderEntityFactory::createReaderFromFile($uploadFilePathToRead);
            $reader->open($uploadFilePathToRead);
            $count = 0;
            $headings = array();
            $value = array();
            $result = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    if ($rowNumber == 1) {
                        $headings = $row->toArray();
                    } elseif ($rowNumber > 1) {
                        $value = $row->toArray();
                        $value = array_combine(spaceToUnderscore($headings), $value);
                    }
                    array_push($result, $value);
                }
            }
            $reader->close();
            if (File::exists($uploadFilePathToRead)) {
                File::delete($uploadFilePathToRead);
            }
            return $result;
        }
    }
    if (!function_exists('paginateArray')) {

        /**
         * @param $value
         */
        function paginateArray($data, $perPage = 15)
        {
            $page = Illuminate\Pagination\Paginator::resolveCurrentPage();
            $total = count($data);
            $results = array_slice($data, ($page - 1) * $perPage, $perPage);

            return new Illuminate\Pagination\LengthAwarePaginator($results, $total, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
            ]);
        }
    }
}
