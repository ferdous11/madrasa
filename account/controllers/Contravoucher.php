<?php

class Contravoucher extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('contravoucherd');
    }

    public function index() {
        $data['baseurl'] = $this->config->item('base_url');
        $data['title'] = "Contra Voucher";
        $data['activemenu'] = "transection";
        $data['activesubmenu'] = "contravoucher";
        
        $data['ledger'] = $this->contravoucherd->ledgerdata(); 
        //$data['contradata'] = $this->db->get("contravoucher")->result();     
        $data['contradata'] = $this->db->query("select c.*,u.fullname as user_name,l.ledgername from contravoucher as c left join alluser as u on c.user_id=u.id left join accountledger as l on c.ledger_id=l.id where c.date>date('Y-m-d 00:00:00')")->result();
        
        $this->load->view('contravoucher', $data);
    }

    public function ledgerdata() {
        $c = $this->input->post('c');$this->contravoucherd->
        $ledger = ledgerdata();
        echo '<input type="hidden" id="count" name="count" value="' . $c . '" />';
        echo '<select class = "form-control" id = "new_ledgerId' . $c . '" name = "new_ledgerId[]">';
        echo '<option value = "">Select</option>';
        foreach ($ledger as $value) {
            echo "<option value='" . $value->ledgerId . "'>$value->acccountLedgerName</option>";
        }
        echo '</select>';
    }

    public function addcontravoucher() {
        $cmpid = $this->session->userdata('company_id');
        
        $isaddedCntDetail = $this->contravoucherd->addcontradetails();
        
        if ($isaddedCntDetail) {
            $this->session->set_userdata('success', 'Contra Voucher added successfully');
            redirect('contravoucher');
        } else {
            $this->session->set_userdata('fail', 'Contra Voucher add failed');
            redirect('contravoucher');
        }
    }

    public function editcontravoucher($id) {
        $data['baseurl'] = $this->config->item('base_url');
        $data['title'] = "Contra Voucher";
        $data['activemenu'] = "transection";
        $data['activesubmenu'] = "contravoucher";
        $data['ledger'] = $this->db->query("Select * from accountledger where accountgroupid=5")->result();
     
        $query = $this->db->query("Select * from contravoucher where id='$id'");
        $IdAvailable = $query->row()->id;
        if ($IdAvailable == "") {
            redirect('contravoucher');
        } else {
            $data['rows'] = $query->row();
            $this->load->view('editcontra', $data);
        }
    }

    public function editcontravoucher2() {
        $isupdatedConMaster = $this->contravoucherd->updatedcontravoucher();
        if ($isupdatedConMaster) {
            $this->session->set_userdata('success', 'Contra Voucher Updated successfully');
            redirect('contravoucher');
        } else {
            $this->session->set_userdata('fail', 'Contra Voucher update failed');
            redirect('contravoucher');
        }
    }

    public function deletecontravoucher($id) {
        $isdeleted = $this->contravoucherd->deletecontraMaster($id);
        if ($isdeleted) {
            $this->session->set_userdata('success', 'Contra Voucher deleted successfully');
            redirect('contravoucher');
        } else {
            $this->session->set_userdata('fail', 'Contra Voucher delete failed');
            redirect('contravoucher');
        }
    }

}
