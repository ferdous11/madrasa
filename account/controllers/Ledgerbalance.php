<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ledgerbalance extends CI_Controller {

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
            $data['activesubmenu'] = 'ledgerbalance';
            $data['page_title'] = 'Ledger balance';
            $data['baseurl'] = $this->config->item('base_url');           
            $data['sdate'] = date('Y-m-d', strtotime("-90 days"));

            $data['edate'] = date('Y-m-d');
            $comid = $this->session->userdata('company_id');
            $data['ledgername'] = $this->db->query("select l.*,d.name as district_name,ag.name as groupname from accountledger as l left join districts as d on l.district=d.id left join accountgroup as ag on l.accountgroupid=ag.id where l.company_id = '$comid' and l.status<>0")->result();
            $data['debit'] = 0;
            $data['credit'] = 0;
            $data['ledgerid'] = 0;
            $data['ledgerdata'] = array(); //$this->db->query("select sum(debit) as totaldebit,sum(credit) as totalcredit,ledgerid,date,voucherid from ledgerposting where date between '$sdate' AND '$edate' group by ledgerid")->result();
            $this->load->view('ledgerbalance', $data);
        else:
            redirect('home');
        endif;
    }

    function viewledgerbalance() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'accountstatement';
            $data['activesubmenu'] = 'ledgerbalance';
            $data['page_title'] = 'Ledger balance';
            $data['baseurl'] = $this->config->item('base_url');
            
            $data['sdate'] = $this->input->get('sdate');
            $data['edate'] = $this->input->get('edate');

            $ledger = $this->input->get('ledgername');
            
            $data['ledgerid'] = $ledger;

            $ledgeopeningbal = $this->db->query("select l.*,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id='$ledger'")->row();
            
            $data['selectledger'] = $ledgeopeningbal;
            $data['debit'] = $ledgeopeningbal->debit;
            $data['credit'] = $ledgeopeningbal->credit;

             // var_dump($data['debit']);
             // var_dump($data['credit']);

            $sdate = $this->input->get('sdate') . ' 00:00:00';
            $edate = $this->input->get('edate') . ' 23:59:59';

            $companyid = $this->session->userdata('company_id');
            
            $data['ledgername'] = $this->db->query("select l.*,d.name as district_name,ag.name as groupname from accountledger as l left join districts as d on l.district=d.id left join accountgroup as ag on l.accountgroupid=ag.id where l.company_id = '$companyid' and status<>0")->result();
            
            

            $data['ledgerdata'] = $this->db->query("select * from ledgerposting where ledgerid = '$ledger' AND date between '$sdate' AND '$edate' AND company_id = '$companyid'")->result();
            $data['closingLedgerData'] = $this->db->query("select * from ledgerposting where ledgerid = '$ledger' AND date < '$sdate' AND company_id = '$companyid'")->result();
            
           
           
            // echo"<pre>";
            // print_r($data['closingLedgerData']);
            // echo"</pre>";
            // die();


            $this->load->view('ledgerbalance', $data);
        else:
            redirect('home');
        endif;
    }

    public function exports_data($ledger_id,$sdate1,$edate1){
        $company_id = $this->session->userdata('company_id');
        $sdate = $sdate1.' 00:00:00';
        $edate = $edate1.' 23:59:59';

        $companyid = $this->session->userdata('company_id');

        $ledgerdata = $this->db->query("select id,date,voucherid,vouchertype,ledgerid,description,debit,credit from ledgerposting where date between '$sdate' AND '$edate' AND ledgerid = '$ledger_id' AND company_id = '$companyid'")->result();
        // echo"<pre>";
        // print_r($ledgerdata);
        // echo"</pre>";
        // die();
        
        $tem = $this->db->query("select sum(debit)as tdebit, sum(credit) as tcredit from ledgerposting where ledgerid = '$ledger_id' AND date < '$sdate' AND company_id = '$companyid'")->row();
        $opBalance= $this->db->query("Select ledgername,debit,credit from accountledger where id='$ledger_id'")->row();

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
        header("Content-Disposition: attachment; filename=\"Ledger Balance For ".$opBalance->ledgername." (".$sdate1." To ".$edate1.").csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $header = array("Date","Voucher No","Voucher Type","Details","Debit","Credit","Balance"); 
        fputcsv($handle, $header);
        $header3 = array("","","","Opening Balance",$opdebit,$opcredit,$opdebit-$opcredit); 
        fputcsv($handle, $header3);

        foreach ($ledgerdata as $key){ 
           $str = "".date('Y-m-d', strtotime($key->date));
           $opdebit += $key->debit;          
           $opcredit += $key->credit;

           $arr[0]= $str; 
           $arr[1]= $key->voucherid; 
           $arr[2]= $key->vouchertype; 
           
           $arr[3]= $key->description;   
           $arr[4]= $key->debit;   
           $arr[5]= $key->credit; 
           $arr[6]= $opdebit-$opcredit; 
               
           fputcsv($handle, $arr);   
        }
        $header2 = array("","","","Closing Balance",$opdebit,$opcredit,$opdebit-$opcredit); 
        fputcsv($handle, $header2);
        
        fclose($handle);
        exit;
    }

}

?>