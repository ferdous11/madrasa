<?php
/**
 * Description of Export Controller
 *
 * @author TechArise Team
 *
 * @email  info@techarise.com
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Exports extends CI_Controller {
	// construct
    public function __construct() {
        parent::__construct();
		// load model
        $this->load->model('Export', 'export');
    }    
	 // export xlsx|xls file
    public function index() {
        $data['page'] = 'export-excel';
        $data['title'] = 'Export Excel data | TechArise';
        $data['mobiledata'] = $this->export->mobileList();
		// load view file for output
        $this->load->view('header');
        $this->load->view('exports/exports', $data);
        $this->load->view('footer');
    }
	// create xlsx
    public function createXLS() {
		// create file name
        $fileName = 'mobile-'.time().'.xlsx';  
		// load excel library
        $this->load->library('excel');
        $mobiledata = $this->export->mobileList();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Model No.');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Mobile Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Price');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Company');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Category');       
        // set Row
        $rowCount = 2;
        foreach ($mobiledata as $val) 
        {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val['model_no']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val['mobile_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val['price']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val['company']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val['mobile_category']);
            $rowCount++;
        }
 
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($fileName);
		// download file
        header("Content-Type: application/vnd.ms-excel");
         redirect(site_url().$fileName);              
    }
    
}
?>