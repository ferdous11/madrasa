<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bankmanagement extends CI_Controller {

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
        $data['activemenu'] = 'bankmanagement';
        $data['activesubmenu'] = 'banklist';
        $data['page_title'] = 'Bank management';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['bankdata'] = $this->db->get('bankaccountlist')->result();
            $this->load->view('bankmangement', $data);
        else:
            redirect('home');
        endif;
    }

    function addbank() {
        if ($this->input->post('accounttype') == 'primary'):
            $type = 'primary';
        else:
            $type = 'secondary';
        endif;
        $bdata = array(
            'bankname' => $this->input->post('bank_name'),
            'bankaccountname' => $this->input->post('bankaccname'),
            'bankaccount' => $this->input->post('bankacc'),
            'bankgroup' => ($type == 'secondary') ? $this->input->post('accountgroup') : '0',
            'accounttype' => $this->input->post('accounttype'),
            'date' => date("Y-m-d H:i:s"),
            'status' => $this->input->post('acstatus'),
            'balance' => $this->input->post('opbalance'),
            'balancetype' => $this->input->post('balanceType')
        );

        $this->db->insert('bankaccountlist', $bdata);
        $lastid = $this->db->insert_id();

        $dattt = array(
            'bankid' => $lastid,
            'bankgroup' => ($type == 'secondary') ? $this->input->post('accountgroup') : '0',
            'amount' => $this->input->post('opbalance'),
            'payment_type' => $this->input->post('balanceType'),
            'date' => date("Y-m-d H:i:s"),
            'details' => 'Opening balance',
        );
        $this->db->insert('bankdeposit', $dattt);

        $this->session->set_userdata('success', 'Bank Information Added successfully');
        redirect('bankmanagement');
    }

    function bankgroup() {
        $data['activemenu'] = 'bankmanagement';
        $data['activesubmenu'] = 'bankgroup';
        $data['page_title'] = 'Bank management';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['bankgroupdata'] = $this->db->get('bankgroup')->result();
            $this->load->view('bankgrouplist', $data);
        else:
            redirect('home');
        endif;
    }

    function addbankgroup() {
        $bdata = array(
            'groupname' => $this->input->post('groupname'),
            'creditlimit' => $this->input->post('creditlimit'),
            'date' => date("Y-m-d H:i:s")
        );
        $this->db->insert('bankgroup', $bdata);
        $this->session->set_userdata('success', 'Bank Information Added successfully');
        redirect('bankmanagement/bankgroup');
    }

    function updatebankgroup() {
        $id = $this->input->post('id');
        $bdata = array(
            'groupname' => $this->input->post('groupname'),
            'creditlimit' => $this->input->post('creditlimit'),
            'date' => date("Y-m-d H:i:s")
        );
        $this->db->where('id', $id);
        $this->db->update('bankgroup', $bdata);
        $this->session->set_userdata('success', 'Bank Information updated successfully');
        redirect('bankmanagement/bankgroup');
    }

    function deletebankgroup($id) {
        $this->db->query("delete from bankgroup where id = '$id'");
        $this->session->set_userdata('success', 'Bank Group Information deleted successfully');
        redirect('bankmanagement/bankgroup');
    }

    function deletebank($id) {
        $this->db->query("delete from bankaccountlist where id = '$id'");
        $this->session->set_userdata('success', 'Bank Information deleted successfully');
        redirect('bankmanagement');
    }

    function updatebank() {
        $id = $this->input->post('id');
        if ($this->input->post('accounttype') == 'primary'):
            $type = 'primary';
        else:
            $type = 'secondary';
        endif;
        $currbalance = $this->db->get_where('bankaccountlist', array('id' => $id))->row()->balance;
        $bdata = array(
            'bankname' => $this->input->post('bank_name'),
            'bankaccountname' => $this->input->post('bankaccname'),
            'bankaccount' => $this->input->post('bankacc'),
            'balance' => $currbalance + $this->input->post('opbalance'),
            'bankgroup' => ($type == 'secondary') ? $this->input->post('accountgroup') : '0',
            'accounttype' => $this->input->post('accounttype'),
            'status' => $this->input->post('acstatus'),
            'date' => date("Y-m-d H:i:s")
        );

        $this->db->where('id', $id);
        $this->db->update('bankaccountlist', $bdata);
        
        $getdepositAmount = $this->db->get_where('bankdeposit', array('bankid' => $id))->row()->amount;
        $dtotalbal = $getdepositAmount + $this->input->post('opbalance');
        
        $updateDepBal = $this->db->query("update bankdeposit set amount = '$dtotalbal' where bankid = '$id'");
        
        $this->session->set_userdata('success', 'Bank Information updated successfully');
        redirect('bankmanagement');
    }

    function transection($id) {
        $data['activemenu'] = 'bankmanagement';
        $data['activesubmenu'] = 'bankmanagement';
        $data['page_title'] = 'Bank management';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['bankid'] = $id;
            $data['bankname'] = $this->db->get_where('bankaccountlist', array('id' => $id))->row();
            $data['bankpaymentdata'] = $this->db->order_by('id', 'asc')->get_where('bankdeposit', array('bankid' => $id))->result();
            $this->load->view('transection', $data);
        else:
            redirect('home');
        endif;
    }

    function addpayment() {
        $balance = 0;
        $totalNetbalance = 0;
        $balance_destination = 0;

        $type = $this->input->post('paymenttype');
        $sourcebank = $this->input->post('sourcebank');
        $destination = $this->input->post('destinationbank');
        $Amount = $this->input->post('amount');
        $note = $this->input->post('note');

        $bankid = $this->input->post('bankid');
        #echo $sourcebank;exit();
        $accountStatus = $this->db->query("select status from bankaccountlist where status = 'active' AND id = '$destination'")->row();
        if (sizeof($accountStatus) > 0):
            if ($type == 'debit'):
                $sourcebalanceQry = $this->db->get_where('bankaccountlist', array('id' => $sourcebank))->row();
                $balance = $sourcebalanceQry->balance;
                $totalNetbalance = $balance - $Amount;

                $updateSourceAccount = $this->db->query("update bankaccountlist set balance = '$totalNetbalance' where id = '$sourcebank'");

                $desbalanceQry = $this->db->get_where('bankaccountlist', array('id' => $destination))->row();
                $balance_destination = $desbalanceQry->balance;
                $totalNetbalanceDestination = $balance_destination + $Amount;

                $updateDestinationAccount = $this->db->query("update bankaccountlist set balance = '$totalNetbalanceDestination' where id = '$destination'");

                $destination_bank_groupid = $this->db->get_where('bankaccountlist', array('id' => $destination))->row();
                if (sizeof($destination_bank_groupid) > 0):
                    $dgroupId = $destination_bank_groupid->bankgroup;
                else:
                    $dgroupId = '0';
                endif;
                $dattt = array(
                    'bankid' => $sourcebank,
                    'bankgroup' => '0',
                    'amount' => $Amount,
                    'payment_type' => 'credit',
                    'date' => date("Y-m-d H:i:s"),
                    'details' => $note,
                );
                $this->db->insert('bankdeposit', $dattt);

                $dattt2 = array(
                    'bankid' => $destination,
                    'bankgroup' => $dgroupId,
                    'amount' => $Amount,
                    'payment_type' => 'debit',
                    'date' => date("Y-m-d H:i:s"),
                    'details' => $note,
                );
                $this->db->insert('bankdeposit', $dattt2);

            endif;

            if ($type == 'credit'):
                $destination_bank_groupid = $this->db->get_where('bankaccountlist', array('id' => $destination))->row();
                if (sizeof($destination_bank_groupid) > 0):
                    $dgroupId = $destination_bank_groupid->bankgroup;
                else:
                    $dgroupId = '0';
                endif;

                $sourcebalanceQry = $this->db->get_where('bankaccountlist', array('id' => $sourcebank))->row();
                $balance = $sourcebalanceQry->balance;
                $totalNetbalance = $balance + $Amount;

                $updateSourceAccount = $this->db->query("update bankaccountlist set balance = '$totalNetbalance' where id = '$destination'");

                $dattt2 = array(
                    'bankid' => $destination,
                    'bankgroup' => $dgroupId,
                    'amount' => $Amount,
                    'payment_type' => 'debit',
                    'date' => date("Y-m-d H:i:s"),
                    'details' => $note,
                );
                $this->db->insert('bankdeposit', $dattt2);
            endif;
            $this->session->set_userdata('success', 'Payment completed successfully');
            redirect('bankmanagement/transection/' . $bankid);

        else:
            $this->session->set_userdata('failed', 'Payment failed.Account status is <b>Closed</b>');
            redirect('bankmanagement/transection/' . $bankid);
        endif;
    }

    function transectionhistory() {
        $data['activemenu'] = 'bankmanagement';
        $data['activesubmenu'] = 'transectionhistory';
        $data['page_title'] = 'Bank management';
        $data['baseurl'] = $this->config->item('base_url');
        $data['bankpaymentdata'] = array();
        $data['groupid'] = '';
        $data['accountid'] = '';
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['bankgroup'] = $this->db->get('bankgroup')->result();
            $data['accountlist'] = $this->db->query("select * from bankaccountlist where bankgroup != '0'")->result();
            $this->load->view('transectionhistory', $data);
        else:
            redirect('home');
        endif;
    }

    function showaccountname() {
        $id = $_POST['id'];
        $customerquery = $this->db->query("select * from bankaccountlist where bankgroup = '$id'");
        if ($customerquery->num_rows() > 0):
            echo '<option value="-1">Select account name</option>';
            echo '<option value="all">All</option>';
            foreach ($customerquery->result() as $customers):
                echo '<option value="' . $customers->id . '">' . $customers->bankaccountname . '</option>';
            endforeach;
        else:
            echo '<option value="-1">Select pin</option>';
        endif;
    }

    function viewtotaltransection() {
        $data['activemenu'] = 'bankmanagement';
        $data['activesubmenu'] = 'transectionhistory';
        $data['page_title'] = 'Bank management';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['bankgroup'] = $this->db->get('bankgroup')->result();
            $data['accountlist'] = $this->db->query("select * from bankaccountlist where bankgroup != '0' order by id desc")->result();

            $groupid = $this->input->post('bankgroup');
            $accontid = $this->input->post('accountid');
            $data['groupid'] = $groupid;
            $data['accountid'] = $accontid;

            if ($accontid == 'all'):
                $data['bankpaymentdata'] = $this->db->query("select * from bankdeposit where bankgroup = '$groupid' order by id desc")->result();
            else:
                $data['bankpaymentdata'] = $this->db->query("select * from bankdeposit where bankid = '$accontid' AND bankgroup = '$groupid' order by id desc")->result();
            endif;



            $this->load->view('transectionhistory', $data);
        else:
            redirect('home');
        endif;
    }

    function allaccount($gid = '') {
        $data['activemenu'] = 'bankmanagement';
        $data['activesubmenu'] = 'transectionhistory';
        $data['page_title'] = 'Bank management';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['bankgroup'] = $this->db->get('bankgroup')->result();
            $data['accountlist'] = $this->db->query("select * from bankaccountlist where bankgroup != '0' order by id desc")->result();

            $data['groupid'] = $gid;
            $data['accountid'] = 'all';

            $data['bankpaymentdata'] = $this->db->query("select * from bankdeposit where bankgroup = '$gid'")->result();

            $this->load->view('transectionhistory', $data);
        else:
            redirect('home');
        endif;
    }

}

?>