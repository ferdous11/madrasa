<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rolemanagement extends CI_Controller {

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
        $data['activesubmenu'] = 'rolemanage';
        $data['page_title'] = 'Role management';
        $data['baseurl'] = $this->config->item('base_url');
        $data['companyname'] = '';
        $data['rolle'] = '';
        $data['select_menu'] = array();
        $data['adminlist'] = $this->db->query("select * from role where company_id=".$this->session->userdata('company_id'))->result();
        $data['menuArray'] = $this->db->query("select * from menu")->result();

        $data['menuArrayMaster'] = $this->db->query("select * from menu where main_menu='Master'")->result();
        $data['menuArrayAccountStatement'] = $this->db->query("select * from menu where main_menu='Account Statement'")->result();
        $data['menuArrayReports'] = $this->db->query("select * from menu where main_menu='Reports'")->result();
        $data['menuArraySettings'] = $this->db->query("select * from menu where main_menu='Settings'")->result();
        $data['menuArrayTransection'] = $this->db->query("select * from menu where main_menu='Transection'")->result();

        $data['menuArrayEmployee'] = $this->db->query("select * from menu where main_menu='Employee'")->result();        

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->load->view('rolemanage', $data);
        else:
            redirect(base_url());
        endif;
    }

    function saveusermanu() {
        $menus = $this->input->post('menu');
        $companyname = $this->input->post('companyname');
        $rolle = $this->input->post('rolle');


        $menulist = '';
        $idlist = '';
        
        $temp = $this->db->query("select * from menu")->result();

        foreach ($temp as $key) {
             $fer[$key->link]=$key->id;
        }


        
        for ($i = 0; $i < count($menus); $i++):
            $menulist = $menulist . $menus[$i] . ',';
            $idlist = $idlist . $fer[$menus[$i]] . ',';
        endfor;
        
        $allowmenu = rtrim($menulist, ',');
        $idlist = rtrim($idlist, ',');

        $this->db->query("update role set menu_link='$allowmenu',menu_id='$idlist' where id=$rolle ");

        redirect('rolemanagement/selfmenu?uid=' . $companyname . '&rolle=' . $rolle);
    }

    function selfmenu() {
        $companyname = urldecode($_GET['uid']);
        $role = $_GET['rolle'];
        $data['baseurl'] = $this->config->item('base_url');

        $data['title'] = "Role management";
        $data['activemenu'] = 'settings';
        $data['activesubmenu'] = 'rolemanage';
        $data['companyname'] = $companyname;
        $data['rolle'] = $role;
        $temp = $this->db->get_where('role',array('id'=>$role))->row()->menu_link;
        $data['select_menu'] = explode(',', $temp);
        $data['adminlist'] = $this->db->query("select * from role where company_id=".$this->session->userdata('company_id'))->result();
        
        $data['menuArrayMaster'] = $this->db->query("select * from menu where main_menu='Master'")->result();
        $data['menuArrayAccountStatement'] = $this->db->query("select * from menu where main_menu='Account Statement'")->result();
        $data['menuArrayReports'] = $this->db->query("select * from menu where main_menu='Reports'")->result();
        $data['menuArraySettings'] = $this->db->query("select * from menu where main_menu='Settings'")->result();
        $data['menuArrayEmployee'] = $this->db->query("select * from menu where main_menu='Employee'")->result(); 
        $data['menuArrayTransection'] = $this->db->query("select * from menu where main_menu='Transection'")->result();
        $this->load->view('rolemanage', $data);
    }

    function addnewrole(){

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            

            $dbdata = array(
            'title' => $this->input->post('title'),
            'company_id' => $this->session->userdata('company_id')
            );

            $this->db->insert('role',$dbdata);

            redirect('rolemanagement');

        else:
            redirect(base_url());
        endif;
    }

}

?>