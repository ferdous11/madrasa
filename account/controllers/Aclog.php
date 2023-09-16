<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Aclog extends CI_Controller {

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
        $data['activemenu'] = 'settings';
        $data['activesubmenu'] = 'aclog';
        $data['page_title'] = 'Activity Log';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->load->view('aclog', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

}

?>