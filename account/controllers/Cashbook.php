<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cashbook extends CI_Controller {

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
        $data['activesubmenu'] = 'cashbook';
        $data['page_title'] = 'Cash Book';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $sdate = date("Y-m-d");
            $edate = date("Y-m-d");
            redirect('Cashbook/cashbookdetails/'.$sdate.'/'.$edate);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function cashbookdetails($sdate=0,$edate=0) {
        $data['activemenu'] = 'accountstatement';
        $data['activesubmenu'] = 'cashbook';
        $data['page_title'] = 'Cash Book';
        $data['baseurl'] = $this->config->item('base_url');

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):


            $ledgeropbal = $this->db->get_where('accountledger', array('id' => 1))->row();
            
            if (isset($ledgeropbal)):
                $data['debit'] = $ledgeropbal->debit;
                $data['credit'] = $ledgeropbal->credit;
            else:
                $data['debit'] = 0;
                $data['credit'] = 0;
            endif;
            if($sdate==0){
            $data['sdate'] = $this->input->post('sdate');
            $data['edate'] = $this->input->post('edate');

            $sdate = $this->input->post('sdate').' 00:00:00';
            $edate = $this->input->post('edate').' 23:59:59';
            }
            else{
                $data['sdate'] = $sdate;
                $data['edate'] = $edate;

                $sdate = $sdate.' 00:00:00';
                $edate = $edate.' 23:59:59';
            }

            $companyid = $this->session->userdata('company_id');

            $data['ledgerdata'] = $this->db->query("select * from ledgerposting where date between '$sdate' AND '$edate' AND ledgerid = '1' AND company_id = '$companyid'")->result();
            
            $tem = $this->db->query("select sum(debit)as tdebit, sum(credit) as tcredit from ledgerposting where ledgerid = '1' AND date < '$sdate' AND company_id = '$companyid'")->row();
            $opBalance= $this->db->query("Select debit,credit from accountledger where id='1'")->row();

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

            $this->load->view('cashbook', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    public function exports_data($sdate1,$edate1){
        $company_id = $this->session->userdata('company_id');
        $sdate = $sdate1.' 00:00:00';
        $edate = $edate1.' 23:59:59';

        $companyid = $this->session->userdata('company_id');

        $ledgerdata = $this->db->query("select id,date,voucherid,vouchertype,ledgerid,description,debit,credit from ledgerposting where date between '$sdate' AND '$edate' AND ledgerid = '1' AND company_id = '$companyid'")->result();
        // echo"<pre>";
        // print_r($ledgerdata);
        // echo"</pre>";
        // die();
        
        $tem = $this->db->query("select sum(debit)as tdebit, sum(credit) as tcredit from ledgerposting where ledgerid = '1' AND date < '$sdate' AND company_id = '$companyid'")->row();
        $opBalance= $this->db->query("Select debit,credit from accountledger where id='1'")->row();

        $tcredit=$tem->tcredit+ $opBalance->credit;
        $tdebit=$tem->tdebit+ $opBalance->debit;
        $opdebit=$opcredit=0;

        if(($tdebit-$tcredit)>0){
            $opdebit=$tdebit-$tcredit;
            $opcredit=0;
        }
        else{
            $opdebit=0;
            $opcredit=$tcredit-$tdebit;
        }

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Cash Book (".$sdate1." To ".$edate1.").csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Date","Voucher No","Voucher Type","Ledger Name","Description","Debit","Credit"); 
            fputcsv($handle, $header);
            $header3 = array("","","","","Opening Balance",$opdebit,$opcredit); 
            fputcsv($handle, $header3);

            foreach ($ledgerdata as $key){ 
                $str = "".$key->date;
               $arr[0]= $str; 
               $arr[1]= $key->voucherid; 
               $arr[2]= $key->vouchertype; 
               $ledgerid = $key->ledgerid;
               $ledgername = $this->db->query("select ledgername from accountledger where id=(select ledgerid from ledgerposting where voucherid='".$key->voucherid."' and vouchertype='".$key->vouchertype."' and id<>'".$key->id."' limit 1)")->row()->ledgername;
               
               $arr[3]= $ledgername; 
               $arr[4]= $key->description;   
               $arr[5]= $key->debit;   
               $arr[6]= $key->credit; 
                   
               fputcsv($handle, $arr); 
               $opdebit += $key->debit;          
               $opcredit += $key->credit;          
            }
            if($opcredit - $opdebit >=0)
            $header2 = array("","","","","Closing Balance ","0",$opcredit - $opdebit); 
            else
            $header2 = array("","","","","Closing Balance ",$opdebit-$credit,"0");
            fputcsv($handle, $header2);
            
            fclose($handle);
            exit;
    }

}

?>