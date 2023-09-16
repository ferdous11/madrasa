<?php

class Contravoucherd extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('common_helper');
    }

    public function ledgerdata() {
        $this->db->select('*');
        $this->db->from('accountledger');
        $this->db->where('company_id', $this->session->userdata('company_id'));
        $this->db->where('accountgroupid', '5');
        $query = $this->db->get();
        return $query->result();
    }

    public function addcontradetails() {
        $type = $_POST['optionsRadios'];

        $ledgerId = $_POST['ledgerId'];
        $new_ledgerid = 1;
        //$newamount = fu_b2eNo($_POST['amount']);
        $newamount = $_POST['amount'];
        $dataforcontradetail = array(
          
            'ledger_id' => $ledgerId,
            'type' => $type,
            'amount' => $newamount,
            'cheque_no' => $this->input->post('chequeNo'),
            'cheque_date' => $this->input->post('chequeDate'),
            'date' => $this->input->post('date'),
            'company_id' => $this->session->userdata('company_id'),
            'user_id' => $this->session->userdata('user_id')
        );
        $saveresultContdetails = $this->db->insert('contravoucher', $dataforcontradetail);
        $contraMasterId = $this->db->insert_id();

        if ($type == "Deposit") {
           
            $dataforLedgerpostDebit = array(
                'voucherid' => $contraMasterId,
                'ledgerid' => $ledgerId,
                'vouchertype' => "Contra Voucher",
                'debit' => $newamount,
                'credit' => '0',
                'description' => "Contra Voucher",
                'date' => $this->input->post('date'),
                'company_id' => $this->session->userdata('company_id')
            );
            $saveresultlpostingDebit = $this->db->insert('ledgerposting', $dataforLedgerpostDebit);
            $dataforLedgerpostCredit = array(
                'voucherid' => $contraMasterId,
                'ledgerid' => $new_ledgerid,
                'vouchertype' => "Contra Voucher",
                'debit' => '0',
                'credit' => $newamount,
                'description' => "Contra Voucher",
                'date' => $this->input->post('date'),
                'company_id' => $this->session->userdata('company_id')
            );
            $saveresultlpostingCredit = $this->db->insert('ledgerposting', $dataforLedgerpostCredit);
        }
        if ($type == "Withdraw") {

            $dataforLedgerpostDebit = array(
                'voucherid' => $contraMasterId,
                'ledgerid' => $ledgerId,
                'vouchertype' => "Contra Voucher",
                'debit' => '0',
                'credit' => $newamount,
                'description' => "Contra Voucher",
                'date' => $this->input->post('date'),
                'company_id' => $this->session->userdata('company_id')
            );
            $savelpostingDebit = $this->db->insert('ledgerposting', $dataforLedgerpostDebit);
            $dataforLedgerpostCredit = array(
                'voucherid' => $contraMasterId,
                'ledgerid' => $new_ledgerid,
                'vouchertype' => "Contra Voucher",
                'debit' => $newamount,
                'credit' => '0',
                'description' => "Contra Voucher",
                'date' => $this->input->post('date'),
                'company_id' => $this->session->userdata('company_id')
            );
            $savelpostingCredit = $this->db->insert('ledgerposting', $dataforLedgerpostCredit);
        }

        if ($saveresultContdetails && $saveresultlpostingDebit && $saveresultlpostingCredit):
            return true;
        endif;
        if ($saveresultContdetails && $savelpostingDebit && $savelpostingCredit):
            return TRUE;
        endif;
    }

    public function updatedcontravoucher() {
        $contramasterid = $_POST['editcontravoucherid'];
        $updatedataConMaster = array(
            'date' => $this->input->post('editdate'),
            'ledger_id' => $this->input->post('editledgerId'),
            'amount' => $this->input->post('editamount'),
            'type' => $this->input->post('optionsRadios'),
            'cheque_no' => $this->input->post('editchequeNo'),
            'cheque_date' => $this->input->post('editchequeDate'),
            'company_id' => $this->session->userdata('company_id'),
            'user_id' => $this->session->userdata('user_id')
        );
        $this->db->where('id', $contramasterid);
        $this->db->update('contravoucher', $updatedataConMaster);

        $type = $_POST['optionsRadios'];
        $ledgerId = $_POST['editledgerId'];
        $newamount = $_POST['editamount'];
        $previousledger = $_POST['previousledger'];
        
        if ($type == "Deposit") {
            $dataforLedgerpostDebit = array(
                'voucherid' => $contramasterid,
                'ledgerid' => $ledgerId,
                'voucherType' => "Contra Voucher",
                'debit' => $newamount,
                'credit' => '0',
                'description' => "Contra Voucher",
                'date' => $this->input->post('editdate'),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->where(array('vouchertype'=> 'Contra Voucher','voucherid'=>'$contramasterid','ledgerid'=>'$previousledger'));
            $this->db->update('ledgerposting', $dataforLedgerpostDebit);
            $dataforLedgerpostCredit = array(
                'voucherid' => $contramasterid,
                'ledgerid' => 1,
                'vouchertype' => "Contra Voucher",
                'debit' => '0',
                'credit' => $newamount,
                'description' => "Contra Voucher",
                'date' => $this->input->post('editdate'),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->where(array('vouchertype'=> 'Contra Voucher','voucherid'=>'$contramasterid','ledgerid'=>'1'));
            $this->db->update('ledgerposting', $dataforLedgerpostCredit);
        }
        if ($type == "Withdraw") {
            $dataforLedgerpostDebit = array(
                'voucherid' => $contramasterid,
                'ledgerid' => $ledgerId,
                'vouchertype' => "Contra Voucher",
                'debit' => '0',
                'credit' => $newamount,
                'description' => "Contra Voucher",
                'date' => $this->input->post('date'),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->where(array('vouchertype'=> 'Contra Voucher','voucherid'=>'$contramasterid','ledgerid'=>'$previousledger'));
            
            $this->db->update('ledgerposting', $dataforLedgerpostDebit);
            $dataforLedgerpostCredit = array(
                'voucherid' => $contramasterid,
                'ledgerid' => $new_ledgerid,
                'vouchertype' => "Contra Voucher",
                'debit' => $newamount,
                'credit' => '0',
                'description' => "Contra Voucher",
                'date' => $this->input->post('editdate'),
                'company_id' => $this->session->userdata('company_id')
            );
             $this->db->where(array('vouchertype'=> 'Contra Voucher','voucherid'=>'$contramasterid','ledgerid'=>'1'));
            $this->db->update('ledgerposting', $dataforLedgerpostCredit);
        }
    }

    public function deletecontraMaster($contraMasterId) {        
        $companyId = $this->session->userdata('company_id');
        $queryformaster = $this->db->query("Delete from contravoucher where id='$contraMasterId' AND company_id='$companyId'");
        
        $queryforledger = $this->db->query("Delete from ledgerposting where company_id='$companyId' AND (voucherid='$contraMasterId' AND vouchertype='Contra Voucher')");
        if ($queryformaster && $queryforledger):           
            return TRUE;
        else :
            return FALSE;
        endif;
    }

}
