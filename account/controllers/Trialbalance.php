<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Trialbalance extends CI_Controller {

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

    public function index() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'accountstatement';
            $data['activesubmenu'] = 'trialbalance';
            $data['page_title'] = 'Trial balance';
            $data['baseurl'] = $this->config->item('base_url');
            $data['sdate'] = date('Y') . '-01-01';
            $data['edate'] = date('Y-m-d');
            $sdate = date('Y') . '-01-01 00:00:00';
            $edate = date('Y-m-d') . ' 23:59:59';

            $companyid = $this->session->userdata('company_id');

            $data['ledgerdata'] = $this->db->query("select sum(debit) as totaldebit,sum(credit) as totalcredit,ledgerid,date,voucherid from ledgerposting where date between '$sdate' AND '$edate' AND company_id = '$companyid' group by ledgerid")->result();

            $this->load->view('trialbalance', $data);
        else:
            redirect('home');
        endif;
    }

    function viewtrialbalance() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'accountstatement';
            $data['activesubmenu'] = 'trialbalance';
            $data['page_title'] = 'Trial balance';
            $data['baseurl'] = $this->config->item('base_url');
            $data['sdate'] = $this->input->post('sdate');
            $data['edate'] = $this->input->post('edate');
            $sdate = $this->input->post('sdate') . ' 00:00:00';
            $edate = $this->input->post('edate') . ' 23:59:59';
            $companyid = $this->session->userdata('company_id');
            $data['ledgerdata'] = $this->db->query("select sum(debit) as totaldebit,sum(credit) as totalcredit,ledgerid,date,voucherid from ledgerposting where date between '$sdate' AND '$edate' AND company_id = '$companyid' group by ledgerid")->result();
            $this->load->view('trialbalance', $data);
        else:
            redirect('home');
        endif;
    }

}

?>