<?php

class District extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('common_helper');
    }

    public function get_district_name($id)
        {
            $query = $this->db->get_where('districts', array('id' => $id))->row();
                return $query->name();
        }



}

?>