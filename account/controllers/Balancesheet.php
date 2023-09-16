<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Balancesheet extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('form', 'file');
        $this->load->helper('url');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->load->helper('common');
        $this->load->helper('csv');
        $this->load->library('image_lib');
        $this->load->library('form_validation');
    }

    function index() {

        $data['baseurl'] = $this->config->item('base_url');
        $this->load->view('404/404',$data);
        return 0;
        
        $data['title'] = "Balance Sheet";
        $data['activemenu'] = "accountstatement";
        $data['activesubmenu'] = "balancesheet";
        $data['baseurl'] = $this->config->item('base_url');
        $company_data = $this->session->userdata('companyid');

        $initialdate = date("Y") . '01-01 00:00:00';
        $date_to = date("Y-m-d H:i:s");
        
        $companyid = $this->session->userdata('company_id');
        $closingstockvalue = $this->db->query("select sum(available_quantity) as totalfreeqty from purchase where date between '$initialdate' AND '$date_to' AND company_id = '$companyid'")->row()->totalfreeqty;
        $openingstockvalue = $this->db->query("select sum(quantity) as totalqty from purchase where date between '$initialdate' AND '$date_to' AND company_id = '$companyid'")->row()->totalqty;

        $data['openingstock'] = $openingstockvalue;
        $data['closingstock'] = $closingstockvalue;


        $data['liability'] = 0;
        $data['currentliability'] = 0;
        $data['supplier'] = 0;

        $data['cashinhand'] = 0;
        $data['bankaccount'] = 0;
        $data['chirtfund'] = 0;
        $data['customertotal'] = 0;
        $data['fixedasset'] = 0;


        $data['date_from'] = date("Y").'-01-01';
        $data['date_to'] = date("Y-m-d");
        $this->load->view('balancesheet', $data);
    }

    public function getassetbalance($id, $sdate, $edate) {
        $ledgerID_asset = '';
        $ledgerassetObj = $this->db->get_where('accountledger', array('accountgroupid' => $id))->result();
        if (sizeof($ledgerassetObj) > 0):
            foreach ($ledgerassetObj as $ledgeridList):
                $ledgerID_asset = $ledgerID_asset . $ledgeridList->id . ',';
            endforeach;
        else:
            $ledgerID_asset = 0;
        endif;
        $final_ledger_id_list1 = trim(rtrim($ledgerID_asset, ','));

        $companyid = $this->session->userdata('company_id');
        $sales_account = $this->db->query("select sum(debit) - sum(credit) as totalbankaccount from ledgerposting where ledgerid IN ( $final_ledger_id_list1 ) AND date between '$sdate' AND '$edate' AND company_id = '$companyid'")->row();
        if (sizeof($sales_account) > 0):
            return $sales_account->totalbankaccount;
        else:
            return 0;
        endif;
    }

    function viewbalancesheet() {
        $data['title'] = "Profit And Loss Analysis";
        $data['activemenu'] = "accountstatement";
        $data['activesubmenu'] = "balancesheet";
        $data['baseurl'] = $this->config->item('base_url');
        $company_data = $this->session->userdata('companyid');



        $data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');

        $sdate = $this->input->post('date_from') . ' 00:00:00';
        $edate = $this->input->post('date_to') . ' 23:59:59';

        $company_id = $this->session->userdata('company_id');
        $closingstockvalue = $this->db->query("select sum(available_quantity) as totalfreeqty from purchase where date between '$sdate' AND '$edate' AND company_id = '$company_id'")->row()->totalfreeqty;
        $openingstockvalue = $this->db->query("select sum(quantity) as totalqty from purchase where date between '$sdate' AND '$edate' AND company_id = '$company_id'")->row()->totalqty;

        $data['openingstock'] = $openingstockvalue;
        $data['closingstock'] = $closingstockvalue;

        //current liability 12
        $data['currentliability'] = $this->getassetbalance(12, $sdate, $edate);

        //liability 2
        $data['liability'] = $this->getassetbalance(2, $sdate, $edate);

        //supplier 24
        $data['supplier'] = $this->getassetbalance(24, $sdate, $edate);

        //bank acoount 9
        $data['bankaccount'] = $this->getassetbalance(9, $sdate, $edate);
        //cash in hand acoount 11
        $data['cashinhand'] = $this->getassetbalance(11, $sdate, $edate);
        //fund account 29
        $data['chirtfund'] = $this->getassetbalance(29, $sdate, $edate);
        //customer total
        $data['customertotal'] = $this->getassetbalance(25, $sdate, $edate);
        //fixed asset 16
        $data['fixedasset'] = $this->getassetbalance(16, $sdate, $edate);
        $this->load->view('balancesheet', $data);
    }

}

?>