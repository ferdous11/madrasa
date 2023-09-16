<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
class Master extends CI_Controller {

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
        $this->load->helper('fu');
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->dbutil();
    }

    public function index() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'units';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['unitdata'] = $this->db->get_where('product_unit', array('company_id' => $this->session->userdata('company_id')))->result();
            $this->load->view('units', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }


    //---------------------------------------------  unit
    function deleteunit($id) {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->db->query("delete from product_unit where id = '$id'");
            $this->session->set_userdata('success', 'Product unit deleted successfully');
            savelog('delete unit', 'Unit id: $id');
            redirect('master');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function updateunit() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $this->input->post('unitid');
            $data = array(
                'name' => $this->input->post('unitname'),
                'description' => $this->input->post('details'),
                'user_id' => $this->session->userdata('user_id')

            );
            $this->db->where('id', $id);
            $this->db->update('product_unit', $data);
            savelog('update unit', 'Unit id: $id');
            $this->session->set_userdata('success', 'Unit updated successfully');
            redirect('master');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function addnewunit() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('unitname'),
                'description' => $this->input->post('details'),
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('product_unit', $data);
            $this->session->set_userdata('success', 'Unit added successfully');
            savelog('add unit', 'Unit name ' . $this->input->post('unitname') . ' added successfully');
            redirect('master');
        else:
            $this->load->view('login', $data);
        endif;
    }

    //---------------------------------------------  ledger
    function acledger() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'acledgers';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->db->order_by("name", "asc");
            $data['districts'] = $this->db->get('districts')->result();
            $data['acledger'] = $this->db->query("select l.*,d.name as district_name,ag.name as groupname,u.fullname from accountledger as l left join districts as d on l.district=d.id left join alluser as u on l.user_id=u.id left join accountgroup as ag on l.accountgroupid=ag.id order by l.id desc")->result();
            $data['acgroup'] = $this->db->get('accountgroup')->result();
            $this->load->view('acledger', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function export_acledger() {

        $acledger = $this->db->query("select l.*,d.name as district_name,ag.name as groupname,u.fullname from accountledger as l left join districts as d on l.district=d.id left join alluser as u on l.user_id=u.id left join accountgroup as ag on l.accountgroupid=ag.id order by l.id desc")->result();

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"All Ledger.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
       
        $header = array("Account Group","Ledger Name","Father Name","Ledger Id","Mobile No","Address","District","Opening Balance","Status"); 
        fputcsv($handle, $header);
        if(count($acledger)!=0):
            foreach ($acledger as  $supplier){  

            $arr[0]= $supplier->groupname;
            $arr[1]= $supplier->ledgername; 
            $arr[2]= $supplier->father_name;
            $arr[3]= $supplier->description;
            $arr[4]= $supplier->mobile;
            $arr[5]= $supplier->address;
            $arr[6]= $supplier->district_name;
            $arr[7]= $supplier->openingbalance;
            
            if($supplier->status==0)
                $arr[8]= "Inactive";
            else 
                $arr[8]= "Active";
            fputcsv($handle, $arr);         
            }
        endif;
        fclose($handle);
        exit;
    }

    function showledger($id){
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'acledgers';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->db->order_by("name", "asc");
            $data['districts'] = $this->db->get('districts')->result();
            $data['supp'] = $this->db->query("select l.*,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id='$id' order by l.id desc")->row();
            $data['acgroup'] = $this->db->get('accountgroup')->result();
            $this->load->view('editledger', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function addledger() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $opbalance=($this->input->post('opbalance'));

            if ($this->input->post('baltype') == 'credit'):
                $credit = $opbalance;
                $debit = 0;
            else:
                $debit = $opbalance;
                $credit = 0;
            endif;
            $fromname = $this->input->post('fromname');
            
            
            $accountgroup = $this->input->post('accountgroup');
            if($accountgroup==5||$accountgroup==6||$accountgroup==8||$accountgroup==9)
            $district = $this->input->post('district');
            else 
                $district = 0;
            $datainsert = array(
                'ledgername' => $this->input->post('ledger_name'),
                'mobile' => $this->input->post('mobile'),
                'debit' => $debit,
                'credit' => $credit,
                'openingbalance' => $opbalance,
                'address' => $this->input->post('address'),
                'district'=>$district,
                'father_name'=>$this->input->post('father_name'),
                'accountgroupid' => $accountgroup,
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'date' => date("Y-m-d H:i:s"),
                'status' => '1'
            );
            $this->db->insert('accountledger', $datainsert);
            $supid = $this->db->insert_id();

            $this->session->set_userdata('success', 'Ledger added successfully');



            $datalist = array(
                'voucherid' => $supid,
                'ledgerid' => $supid,
                'date' => date("Y-m-d H:i:s"),
                'vouchertype' => 'Opening Balance',
                'debit' => $debit,
                'credit' => $credit,
                'description' => 'New Account',
                'company_id' => $this->session->userdata('company_id'),
            );
            //$this->db->insert('ledgerposting', $datalist);
            savelog('New ledger', 'New account ledger ' . $this->input->post('ledger_name') . ' created successfully');
            if ($fromname == 'ledger'):
                redirect('master/acledger');
            endif;
            if ($fromname == 'supplier'):
                redirect('master/supplier');
            endif;
            if ($fromname == 'customer'):
                redirect('master/customer');
            endif;
        else:
            $this->load->view('login');
        endif;
    }

    function updateledger() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $this->input->post('updateid');
            $fromname = $this->input->post('fromname');

            if ($this->input->post('baltype') == 'credit'):
                $credit = ($this->input->post('opbalance'));
                $debit = 0;
            else:
                $debit = ($this->input->post('opbalance'));
                $credit = 0;
            endif;

            $accountgroup = $this->input->post('accountgroup');
            if($accountgroup==5||$accountgroup==19||$accountgroup==15||$accountgroup==16||$accountgroup==18)
            $district = $this->input->post('district');
            else 
                $district = 0;

            $opbalance = ($this->input->post('opbalance'));
            $data = array(
                'ledgername' => $this->input->post('ledgername'),
                'mobile' => $this->input->post('mobile'),
                'debit' => $debit,
                'credit' => $credit,
                'openingbalance' => $opbalance,
                'address' => $this->input->post('address'),
                'district'=>$district,
                'father_name'=>$this->input->post('father_name'),
                'accountgroupid' => $accountgroup,
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'date' => date("Y-m-d H:i:s"),
            );
            $this->db->where('id', $id);
            $this->db->update('accountledger', $data);
            $this->session->set_userdata('success', 'Supplier updated successfully');
            savelog('New ledger', 'New account ledger ' . $this->input->post('ledgername') . ' updated successfully');
            if ($fromname == 'ledger'):
                redirect('master/acledger');
            endif;
            if ($fromname == 'supplier'):
                redirect('master/supplier');
            endif;
            if ($fromname == 'customer'):
                redirect('master/customer');
            endif;
        else:
            $this->load->view('login');
        endif;
    }

    function deleteledger($id) {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->db->query("delete from accountledger where id = '$id'");
            $this->db->query("delete from payments where ledgerid = '$id'");
            $this->db->query("delete from received where ledgerid = '$id'");
            $this->db->query("delete from ledgerposting where ledgerid = '$id'");
            $this->session->set_userdata('success', 'Account ledger deleted successfully');
            redirect('master/acledger');
        else:
            $this->load->view('login');
        endif;
    }

    public function changeStatus(){
        $ledger_id =  $this->input->post('ledger_id');
        $this->db->query("update accountledger set status= NOT status where id='$ledger_id'");
    }

    //---------------------------------------------  ledger group
    function acgroup() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'acgroup';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['acgroup'] = $this->db->get('accountgroup')->result();
            $this->load->view('acgroup', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function addgroup() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('name'),
                'details' => $this->input->post('details'),
                'company_id' => $this->session->userdata('company_id'),
                'date' => date("Y-m-d H:i:s")
            );
        $cName=$this->input->post('name');

        $rCount = $this->db->query("select id from accountgroup where name like '$cName' limit 1")->row();
        
        if($rCount){
             $this->session->set_userdata('failed', 'Account group already exist.');
        }
        else{
            $this->db->insert('accountgroup', $data);
            $this->session->set_userdata('success', 'Account group added successfully');
        }
            
            redirect('master/acgroup');
        else:
            $this->load->view('login');
        endif;
    }

    function checkacgroup() {

        $com_id= $this->session->userdata("company_id");
        $aclname = $this->input->post('ledgername');
        $acid = $this->input->post('accountgroupid');

        if ($acid == '11'):
            $checkdb = $this->db->query("select * from accountledger where accountgroupid = '$acid' and company_id='$com_id'")->row();
            if (sizeof($checkdb) > 0):
                echo 'no';
            else:
                echo 'yes';
            endif;
        elseif ($acid == '22'):
            $checkdb = $this->db->query("select * from accountledger where accountgroupid = '$acid' and company_id='$com_id'")->row();
            if (sizeof($checkdb) > 0):
                echo 'no';
            else:
                echo 'yes';
            endif;

        elseif ($acid == '25'):
            echo 'customer';

        elseif ($acid == '23'):
            $checkdb = $this->db->query("select * from accountledger where accountgroupid = '$acid' and company_id='$com_id'")->row();
            if (sizeof($checkdb) > 0):
                echo 'no';
            else:
                echo 'yes';
            endif;
        else:
            $idexist = $this->db->query("select id from accountledger where ledgername like '$aclname' and company_id='$com_id' and accountgroupid = '$acid' limit 1")->row();
            if ($idexist):
                echo 'exist';
            else:
                echo 'yes';
            endif;
        endif;
    }

    function updateacgroup() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('name'),
                'details' => $this->input->post('details')
            );
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->update('accountgroup', $data);
            $this->session->set_userdata('success', 'Account group updated successfully');
            redirect('master/acgroup');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function deleteacgroup($id) {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->db->query("delete from accountgroup where id = '$id'");
            $this->session->set_userdata('success', 'Account group deleted successfully');
            redirect('master/acgroup');
        else:
            $this->load->view('login', $data);
        endif;
    }


    //--------------------------------------------  salesman
    function salesman() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'salesman';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['salesmandata'] = $this->db->get_where('alluser', array('company_id' => $this->session->userdata('company_id'), 'role' => 'user'))->result();
            $this->load->view('salesman', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function addsalesman() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'login' => $this->input->post('userid'),
                'password' => $this->input->post('password'),
                'role' => 'user',
                'fullname' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'company_id' => $this->session->userdata('company_id'),
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('alluser', $data);
            savelog('add sales man', 'name' . $this->input->post('name') . ' Sales Man added successfully');
            $this->session->set_userdata('success', 'Sales Man added successfully');
            redirect('master/salesman');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function updatesalesman() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $this->input->post('sell_id');
            $data = array(
                'fullname' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->where('id', $id);
            $this->db->update('alluser', $data);
            $this->session->set_userdata('success', 'Sales Man updated successfully');
            savelog('update sales man', 'id $id' );
            redirect('master/salesman');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function deletesalesman($id) {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->db->query("delete from alluser where id = '$id'");
            $this->session->set_userdata('success', 'Sales Man deleted successfully');
            savelog('delete sales man', 'id $id' );
            redirect('master/salesman');
        else:
            $this->load->view('login', $data);
        endif;
    }

    //--------------------------------------------  customer
    function customer() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'customer';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        $compid = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $acid = $this->db->get_where('accountgroup', array('name' => 'customer'))->row()->id;
            $data['customerdata'] = $this->db->query("select * from accountledger where company_id = '$compid' AND accountgroupid = '$acid' order by id desc")->result();
            $this->load->view('customer', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function updatecustomer() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $this->input->post('cus_id');
            $data = array(
                'name' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->where('id', $id);
            $this->db->update('customers', $data);
            $this->session->set_userdata('success', 'Customer updated successfully');
            redirect('master/customer');
        else:
            $this->load->view('login', $data);
        endif;
    }
    //--------------------------category

        public function category() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'category';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['categorydata'] = $this->db->get_where('category', array('company_id' => $this->session->userdata('company_id')))->result();
            $this->load->view('category', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function updatecategory() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('categoryname'),
                'description' => $this->input->post('details'),
            );
            $this->db->where('id', $id);
            $this->db->update('category', $data);
            $this->session->set_userdata('success', 'Category updated successfully');
            redirect('master/category');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function addnewcategory() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('categoryname'),
                'description' => $this->input->post('details'),
                'company_id' => $this->session->userdata('company_id'),
                'status' =>1,
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('category', $data);
            $this->session->set_userdata('success', 'New category added successfully');
            savelog('add category', 'Category name ' . $this->input->post('categoryname'));
            redirect('master/category');
        else:
            $this->load->view('login', $data);
        endif;
    }

    //backup data base
    function dbbackup(){
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $prefs = array(     
            'format'      => 'zip',             
            'filename'    => 'my_db_backup.sql'
            );


        $backup =& $this->dbutil->backup($prefs); 

        $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
        $save = 'neotech_db/'.$db_name;
        write_file($save, $backup); 
        force_download($db_name, $backup);

        else:
            $this->load->view('login');
        endif;
    }

}

?>