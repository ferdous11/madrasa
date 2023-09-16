<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Receiveproduct extends CI_Controller {

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
        $data['activemenu'] = 'production';
        $data['activesubmenu'] = 'receive';
        $data['page_title'] = 'Receive product';
        $data['baseurl'] = $this->config->item('base_url');
        $data['sdate'] = date("Y").'-01-01';
        $data['edate'] = date("Y-m-d");
        $data['ledgername'] = '';
        $sdate = date("Y").'-01-01';
        $edate = date("Y-m-d");

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):            
            $data['receivedata'] = $this->db->query("select * from purchase where date between '$sdate' AND '$edate' AND stype = 'factory'")->result();
            $this->load->view('receiveproduct', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function current_receive() {
        $data['activemenu'] = 'production';
        $data['activesubmenu'] = 'receive';
        $data['page_title'] = 'Receive product';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['randsellid'] = $this->input->get('id');

            $data['cname'] = $this->input->get('cname');
            $data['pid'] = $this->input->get('pid');
            $data['date'] = $this->input->get('date');

            $this->load->view('create_receive', $data);
        else:
            redirect(base_url());
        endif;
    }

    function getpdetails() {
        $pid = $this->input->post('productid');
        $pdata = $this->db->query("select * from issuelist where id = '$pid'")->row();
        echo json_encode($pdata);
    }

    function tempreceive() {
        $productval = $this->input->post('productid');
        $rawdata = explode(':', $productval);
        $cname = $this->input->post('cname');
        $date = $this->input->post('date');

        #$pdetails = $this->db->get_where('purchase', array('product_id' => $product_id))->row();

        $datav = array(
            'randsellid' => $this->input->post('randsellid'),
            'pname' => $rawdata[1],
            'sellprice' => $this->input->post('price'),
            'pid' => $rawdata[0],
            'qty' => $this->input->post('freeqty'),
            'unit' => $this->input->post('unit'),
            'total_price' => $this->input->post('price') * $this->input->post('freeqty')
        );
        $this->db->insert('tempsell', $datav);
        redirect('receiveproduct/current_receive?id=' . $this->input->post('randsellid') . '&pid=' . $rawdata[0] . '&cname=' . $cname . '&date=' . $date);
    }

    function create_receive() {
        $data['activemenu'] = 'production';
        $data['activesubmenu'] = 'receive';
        $data['page_title'] = 'Receive product to factory';
        $data['baseurl'] = $this->config->item('base_url');
        $data['pid'] = '';
        $data['cname'] = '';
        $data['randsellid'] = rand(1111, time());
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->load->view('create_receive', $data);
        else:
            redirect(base_url());
        endif;
    }

    function remove_receive($id) {
        $randid = $this->db->get_where('tempsell', array('id' => $id))->row()->randsellid;
        $this->db->query("delete from tempsell where id = '$id'");
        redirect('issueproduct/current_issue?id=' . $randid . '&pid=0&cname=0&date=' . date("Y-m-d"));
    }

    function savereceive() {
        $supplieridId = $this->input->post('cname');

        $pricelist = $this->input->post('pricelist');
        $requriedQty = $this->input->post('qty');
        $idlist = $this->input->post('idlist');
        $unit = $this->input->post('unit');
        $pname = $this->input->post('pname');

        for ($i = 0; $i < count($idlist); $i++) {
            
            $pidentitiy = $this->db->get_where('products', array('pname' => $pname[$i]))->row();
            #$pidentitiy = $this->db->get_where('products', array('id' => $pid))->row();

            $datar = array(
                'invoiceid' => time(),
                'product_id' => $pidentitiy->id,
                'product_group_id' => $pidentitiy->product_group_id,
                'supplier_id' => $supplieridId,
                'buyprice' => $pricelist[$i],
                'sellprice' => $pricelist[$i],
                'total_buyprice' => 0,
                'total_sellprice' => 0,
                'netproductcost' => 0,
                'quantity' => $requriedQty[$i],
                'available_quantity' => $requriedQty[$i],
                'randomkey' => time(),
                'unit' => $unit[$i],
                'tax' => 0,
                'shippingcost' => 0,
                'othercost' => 0,
                'product_name' => $pname[$i],
                'pmodel' => 0,
                'manufacturer' => 'ASM',
                'category' => '',
                'date' => date("Y-m-d H:i:s"),
                'ptype' => 'purchase',
                'stype' => 'factory',
                'confirm' => 'yes',
                'company_id' => $this->session->userdata('company_id')
            );

            $this->db->insert('purchase', $datar);
        }

        $this->session->set_userdata('success', 'Product received successfully');
        redirect('receiveproduct');
    }

    function deletereceive($id) {
        $this->db->query("delete from purchase where id = '$id'");
        $this->session->set_userdata('success', 'Product receive deleted successfully');
        redirect('receiveproduct');
    }

    function updatereceive() {
        $id = $this->input->post('id');

        $listid = $this->input->post('productname');
        $sublist = explode('-', $listid);
        $pid = $sublist[0];
        $manufacturer = $sublist[1];
        $category = $sublist[2];

        $unity = $this->input->post('unit');
        $unit = $this->db->get_where('product_unit', array('id' => $unity))->row()->name;
        $pname = $this->db->get_where('products', array('id' => $pid))->row()->pname;

        $supid = $this->input->post('suppliername');
        #$sname = $this->db->get_where('accountledger', array('id' => $supid))->row()->ledgername;

        $datalist = array(
            'product_id' => $pid,
            'product_name' => $pname,
            'manufacturer' => $manufacturer,
            'category' => $category,
            'quantity' => $this->input->post('quantity'),
            'available_quantity' => $this->input->post('quantity'),
            'unit' => $unit,
            'buyprice' => $this->input->post('price'),
            'supplier_id' => $supid,
            'date' => date("Y-m-d H:i:s"),
        );
        $this->db->where('id', $id);
        $this->db->update('purchase', $datalist);
        $this->session->set_userdata('success', 'Product receive updated successfully');
        redirect('receiveproduct');
    }

    function viewReceive() {
        $sdate = $this->input->post('sdate') . ' 00:00:00';
        $edate = $this->input->post('edate') . ' 23:59:59';
        $ledgerid = $this->input->post('customer');

        $data['activemenu'] = 'production';
        $data['activesubmenu'] = 'receive';
        $data['page_title'] = 'Receive product';
        $data['baseurl'] = $this->config->item('base_url');
        $data['sdate'] = $this->input->post('sdate');
        $data['edate'] = $this->input->post('edate');

        $data['ledgername'] = $this->input->post('customer');

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['receivedata'] = $this->db->query("select * from purchase where date between '$sdate' AND '$edate' AND supplier_id = '$ledgerid'")->result();
            $this->load->view('receiveproduct', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

}

?>