<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

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
        $data['activemenu'] = 'settings';
        $data['activesubmenu'] = 'storesettings';
        $data['page_title'] = 'Products';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $comid = $this->session->userdata('company_id');
            $data['store'] = $this->db->get_where('company',array('company_id'=>$comid))->row();
            $this->load->view('companysetting', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function addcompany() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'company_name' => $this->input->post('sname'),
                'company_id' => $this->input->post('companyid'),
                'address' => $this->input->post('address'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'website' => $this->input->post('website'),
                'status' => $this->input->post('status')
            );
            $this->db->insert('company', $data);
            $this->session->set_userdata('success', 'Company information added successfully');
            savelog('New company added', 'Company : '.$this->input->post('sname').' created by '.$this->session->userdata('company_id'));
            redirect('company');
        else:
            redirect(base_url());
        endif;
    }

    function updatecompany() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'company_name' => $this->input->post('sname'),                
                'address' => $this->input->post('address'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'website' => $this->input->post('website'),
                'status' => $this->input->post('status')
            );
            $id = $this->input->post('id');
            $this->db->where('id',$id);
            $this->db->update('company', $data);
            $this->session->set_userdata('success', 'Company information updated successfully');
            savelog('New company added', 'Company : '.$this->input->post('sname').' updated by '.$this->session->userdata('company_id'));
            redirect('company');
        else:
            redirect(base_url());
        endif;
    }

}

?>