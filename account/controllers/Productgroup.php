<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productgroup extends CI_Controller {

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

    function index() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'pgroup';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        $companyid = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['pgroup'] = $this->db->get_where('productgroup',array('company_id' => $companyid))->result();
            $this->load->view('productgroup', $data);
        else:
            redirect(base_url());
        endif;
    }

    function addgroup() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('name'),
                'details' => $this->input->post('details'),
                'company_id' => $this->session->userdata('company_id')                
            );
            $this->db->insert('productgroup', $data);
            $this->session->set_userdata('success', 'Product group added successfully');
            redirect('productgroup');
        else:
            redirect(base_url());
        endif;
    }

    function updategroup() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('name'),
                'details' => $this->input->post('details')
            );
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->update('productgroup', $data);
            $this->session->set_userdata('success', 'Product group updated successfully');
            redirect('productgroup');
        else:
            redirect(base_url());
        endif;
    }

    function deletegroup($id) {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->db->query("delete from productgroup where id = '$id'");
            $this->session->set_userdata('success', 'Product group deleted successfully');
            redirect('productgroup');
        else:
            redirect(base_url());
        endif;
    }

}
