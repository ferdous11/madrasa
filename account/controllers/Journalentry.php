<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Journalentry extends CI_Controller {

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
        $data['activemenu'] = 'transection';
        $data['activesubmenu'] = 'journalentry';
        $data['page_title'] = 'Journal Entry';
        $data['baseurl'] = $this->config->item('base_url');
        $data['randomkey'] = time();
        $data['suppliers'] = '';
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $comid = $this->session->userdata('company_id');
            
            $data['ledgergroup'] = $this->db->get_where('accountgroup',array('company_id'=>$comid))->result();
            $compaynyId = $this->session->userdata('company_id');
            $data['journalData'] = $this->db->query("select journalmaster.ledger_name,journalmaster.date,journaldetails.id,journaldetails.ledgerid,journaldetails.journalmasterid,journaldetails.debit,journaldetails.credit,journaldetails.description,journaldetails.company_id from journalmaster join journaldetails on journaldetails.journalmasterid = journalmaster.id where journaldetails.company_id = '$compaynyId' group by journaldetails.journalmasterid order by journaldetails.id desc")->result();
            $this->load->view('journalentry', $data);
        else:
            redirect(base_url());
        endif;
    }

    function addjournal() {

        $drnaem = $this->db->query("select ledgername from accountledger where id=".$this->input->post('debitid'))->row()->ledgername;
        $crname = $this->db->query("select ledgername from accountledger where id=".$this->input->post('creditid'))->row()->ledgername;

        $data = array(
            'date' => $this->input->post('date'),
            'description' => $this->input->post('description'),
            'company_id' => $this->session->userdata('company_id'),
            'ledger_name' => "Dr: ".$drnaem.", Cr: ".$crname
        );
        $insert = $this->db->insert('journalmaster', $data);
        $journal_Id_Last = $this->db->insert_id();
            $dataarrayforjurnal = array(
                'journalmasterid' => $journal_Id_Last,
                'ledgerid' => $this->input->post('debitid'),
                'debit' => $this->input->post('debit'),
                'credit' => 0,
                'description' => $this->input->post('description'),
                'company_id' => $this->session->userdata('company_id'),
                'date' => $this->input->post('date')
            );
            $saveresultjdetails = $this->db->insert('journaldetails', $dataarrayforjurnal);
            $dataarrayforjurnal = array(
                'journalmasterid' => $journal_Id_Last,
                'ledgerid' => $this->input->post('creditid'),
                'debit' => 0,
                'credit' => $this->input->post('credit'),
                'description' => $this->input->post('description'),
                'company_id' => $this->session->userdata('company_id'),
                'date' => $this->input->post('date')
            );
            $saveresultjdetails = $this->db->insert('journaldetails', $dataarrayforjurnal);

            $dataarrayforledger = array(
                'voucherid' => $journal_Id_Last,
                'ledgerid' => $this->input->post('debitid'),
                'vouchertype' => "Journal entry",
                'debit' => $this->input->post('debit'),
                'credit' => 0,
                'description' => $this->input->post('description'),
                'company_id' => $this->session->userdata('company_id'),
                'date' => $this->input->post('date')
            );
            $saveresultlposting = $this->db->insert('ledgerposting', $dataarrayforledger);
            $dataarrayforledger = array(
                'voucherid' => $journal_Id_Last,
                'ledgerid' => $this->input->post('creditid'),
                'vouchertype' => "Journal entry",
                'debit' => 0,
                'credit' => $this->input->post('credit'),
                'description' => $this->input->post('description'),
                'company_id' => $this->session->userdata('company_id'),
                'date' => $this->input->post('date')
            );
            $saveresultlposting = $this->db->insert('ledgerposting', $dataarrayforledger);

            savelog('Journal entry',  'amount: ' . $this->input->post('debit') . ' debit account: ' . $this->input->post('debitid') . ' credit account: ' . $this->input->post('creditid'));
        
        $this->session->set_userdata('success', 'Jurnal Entry added successfully');
        redirect('journalentry');
    }

    function delete_entry($id) {
        $this->db->query("delete from ledgerposting where voucherid = '$id' and vouchertype='Journal entry'");
        $this->db->query("delete from journalmaster where id = '$id'");
        $this->db->query("delete from journaldetails where journalmasterid = '$id'");
        $this->session->set_userdata('success', 'Jurnal Entry deleted successfully');
        savelog('Delete journal entry', 'Journal entry deleted for Id: ' . $id);
        redirect('journalentry');
    }

    function edit_entry($id) {
        $data['activemenu'] = 'transection';
        $data['activesubmenu'] = 'journalentry';
        $data['page_title'] = 'Journal Entry';
        $data['baseurl'] = $this->config->item('base_url');
        $data['randomkey'] = time();
        $data['suppliers'] = '';
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['voucherid'] = $this->db->get('accountledger')->result();

            $data['ledger'] = $this->db->get('accountledger')->result();
            $data['sortalldata'] = $this->db->get_where('journalmaster', array('id' => $id))->result();

            $data['jmasterId'] = $id;

            $company_id = $this->session->userdata('company_id');

            $data['getidValues'] = $this->db->query("select * from journaldetails where journalmasterid = '$id' AND company_id = '$company_id'")->result();
            $data['getLedgerDataValues'] = $this->db->query("select * from ledgerposting where voucherid = '$id' AND vouchertype='Journal entry' AND company_id = '$company_id'")->result();

            $this->load->view('editjournalentry', $data);
        else:
            redirect(base_url());
        endif;
    }

    public function updatedjounal() {
        $JournalMasterID = $_POST['JournalMasterID'];

        $newcredit = $_POST['editcredit'];
        $newdebit = $_POST['editdebit'];
        $ledgerId = $_POST['edit_ledgerId'];

        $drnaem = $this->db->query("select ledgername from accountledger where id=".$ledgerId[0])->row()->ledgername;
        $crname = $this->db->query("select ledgername from accountledger where id=".$ledgerId[1])->row()->ledgername;

        $updatedataJMaster = array(
            'date' => $this->input->post('editdate'),
            'description' => $this->input->post('editdescription'),
            'company_id' => $this->session->userdata('company_id'),
            'ledger_name' => "Dr: ".$drnaem.", Cr: ".$crname
        );
        $this->db->where('id', $JournalMasterID);
        $this->db->update('journalmaster', $updatedataJMaster);
        

        $journalDetailsId = $_POST['journalDetailsId'];
        $updateJournalDetail = array();
        for ($i = 0; $i < count($ledgerId); $i++) {
            $updateJournalDetail[] = array(
                'id' => $journalDetailsId[$i],
                'journalmasterid' => $JournalMasterID,
                'ledgerid' => $ledgerId[$i],
                'debit' => ($newdebit[$i]),
                'credit' => ($newcredit[$i]),
                'description' => $this->input->post('editdescription'),
                'company_id' => $this->session->userdata('company_id')
            );
            savelog('Journal entry update', 'Credit amount: ' . ($newcredit[$i]) . ' debit amount: ' . ($newdebit[$i]) . ' for ledger id ' . $ledgerId[$i]);
        }

        $date = $_POST['editdate'];
        $ledgerPostingId = $_POST['ledgerPostingId'];

        for ($i = 0; $i < count($ledgerId); $i++) {
            $updateLedgerPost[] = array(
                'id' => $ledgerPostingId[$i],
                'voucherid' => $JournalMasterID,
                'ledgerid' => $ledgerId[$i],
                'vouchertype' => "Journal entry",
                'debit' => ($newdebit[$i]),
                'credit' => ($newcredit[$i]),
                'description' => $this->input->post('editdescription'),
                'date' => $date,
                'company_id' => $this->session->userdata('company_id')
            );
            savelog('Journal entry update', 'Credit amount: ' . ($newcredit[$i]) . ' debit amount: ' . ($newdebit[$i]) . ' for ledger id ' . $ledgerId[$i]);
        }
        $this->db->trans_start();
        $this->db->update_batch('journaldetails', $updateJournalDetail, 'id');
        $this->db->update_batch('ledgerposting', $updateLedgerPost, 'id');
        $this->db->trans_complete();
        $this->session->set_userdata('success', 'Jurnal Entry updated successfully');

        redirect('journalentry');
    }

    function getledger(){
        $id = $this->input->post('id');
        $ledgerlist= $this->db->query("select l.id,l.ledgername,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where accountgroupid = '$id' and status<>0 order by ledgername")->result();
        echo json_encode($ledgerlist);
    }

}

?>