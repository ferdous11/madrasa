<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bankbook extends CI_Controller {

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
        $data['activemenu'] = 'accountstatement';
        $data['activesubmenu'] = 'bankbook';
        $data['page_title'] = 'Bank Book';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $data['ledgerdata'] = array();
            $data['ledgerid'] = '0';
            $data['debit'] = '0';
            $data['credit'] = '0';
            $data['sdate'] = date("Y").'-01-01';
            $data['edate'] = date("Y-m-d");
            $data['opdebit']=0;
            $data['opcredit']=0;

            $this->load->view('bankbook', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function bankbookdetails() {
        $data['activemenu'] = 'accountstatement';
        $data['activesubmenu'] = 'bankbook';
        $data['page_title'] = 'Bank Book';
        $data['baseurl'] = $this->config->item('base_url');

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $data['ledgerid'] = $this->input->post('ledgername');
            $ledgerid = $this->input->post('ledgername');

            $data['debit'] = 0;
            $data['credit'] = 0;
           
            $ledgeropbal = $this->db->get_where('accountledger', array('id' => $ledgerid))->row();

            $data['debit'] = $ledgeropbal->debit;
            $data['credit'] = $ledgeropbal->credit;

            $data['sdate'] = $this->input->post('sdate');
            $data['edate'] = $this->input->post('edate');

            $sdate = $this->input->post('sdate') . ' 00:00:00';
            $edate = $this->input->post('edate') . ' 23:59:59';
            
            $companyid = $this->session->userdata('company_id');

            $data['ledgerdata'] = $this->db->query("select l.*,c.cheque_no from ledgerposting as l left join contravoucher as c on l.voucherid=c.id where l.date between '$sdate' AND '$edate' AND l.ledgerid = '$ledgerid' AND l.company_id = '$companyid'")->result();

            $tem = $this->db->query("select sum(debit)as tdebit, sum(credit) as tcredit from ledgerposting where ledgerid = '$ledgerid' AND date < '$sdate' AND company_id = '$companyid'")->row();
            $opBalance= $this->db->query("Select debit,credit from accountledger where id='$ledgerid'")->row();

            $tcredit=$tem->tcredit+ $opBalance->credit;
            $tdebit=$tem->tdebit+ $opBalance->debit;
            $data['opdebit']=0;
            $data['opcredit']=0;
            if(($tdebit-$tcredit)>0){
                $data['opdebit']=$tdebit-$tcredit;
                $data['opcredit']=0;
            }
            else{
                $data['opdebit']=0;
                $data['opcredit']=$tcredit-$tdebit;
            }

            $this->load->view('bankbook', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

}

?>