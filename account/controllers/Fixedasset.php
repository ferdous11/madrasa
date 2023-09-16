<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fixedasset extends CI_Controller {

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

    public function addasset() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $comid = $this->session->userdata('company_id');
            $datalist = array(
                'account_group' => $this->input->post("accountgroup"),
                'productname' => $this->input->post("assetname"),
                'model' => $this->input->post("assetmodel"),
                'buyprice' => $this->input->post("unitprice"),
                'total_price' => $this->input->post("quantity") * $this->input->post("assetname"),
                'purchase_date' => $this->input->post("sdate"),
                'quantity' => $this->input->post("quantity"),
                'depreciation_cost' => $this->input->post("depreciationcosr"),
                'company_id' => $comid
            );

            $this->db->insert('fixeddeposit', $datalist);
            $this->session->set_userdata('success', 'Asset added successfully');
            savelog('New asset added', 'New asset '.$this->input->post("assetname").' from IP address: '.$_SERVER['REMOTE_ADDR']);
            redirect('reports/fixedstock');
        else:
            redirect('home');
        endif;
    }

    public function updateasset() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $datalist = array(
                'productname' => $this->input->post("assetname"),
                'model' => $this->input->post("assetmodel"),
                'buyprice' => $this->input->post("unitprice"),
                'total_price' => $this->input->post("quantity") * $this->input->post("assetname"),
                'purchase_date' => $this->input->post("sdate"),
                'quantity' => $this->input->post("quantity"),
                'depreciation_cost' => $this->input->post("depreciationcosr"),
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('fixeddeposit', $datalist);
            $this->session->set_userdata('success', 'Asset updated successfully');
            savelog('Asset updated', 'New asset '.$this->input->post("assetname").' updated from IP address: '.$_SERVER['REMOTE_ADDR']);
            redirect('reports/fixedstock');
        else:
            redirect('home');
        endif;
    }

    public function deleteasset($id) {
        $this->db->where('id', $id);
        $this->db->delete('fixeddeposit');
        $this->session->set_userdata('success', 'Asset deleted successfully');
        savelog('Asset deleted', 'Asset '.$id.' deleted from IP address: '.$_SERVER['REMOTE_ADDR']);
        redirect('reports/fixedstock');
    }

}

?>