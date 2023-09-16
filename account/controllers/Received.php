<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Received extends CI_Controller {

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
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'received';
            $data['page_title'] = 'Received history';
            $data['baseurl'] = $this->config->item('base_url');   
            $comid = $this->session->userdata('company_id');
            $data['randomkey'] = time();
            $data['getledger'] = $this->db->query("select a.*,d.name as district_name, gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id  left join accountgroup as gn on a.accountgroupid=gn.id where a.company_id= '$comid' and a.accountgroupid in(1,16,19,3,12,5) and a.status<>0 order by a.id asc")->result();
 
            $data['payments'] = $this->db->query("select p.*,u.fullname from received as p left join alluser as u on p.user_id=u.id where p.company_id = '$comid' and p.date between '".date('Y-m-d 00:00:00')."' and '".date('Y-m-d 23:59:59')."' order by p.id desc")->result();

            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_received group by invoiceid order by invoiceid desc")->result();
            $data['currentlist']=array();

            $this->load->view('receipt_voucher', $data);
             else:
            redirect('home');
        endif;
    }

    public function temp_payment(){
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'received';
            $data['page_title'] = 'Received history';
            $data['baseurl'] = $this->config->item('base_url');
            $comid = $this->session->userdata('company_id');
            $invoiceid = $this->input->post('invoiceid');
            $ledgerid = $this->input->post('ledgerid');
            $amount = str_replace( ',', '', $this->input->post('amount'));
            $description = $this->input->post('description');
            $data['randomkey']=$invoiceid;

            $datar = array(
                'invoiceid'=>$invoiceid,
                'ledgerid'=>$ledgerid,
                'amount'=>$amount,
                'description'=>$description,
                'company_id'=>$comid
            );
            $this->db->insert('temp_received', $datar);

            $data['currentlist'] = $this->db->query("select tp.*,l.ledgername,l.address,l.accountgroupid,d.name as district_name  from temp_received as tp left join accountledger as l on tp.ledgerid=l.id left join districts as d on l.district=d.id where tp.invoiceid='$invoiceid' order by tp.id desc")->result();
            
            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_received group by invoiceid order by invoiceid desc")->result();
            $data['getledger'] = $this->db->query("select a.*,d.name as district_name, gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id  left join accountgroup as gn on a.accountgroupid=gn.id where a.company_id= '$comid' and a.accountgroupid in(1,16,19,3,12,5) and a.status<>0 order by a.id asc")->result();
 
            $data['payments'] = array();
            $this->load->view('receipt_voucher', $data);
            else:
            redirect('home');
        endif;
    }

    function addpayments() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $comment =  $this->input->post('comment');
            $amount =  $this->input->post('amount');
            $ledgerid =  $this->input->post('ledgerid');
            $date =  $this->input->post('date');
            $randomkey =  $this->input->post('randomkey');
            if(sizeof($ledgerid)>0):
              for($i=0;$i<sizeof($ledgerid);$i++){

                $datalist = array(
                    'invoiceid'=>$randomkey,
                    'ledgerid' => $ledgerid[$i],
                    'date' => $date,
                    'amount' => $amount[$i],
                    'description' => $comment[$i],
                    'company_id' => $this->session->userdata('company_id'),
                    'user_id' => $this->session->userdata('user_id')

                );
                $this->db->insert('received', $datalist);
                $lastid = $this->db->insert_id();
                $this->db->where('id', $lastid);
               // $this->db->update('payments', array('invoiceid' => $lastid));

                $datalist = array(
                    'voucherid' => $lastid,
                    'ledgerid' => 1,
                    'date' => $date,
                    'debit' => $amount[$i],
                    'credit' => 0,
                    'vouchertype' => 'Received voucher',
                    'description' => $comment[$i],
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist);

                $datalist2 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => $ledgerid[$i],
                    'date' => $date,
                    'debit' => 0,
                    'credit' => $amount[$i],
                    'vouchertype' => 'Received voucher',
                    'description' => $comment[$i],
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist2);

              }
              $this->db->query("DELETE FROM temp_received WHERE invoiceid='$randomkey'");
              redirect('received');
            else:
                redirect('received');
            endif;
        else:
            redirect('home');
        endif;
    }

    function deletereceiptvoucher($id) {
        //delete receipt data
        $this->db->query("delete from received where id = '$id'");
        $this->db->query("delete from ledgerposting where voucherid = '$id'  and vouchertype='Received voucher'");
        $this->session->set_userdata('success', "Receipt voucher deleted successfully");
        redirect('received');
    }

    function removedata(){
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
           $id = $this->input->get('id');
           $randomkey = $this->input->get('randomkey');

            $this->db->query("delete from temp_received where id = '$id'");
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'received';
            $data['page_title'] = 'Received history';
            $data['baseurl'] = $this->config->item('base_url');
            $comid = $this->session->userdata('company_id');

            $data['randomkey']=$randomkey;

            $data['currentlist'] = $this->db->query("select tp.*,l.ledgername,l.address,l.accountgroupid,d.name as district_name  from temp_received as tp left join accountledger as l on tp.ledgerid=l.id left join districts as d on l.district=d.id where tp.invoiceid='$randomkey' order by tp.id desc")->result();


            $data['getledger'] = $this->db->query("select a.*,d.name as district_name, gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id  left join accountgroup as gn on a.accountgroupid=gn.id where a.company_id= '$comid' and a.accountgroupid in(1,16,19,3,12,5) and a.status<>0 order by a.id asc")->result();

            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_received group by invoiceid order by invoiceid desc")->result();
 
            $data['payments'] = $this->db->query("select * from received where company_id = '$comid' order by id desc")->result();
            $this->load->view('receipt_voucher', $data);
            else:
            redirect('home');
        endif;
    }

    function tempremove(){
        
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $randomkey = $this->input->get('randomkey');
        $this->db->query("DELETE FROM temp_received WHERE invoiceid='$randomkey'");
            redirect('received');
        else:
            $this->load->view('login', $data);
        endif;

    }

    function showtemp(){
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'received';
            $data['page_title'] = 'Received history';
            $data['baseurl'] = $this->config->item('base_url');
            $comid = $this->session->userdata('company_id');
           
            $randomkey = $data['randomkey']=$randomkey = $_GET['randomkey'];
            $data['currentlist'] = $this->db->query("select tp.*,l.ledgername,l.address,l.accountgroupid,d.name as district_name  from temp_received as tp left join accountledger as l on tp.ledgerid=l.id left join districts as d on l.district=d.id where tp.invoiceid='$randomkey' order by tp.id desc")->result();

            $data['getledger'] = $this->db->query("select a.*,d.name as district_name, gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id  left join accountgroup as gn on a.accountgroupid=gn.id where a.company_id= '$comid' and a.accountgroupid in(1,16,19,3,12,5) and a.status<>0 order by a.id asc")->result();
            
            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_received group by invoiceid order by invoiceid desc")->result();
 
            $data['payments'] = array();
            $this->load->view('receipt_voucher', $data);
            else:
            redirect('home');
        endif;

    }

    function edit($id){
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'received';
            $data['page_title'] = 'Received history';
            $data['baseurl'] = $this->config->item('base_url');
            $comid = $this->session->userdata('company_id');

            $data['getledger'] = $this->db->query("select a.*,d.name as district_name, gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id  left join accountgroup as gn on a.accountgroupid=gn.id where a.company_id= '$comid' and a.accountgroupid in(1,16,19,3,12,5) and a.status<>0 order by a.id asc")->result();

            $item = $this->db->query("SELECT * from received where id ='$id'")->row();
            $data['received_id'] = $id;
            $data['ledgerid']= $item->ledgerid;
            $data['comment']= $item->description;
            $data['amount']= $item->amount;
            $data['invoiceid']= $item->invoiceid;
            $data['date']= $item->date;

            $this->load->view('edit_received', $data);
        else:
            redirect('home');
        endif;
    }

    function update(){
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
           $ledgerid =$this->input->post('ledgerid');
           $description =$this->input->post('description');
           $amount = str_replace( ',', '', $this->input->post('amount'));
           $date =$this->input->post('date');
           $invoiceid =$this->input->post('invoiceid');

        $data = array(
                'ledgerid' => $ledgerid,
                'description' => $description,
                'amount' => $amount,
                'date' => $date,
                'user_id' => $this->session->userdata("user_id")
        );

        $this->db->where('id', $invoiceid);
        $this->db->update('received', $data);

        $this->db->query("Update ledgerposting set debit='$amount',date='$date',description='$description' where voucherid='$invoiceid' and vouchertype='Received voucher' and credit=0");
        $this->db->query("Update ledgerposting set credit='$amount',date='$date',description='$description',ledgerid='$ledgerid' where voucherid='$invoiceid' and vouchertype='Received voucher' and debit=0");
        redirect('reports/received');


        else:
            redirect('home');
        endif;
    }

}

?>