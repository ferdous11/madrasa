<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {

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
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'payments';
            $data['page_title'] = 'Payment Voucher';
            $data['randomkey'] = time();
            $data['getledger'] = $this->db->query("select a.*,d.name as district_name ,gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id left join accountgroup as gn on a.accountgroupid=gn.id where a.accountgroupid in(1,4,8,9,10,11,12,15,17,18,19,5) and a.status<>0 order by a.accountgroupid asc")->result();
            $data['payments'] = $this->db->query("select p.*,u.fullname from payments as p left join alluser as u on p.user_id=u.id where p.date between '".date('Y-m-d 00:00:00')."' and '".date('Y-m-d 23:59:59')."' order by p.id desc")->result();

            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_payment group by invoiceid order by invoiceid desc")->result();
            $data['currentlist']=array();

            $this->load->view('payment_voucher', $data);


        else:
            redirect('home');
        endif;
    }

    public function temp_payment(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'payments';
            $data['page_title'] = 'Payment Voucher';
            
            $invoiceid = $this->input->post('invoiceid');
            $ledgerid = $this->input->post('ledgerid');
            $amount = str_replace( ',', '', $this->input->post('amount'));
            $description = $this->input->post('description');
            $data['randomkey']=$invoiceid;

            $datar = array(
                'invoiceid'=>$invoiceid,
                'ledgerid'=>$ledgerid,
                'amount'=>$amount,
                'description'=>$description
            );
            $this->db->insert('temp_payment', $datar);

            $data['currentlist'] = $this->db->query("select tp.*,l.ledgername,l.address,l.accountgroupid,d.name as district_name  from temp_payment as tp left join accountledger as l on tp.ledgerid=l.id left join districts as d on l.district=d.id where tp.invoiceid='$invoiceid' order by tp.invoiceid desc")->result();


            $data['getledger'] = $this->db->query("select a.*,d.name as district_name ,gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id left join accountgroup as gn on a.accountgroupid=gn.id where a.accountgroupid in(1,4,8,9,10,11,12,15,17,18,19,5) and a.status<>0 order by a.accountgroupid asc")->result();
            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_payment group by invoiceid order by invoiceid desc")->result();
 
            $data['payments'] = array();
            $this->load->view('payment_voucher', $data);
            else:
            redirect('home');
        endif;
    }

    function addpayments() {
        if ($this->session->userdata('loggedin') == 'yes'):

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
                    'user_id' => $this->session->userdata('user_id')
                );
                $this->db->insert('payments', $datalist);
                $lastid = $this->db->insert_id();
                $this->db->where('id', $lastid);
               // $this->db->update('payments', array('invoiceid' => $lastid));

                $datalist = array(
                    'voucherid' => $lastid,
                    'ledgerid' => 1,
                    'date' => $date,
                    'debit' => 0,
                    'credit' => $amount[$i],
                    'vouchertype' => 'Payment voucher',
                    'description' => $comment[$i]
                    
                );
                $this->db->insert('ledgerposting', $datalist);

                $datalist2 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => $ledgerid[$i],
                    'date' => $date,
                    'debit' => $amount[$i],
                    'credit' => 0,
                    'vouchertype' => 'Payment voucher',
                    'description' => $comment[$i]
                );
                $this->db->insert('ledgerposting', $datalist2);

              }
              $this->db->query("DELETE FROM temp_payment WHERE invoiceid='$randomkey'");
              redirect('payments');
            else:
                redirect('payments');
            endif;
        else:
            redirect('home');
        endif;
    }

    function deletepaymentvoucher($id) {
        //delete payments data
        $this->db->query("delete from payments where id = '$id'");
        $this->db->query("delete from ledgerposting where voucherid = '$id' and vouchertype='Payment voucher'");
        $this->session->set_userdata('success', "Payemnts voucher deleted successfully");
        redirect('payments');
    }

    function removedata(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
           $id = $this->input->get('id');
           $randomkey = $this->input->get('randomkey');

            $this->db->query("delete from temp_payment where id = '$id'");
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'payments';
            $data['page_title'] = 'Payment Voucher';

            $data['randomkey']=$randomkey;

            $data['currentlist'] = $this->db->query("select tp.*,l.ledgername,l.address,l.accountgroupid,d.name as district_name  from temp_payment as tp left join accountledger as l on tp.ledgerid=l.id left join districts as d on l.district=d.id where tp.invoiceid='$randomkey' order by tp.id desc")->result();


            $data['getledger'] = $this->db->query("select a.*,d.name as district_name ,gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id left join accountgroup as gn on a.accountgroupid=gn.id where  a.accountgroupid in(1,4,8,9,10,11,12,15,17,18,19,5) and a.status<>0 order by a.accountgroupid asc")->result();

            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_payment group by invoiceid order by invoiceid desc")->result();
 
            $data['payments'] = $this->db->query("select p.*,u.fullname from payments as p left join alluser as u on p.user_id=u.id where p.date between '".date('Y-m-d 00:00:00')."' and '".date('Y-m-d 23:59:59')."' order by p.id desc")->result();

            $this->load->view('payment_voucher', $data);
            else:
            redirect('home');
        endif;
        
    }

    function tempremove(){
        
        if ($this->session->userdata('loggedin') == 'yes'):
        $randomkey = $this->input->get('randomkey');
        $this->db->query("DELETE FROM temp_payment WHERE invoiceid='$randomkey'");
            redirect('payments');
        else:
            $this->load->view('login', $data);
        endif;

    }

    function showtemp(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'payments';
            $data['page_title'] = 'Payment Voucher';
           
            $invoiceid=$data['randomkey']=$randomkey = $_GET['randomkey'];
            $data['currentlist'] = $this->db->query("select tp.*,l.ledgername,l.address,l.accountgroupid,d.name as district_name  from temp_payment as tp left join accountledger as l on tp.ledgerid=l.id left join districts as d on l.district=d.id where tp.invoiceid='$invoiceid' order by tp.invoiceid desc")->result();


            $data['getledger'] = $this->db->query("select a.*,d.name as district_name ,gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id left join accountgroup as gn on a.accountgroupid=gn.id where a.accountgroupid in(1,4,8,9,10,11,12,15,17,18,19,5) and a.status<>0 order by a.accountgroupid asc")->result();
            $data['uncomlitelist'] = $this->db->query("select invoiceid,sum(amount) as tprice, count(ledgerid) as titem from temp_payment group by invoiceid order by invoiceid desc")->result();
 
            $data['payments'] = array();
            $this->load->view('payment_voucher', $data);
            else:
            redirect('home');
        endif;

    }

    function edit($id){
            $data['baseurl'] = $this->config->item('base_url');
            if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'payments';
            $data['page_title'] = 'Payment Voucher';

            $data['getledger'] = $this->db->query("select a.*,d.name as district_name ,gn.name as  groupname  from accountledger as a left join districts as d on a.district=d.id left join accountgroup as gn on a.accountgroupid=gn.id where a.accountgroupid in(1,4,8,9,10,11,12,15,17,18,19,5) and a.status<>0 order by a.accountgroupid asc")->result();

            $item = $this->db->query("SELECT * from payments where id ='$id'")->row();
            $data['received_id'] = $id;
            $data['ledgerid']= $item->ledgerid;
            $data['comment']= $item->description;
            $data['amount']= $item->amount;
            $data['invoiceid']= $item->invoiceid;
            $data['date']= $item->date;

            $this->load->view('edit_payments', $data);
        else:
            redirect('home');
        endif;
    }

    function update(){
        if ($this->session->userdata('loggedin') == 'yes'):
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
        $this->db->update('payments', $data);

        $this->db->query("Update ledgerposting set debit='$amount',date='$date',description='$description',ledgerid='$ledgerid' where voucherid='$invoiceid' and vouchertype='Payment voucher' and credit=0");
        $this->db->query("Update ledgerposting set credit='$amount',date='$date',description='$description' where voucherid='$invoiceid' and vouchertype='Payment voucher' and debit=0");
        redirect('reports/payment');


        else:
            redirect('home');
        endif;
    }

}

?>