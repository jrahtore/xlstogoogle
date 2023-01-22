<?php
/**
 * ImportMysql Class
 *
 * @category  Database Access
 * @package   ImportMysql
 * @author    Jitendra Singh Rathroe <jitendra.r3@gmail.com>
 * @copyright Copyright (c) 2023-2027
 * @license   GNU Public License
 * @version   1.0.0
 */

class ImportMysql {
    /**
     * Database Connection
     *
     * @object MysqliDb
     */
    public $db;
    /**
     * Import File Type Config Arr
     *
     * @array MysqliDb
     */
    public $importFileTypeConfigArr;
    /**
     * @param MysqliDb $db
     */
    public function __construct(MysqliDb $db,array $importFileTypeConfigArr)
    {
        $this->db = $db;
        $this->importFileTypeConfigArr = $importFileTypeConfigArr;
    }

    /**
     * A method to read file
     *
     * @param string $file_name
     * @param Int $tot
     * @param String $type
     *
     * @return array
     */
    function readFile(String $xls_name,String $type) : array
    {
        if($_FILES[$xls_name]["name"] != '')
        {
            $allowed_extension = array('xls', 'csv', 'xlsx');
            $file_array = explode(".", $_FILES[$xls_name]["name"]);
            $file_extension = end($file_array);

            if(in_array($file_extension, $allowed_extension))
            {
                $file_name = time() . '.' . $file_extension;
                if(move_uploaded_file($_FILES[$xls_name]['tmp_name'], $file_name))
                {
                    $sheetsArr = $this->readAllSheets($file_name);
                    
                    return $sheetsArr;  
                }
                else
                {
                    return [];
                }    
            }
            else
            {
                return [];
            }
        }
        else
        {
            return [];
        }
    }
    /**
     * A method to read data from sheet
     *
     * @param mixed $spreadsheet
     * @param Int $tot
     *
     * @return array
     */
    public function readAllSheets($file_name) : array
    {

        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
        $reader    = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
        $reader->setReadDataOnly(TRUE); $reader->setReadEmptyCells(FALSE);
        $spreadsheet = $reader->load($file_name);
        unlink($file_name);
        $sheetsArr = [];
        $tot = count($this->importFileTypeConfigArr);
        $i=0;
        foreach($this->importFileTypeConfigArr as $d)
        {
            $worksheet = $spreadsheet->getSheet($i);
            $rows = [];
            $j=0;
            foreach ($worksheet->getRowIterator() AS $row) 
            {
                
                if($j==0||$j==1)
                {
                    $j++;

                    continue;
                }
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $k=0;
                foreach ($cellIterator as $cell) {
                    if($k<count($d)-1)
                    {
                        $cells[$d[$k]] = $cell->getValue();
                    }
                    $k++;
                }
                $cells = array_merge($cells,['tp'=>$d['tp']]);
            
                $this->db->insert('top_500',$cells);

                $rows[] = $cells;
                $j++;
            }
            if(!empty($rows)){
                $sheetsArr['sheet_'.$i] = $rows;
            }
            $i++;
        }
        return $sheetsArr;
    }
    public function saveToDb() :void
    {
        $sql = "INSERT INTO `top_500` ( `tp`, `prod_name`, `strength`, `pkg_size`, `form`, `mfr`, `stp`, `low_sold`, `avg_sold`, `high_sold`, `sold_variance`, `best_price_today`, `best_price_exp_date`, `best_price_qty_available`, `avg_trxade_price_today`, `num_results`, `rank`, `manufacturer_name`, `percent_unit_sold`, `percentof_total_unit_sold`, `by_sales_or_unit`) VALUES ('sheet_name2', 'pname', 'str', 'pkg', 'form', 'mfr', 'stp', 'low_sold', 'avg_sold', 'high_sold', 'sold_var', 'best_price_to', 'best_price_exp', 'best_price_qty', 'avg_tradeprice_today', 'num_result', 'rank', 'manufacture_name', 'percent_unit_sold', 'percentof_total_unit_sold', 'by_sales_or_unit');
        ";
        $this->db->rawQuery($sql);
    }
}