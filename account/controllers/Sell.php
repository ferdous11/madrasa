<?php
// deff from jibon store
defined('BASEPATH') OR exit('No direct script access allowed');

class Sell extends CI_Controller {

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
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $data['activemenu'] = 'transection';
        $data['activesubmenu'] = 'sell';
        $data['page_title'] = 'Products sell';
        $data['company_id'] = $this->session->userdata('company_id');
        $data['baseurl'] = $this->config->item('base_url');
        $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$data['company_id']."' order by name asc")->result();
        $data['sub_category']='';
        $data['category_id']='';
        $data['customer_id']='';

        if($this->session->userdata('fcategory')!='true')
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id='1' order by product_name asc")->result();
        else
        $data['productlist']= array();
        $data['subCategory']= array();
        $data['getcustomer'] = $this->db->query("select al.*,ds.name as district_name from accountledger as al left join districts as ds on al.district=ds.id where al.accountgroupid = '7' and al.company_id = ".$data['company_id']." and al.status<>0 order by al.ledgername")->result();
        
        $data['randsellid'] = time();
        $data['date'] = date("Y-m-d H:i:s");
        $data['uncomlitelist'] = $this->db->query("select customer_id,date,randsellid,sum(total_price) as tprice, count(product_id) as titem from tempsell group by randsellid order by randsellid desc")->result();
        $this->load->view('sell_derectory/sellproduct', $data);

        else:
            redirect('sell/login');
        endif;
    }

    function removesell($id) {
        $temp = $this->db->query("select t.randsellid,t.date,t.customer_id,p.sub_category,p.category_id from tempsell as t left join products as p on t.product_id=p.id where t.id='$id'")->row();

        $this->db->query("delete from tempsell where id = '$id'");
        redirect('sell/currentsell?id=' . $temp->randsellid . '&sub_category=' . $temp->sub_category . '&category_id=' . $temp->category_id. '&date=' . $temp->date. '&customer_id=' . $temp->customer_id);
    }

    function savetempsell() {
        $product_id = $this->input->post('product_id');
        
        $sub_category = $this->input->post('sub_category');
        $category_id = $this->input->post('category_id');
        $customer_id = $this->input->post('customer_id');
        $comment = $this->input->post('comment');
       
        $b = str_replace( ',', '', $this->input->post('price'));
        $date = $this->input->post('date');
        $datav = array(
            'randsellid' => $this->input->post('randsellid'),
            'unit_price' => $b,
            'product_id' => $product_id,
            'qty' => $this->input->post('freeqty'),
            'total_price' => ceil($b * $this->input->post('freeqty')),
            'customer_id' => $customer_id,
            'comment' => $comment,
            'available_quantity' => $this->input->post('available_quantity'),
            'date' => $date
        );
        
        $this->db->insert('tempsell', $datav);
        redirect('sell/currentsell?id=' . $this->input->post('randsellid') . '&sub_category=' . $sub_category . '&category_id=' . $category_id. '&customer_id=' . $customer_id. '&date=' . $date);
    }

    function currentsell() {
        $data['activemenu'] = 'transection';
        $data['activesubmenu'] = 'sell';
        $data['page_title'] = 'Products sell';
        $data['baseurl'] = $this->config->item('base_url');
        $data['getcategory2'] = $this->db->query("select id,name from category where company_id ='".$this->session->userdata('company_id')."' order by name asc")->result();
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
           
            $data['company_id'] = $this->session->userdata('company_id');
            $data['randsellid'] = $this->input->get('id');
            $data['category_id'] = $this->input->get('category_id');
            $data['sub_category'] = $this->input->get('sub_category');
            $data['customer_id'] = $this->input->get('customer_id');
            $data['date'] = $this->input->get('date');

            $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$data['company_id']."' order by name asc")->result();
            $data['subCategory']= $this->db->query("select id,name from sub_category where company_id ='".$data['company_id']."' AND category_id =".$data['category_id']." order by name asc")->result();
            if($data['sub_category']==-1)
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id =".$data['category_id']. " order by product_name asc")->result();
            else    
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id =".$data['category_id']. " AND sub_category =".$data['sub_category']. " order by product_name asc")->result();
            
            $data['getcustomer'] = $this->db->query("select al.*,ds.name as district_name from accountledger as al left join districts as ds on al.district=ds.id where al.accountgroupid = '16' and al.company_id = ".$data['company_id']." and al.status<>0 order by al.ledgername")->result();
            $data['uncomlitelist'] = $this->db->query("select customer_id,date,randsellid,sum(total_price) as tprice, count(product_id) as titem from tempsell group by randsellid order by randsellid desc")->result();

            $this->load->view('sell_derectory/sellproduct', $data);
        else:
            redirect('sell/login');
        endif;
    }

    function showtemp(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $randomkey = $_GET['randomkey'];
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'sell';
            $data['page_title'] = 'Products sell';
            
            $data['getcategory2'] = $this->db->query("select id,name from category where company_id ='".$this->session->userdata('company_id')."' order by name asc")->result();
            $data['company_id'] = $this->session->userdata('company_id');
            $data['randsellid'] = $randomkey;
            $data['category_id'] = '';
            $data['sub_category'] = '';
            $tem = $this->db->query("select * from tempsell where randsellid='$randomkey'")->row();
            $data['customer_id'] = $tem->customer_id;
            $data['date'] = $tem->date;
            $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$data['company_id']."' order by name asc")->result();
            $data['subCategory']= array();
            $data['productlist']= array();
            
            
            $data['getcustomer'] = $this->db->query("select al.*,ds.name as district_name from accountledger as al left join districts as ds on al.district=ds.id where al.accountgroupid = '16' and al.company_id = ".$data['company_id']." and al.status<>0 order by al.ledgername")->result();

            $data['uncomlitelist'] = $this->db->query("select customer_id,date,randsellid,sum(total_price) as tprice, count(product_id) as titem from tempsell group by randsellid order by randsellid desc")->result();

            $this->load->view('sell_derectory/sellproduct', $data);

        else:
            $this->load->view('login', $data);
        endif;
    }

    function tempremove(){
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $randomkey = $_GET['randomkey'];
        $this->db->query("DELETE FROM tempsell WHERE randsellid='$randomkey'");
            redirect('sell');
        else:
            $this->load->view('login');
        endif;
    }

    function sellsave() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $nettotal_price = 0;
         
           
            $company_id = $this->session->userdata('company_id');
            $user_id = $this->session->userdata('user_id');
            $customer_id = $this->input->post('finalcustomer');

            $invoiceid = $this->input->post('randsellid');
           
            $comment = $this->input->post('comment');
            $total_price = str_replace( ',', '', $this->input->post('totalprice'));
            $labour_charge = str_replace( ',', '', $this->input->post('labour_charge'));
            $transport_cost = str_replace( ',', '', $this->input->post('transport_cost'));
            $discount = str_replace( ',', '', $this->input->post('discount'));
            $unit_price = $this->input->post('unit_price');
            $qry = $this->input->post('qty');
            $idlist = $this->input->post('product_id');
            $unit = $this->input->post('unit');
            $paid_amount = str_replace( ',', '', $this->input->post('paidAmount'));
            $pre_due = str_replace( ',', '', $this->input->post('preDue'));
            $due = str_replace( ',', '', $this->input->post('fTotalDue'));
            $totaldue = str_replace( ',', '', $this->input->post('totaldue'));

            $ledger = $this->db->query("select * from accountledger where id='$customer_id'")->row();

            $message = $ledger->ledgername."\n";
            
            for ($i = 0; $i < count($idlist); $i++) {

                $id = $idlist[$i]; $lastbuyprice=0; $lastquantity=0;
                $getpdetails = $this->db->query("select p.*,u.name as unitname from products as p left join product_unit as u on p.unit=u.id where p.id = '$id'")->row();
                $lastquantity = $getpdetails->available_quantity - $qry[$i];
                $tquantity= $qry[$i];
                $this->db->query("update products set available_quantity = '$lastquantity' where id = '$id'");
                $purchase = $this->db->query("select id,a_quantity,buyprice from purchase where product_id='$id' and a_quantity>0 order by id asc")->result();
                $devcomment = array();
                $profit= 0;
                foreach ($purchase as $pu){
                    if($pu->a_quantity>=$tquantity){
                        $this->db->query("update purchase set a_quantity ='".($pu->a_quantity-$tquantity)."' where id='$pu->id'");
                        $profit+=(($unit_price[$i]-$pu->buyprice) * $tquantity);
                        $devcomment[$pu->id] = $tquantity;
                        $lastquantity = $tquantity;
                        $lastbuyprice = $pu->buyprice;
                        $tquantity =0;
                        break;
                    }
                    else {
                        $this->db->query("update purchase set a_quantity =0 where id='$pu->id'");
                        $profit+=(($unit_price[$i]-$pu->buyprice) * $pu->a_quantity);
                        $devcomment[$pu->id] = $pu->a_quantity;
                        $tquantity -= $pu->a_quantity;
                    }
                }

                if($tquantity!=0){
                    $profit+=(($unit_price[$i]-$getpdetails->opening_price) * $tquantity);
                    $lastquantity = $tquantity;
                    $lastbuyprice = $getpdetails->opening_price;
                }

                if($getpdetails->warning_quantity<$getpdetails->available_quantity && $getpdetails->warning_quantity>=$getpdetails->available_quantity-$qry[$i])
                        $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
                

                $datalist = array(
                    'invoice_id' => $invoiceid,
                    'product_id' => $idlist[$i],
                    'customer_id'=> $customer_id,
                    'sellprice' =>$unit_price[$i],
                    'profit' => $profit,
                    'quantity' => $qry[$i],
                    'unit' => $unit[$i],
                    'date' => $this->input->post('finaldate'),
                    'comment' => $comment[$i],
                    'devcomment' => json_encode($devcomment),
                    'company_id' => $company_id,
                    'lastbuyprice'=> $lastbuyprice,
                    'lastquantity'=> $lastquantity,
                );
                $nettotal_price = $nettotal_price + ($unit_price[$i] * $qry[$i]);
               
                $this->db->insert('daily_sell', $datalist);

                $message = $message.$getpdetails->product_name." ".number_format($qry[$i]).$getpdetails->unitname." ".($unit_price[$i]*$qry[$i])."৳\n";
                savelog('Product sale', 'Product id ' . $idlist[$i] . ' sold successfully from IP: ' . $_SERVER['REMOTE_ADDR']);
            }
            $message = $message."মোট ".$nettotal_price."৳ ";
            $datalist2 = array(
                    'voucherid' => $invoiceid,
                    'customer_id' => $customer_id,
                    'date' => $this->input->post('finaldate'),
                    'user_id' => $this->session->userdata('user_id'),
                    'total_price' => $total_price,
                    'paid_amount' => $paid_amount,
                    'due' => $due,
                    'pre_due' => $pre_due,
                    'discount' => $discount,
                    'comment' => $this->input->post('sumComment'),
                    'company_id' => $this->session->userdata('company_id')
            );

            $this->db->insert('daily_sell_summary', $datalist2);
            $INSERT_ID = $this->db->insert_id();

            $d_ledgerid = 2;
            $datalist_payment1 = array(
                'voucherid' => $invoiceid,
                'ledgerid' => $d_ledgerid,
                'date' =>  $this->input->post('finaldate'),
                'vouchertype' => 'sales',
                'debit' => '0',
                'credit' => $total_price+$labour_charge+$transport_cost,
                'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                'user_id'=>$user_id,
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist_payment1);

            $datalist_payment2 = array(
                'voucherid' => $invoiceid,
                'ledgerid' => $customer_id,
                'date' => $this->input->post('finaldate'),
                'vouchertype' => 'sales',
                'debit' => $total_price + $labour_charge+$transport_cost,
                'credit' => '0',
                'user_id'=>$user_id,
                'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist_payment2);

            //clean temp data
            $this->db->query("delete from tempsell where randsellid = '$invoiceid'");

            if($discount>0){
                $message = $message."বাট্টা ".$discount."৳ ";
                $d_ledgerid = 5;
                $datalist_payment1 = array(
                    'voucherid' => $invoiceid,
                    'ledgerid' => $d_ledgerid,
                    'date' =>  $this->input->post('finaldate'),
                    'vouchertype' => 'Discount',
                    'debit' => $discount,
                    'credit' => '0',
                    'user_id'=>$user_id,
                    'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment1);


                $datalist_payment2 = array(
                    'voucherid' => $invoiceid,
                    'ledgerid' => $customer_id,
                    'date' => $this->input->post('finaldate'),
                    'vouchertype' => 'Discount',
                    'debit' => '0',
                    'credit' => $discount,
                    'user_id'=>$user_id,
                    'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment2);
            }

            if($paid_amount>0){

                $cashInHand = 1;
                $message = $message."জমা ".$paid_amount."৳ ";
                $datalist = array(
                    'voucherid' => $invoiceid,
                    'ledgerid' => $cashInHand,
                    'date' => $this->input->post('finaldate'),
                    'debit' => $paid_amount,
                    'credit' => 0,
                    'user_id'=>$user_id,
                    'vouchertype' => 'Received voucher',
                    'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist);
                $datalist2 = array(
                    'voucherid' => $invoiceid,
                    'ledgerid' => $customer_id,
                    'date' => $this->input->post('finaldate'),
                    'debit' => 0,
                    'credit' => $paid_amount,
                    'user_id'=>$user_id,
                    'vouchertype' => 'Received voucher',
                    'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist2);

                $datalist = array(
                    'invoiceid'=>$invoiceid,
                    'ledgerid' => $customer_id,
                    'date' => $this->input->post('finaldate'),
                    'amount' => $paid_amount,
                    'user_id'=>$user_id,
                    'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id'),
                );
                $this->db->insert('received', $datalist);

            }

            $message = $message."বাকি ".($nettotal_price-$paid_amount-$discount)."৳";
            smsWhatsappAdmin($message);

            $this->session->set_userdata('success', 'Sales completed successfully.');
            
            $this->printinvoice($invoiceid,$total_price,$discount,$labour_charge,$transport_cost,$totaldue,$paid_amount,$pre_due,$due);
        
        else:
            redirect(base_url());
        endif;
    }

    function getcustomerdetails() {
        $ledgerid = $this->input->post('ledgerid');
        $ledgerdata = $this->db->query("select * from accountledger where id = '$ledgerid'")->row();
        
        $lPosting = $this->db->query("select sum(debit) as aDebit,sum(credit) as aCredit from ledgerposting where ledgerid='$ledgerid'")->row();

        $debit = $ledgerdata->debit+$lPosting->aDebit;
        $credit = $ledgerdata->credit+$lPosting->aCredit;
        $ldata = array();

        if ($ledgerdata):
            $ldata = array(
                'mobile' => $ledgerdata->mobile,
                'name' => $ledgerdata->ledgername,
                'address' => $ledgerdata->address,
                'due' => $debit-$credit
            );
        else:
            $ldata = array(
                'mobile' => '',
                'name' => '',
                'address' => '',
                'due' => ''
            );
        endif;
        echo json_encode($ldata);
    }

    function printinvoice($invoiceid,$totalPrice=0,$discount=0,$labour_charge=0,$transport_cost=0,$grossTotal=0,$paidAmount=0,$preDue=0,$fTotalDue=0) {
        $data['baseurl'] = $this->config->item('base_url');
      if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellhistory';
        $company_id = $this->session->userdata('company_id');
        $data['company'] = $this->db->query("select * from company where company_id = '$company_id'")->row();
        $data['invoicedata'] = $this->db->query("select d.*,p.product_name ,u.name as unit_name from daily_sell as d left join products as p on d.product_id=p.id left join product_unit as u on d.unit=u.id where d.invoice_id = '$invoiceid' AND d.company_id = '$company_id'")->result();

        $data['voicedata'] = $this->db->query("select d.*,l.ledgername as customer_name,l.father_name,l.mobile,l.address,di.name as district_name,ui.fullname from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as ui on d.user_id=ui.id where d.voucherid = '$invoiceid' AND d.company_id = '$company_id'")->row();

        $data['totalPrice'] =($totalPrice);
       
        $data['discount'] =($discount);
        $data['labour_charge'] =($labour_charge);
        $data['transport_cost'] =($transport_cost);

        $data['grossTotal'] =($grossTotal);
        $data['inword']= convert_number(round($grossTotal));
        $data['paidAmount'] =($paidAmount);
        $data['preDue'] =($preDue);
        $data['fTotalDue'] =($fTotalDue);
        $data['baseurl'] = $this->config->item('base_url');
        $this->load->view('sell_derectory/printinvoice', $data);

       else:
        redirect('sell/login');
       endif;
    }

    function sell_return() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['company_id'] = $this->session->userdata('company_id');
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'sell_return';
            $data['page_title'] = 'Sales Return';
            $data['baseurl'] = $this->config->item('base_url');
            $data['voucherid'] = '';
            $data['summary'] = '';                            
            $data['invoiceidp'] = $this->db->where('company_id', $data['company_id'])->select('voucherid')->get('daily_sell_summary')->result();

            $data['selldata'] = array();
            $this->load->view('sell_derectory/sellreturn', $data);
        else:
            redirect('sell/login');
        endif;
    }

    function sell_return_view() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'sell_return';
            $data['page_title'] = 'Sales Return';
            $data['baseurl'] = $this->config->item('base_url');

            $voucherid = $this->input->post('invoicenumber');
            $company_id = $this->session->userdata('company_id');
            $data['voucherid'] = $voucherid;

            $data['invoiceidp'] = $this->db->where('company_id', $company_id)->select('voucherid')->get('daily_sell_summary')->result();

            $data['selldata'] = $this->db->query("select d.*,p.product_name,u.name from daily_sell as d left join products as p on d.product_id=p.id left join product_unit as u on d.unit=u.id where d.invoice_id = '$voucherid' AND d.company_id = '$company_id' ")->result();

            $data['summary'] = $this->db->query("select d.*,l.ledgername from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id where d.voucherid = '$voucherid' AND d.company_id = '$company_id' ")->row();
            $this->load->view('sell_derectory/sellreturn', $data);
        else:
            redirect('sell/login');
        endif;
    }

    function updatesell() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'sell_return';
            $data['page_title'] = 'Sales Return';

            $data['baseurl'] = $this->config->item('base_url');
            $invoiceid = ($this->input->post('invoiceid'));
            $id = ($this->input->post('sellid'));
            $sellprice = ($this->input->post('sellprice'));
            $qty = ($this->input->post('rquantity'));
            $user_id = $this->session->userdata('user_id');


            $lastselldata = $this->db->get_where('daily_sell', array('id' => $id))->row();
            $totalPrePrice = $lastselldata->sellprice * $lastselldata->quantity;

            $this->db->query("update products set available_quantity=available_quantity+'$qty' where id=".$lastselldata->product_id);
            $product_name = $this->db->get_where('Products', array('id' => $lastselldata->product_id))->row()->product_name;
            $customer_id = $lastselldata->customer_id;

            $CurentPrePrice = $sellprice * $qty;
            $currentqty = $lastselldata->quantity - $qty;
            $this->db->query("Update daily_sell_summary set total_price= total_price -'$CurentPrePrice',due=due-'$CurentPrePrice' where voucherid='$invoiceid'");
            $difference = $totalPrePrice - $CurentPrePrice;

            if($qty==$lastselldata->quantity)
                $this->db->query("delete from daily_sell where id='$id'");
            else{

                $dattt = array(
                    'quantity' => $currentqty,
                );
                $this->db->where('id', $id);
                $this->db->update('daily_sell', $dattt);
            }
            $d_ledgerid = 2;

            $datalist_payment1 = array(
                'voucherid' => $invoiceid,
                'ledgerid' => $d_ledgerid,
                'date' => date("Y-m-d H:i:s"),
                'vouchertype' => 'direct sales return',
                'credit' => '0',
                'debit' => $CurentPrePrice,
                'user_id' => $user_id,
                'description' => "Product Name: ".$product_name." Quantity:".$qty,
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist_payment1);


            $datalist_payment2 = array(
                'voucherid' => $invoiceid,
                'ledgerid' => $customer_id,
                'date' => date("Y-m-d H:i:s"),
                'vouchertype' => 'direct sales return',
                'credit' => $CurentPrePrice,
                'debit' =>'0',
                'user_id' => $user_id,
                'description' => "Product Name: ".$product_name." Quantity:".$qty,
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist_payment2);


            $datalistsell = array(
                'invoice_id' => $invoiceid,
                'product_id' => $lastselldata->product_id,
                'quantity' => $qty,
                'date' => date('Y-m-d H:i:s'),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('sell_return', $datalistsell);
            savelog('Sales return or update', 'Product ID: ' . $lastselldata->product_id . ' updated from IP:' . $_SERVER['REMOTE_ADDR'] . ' Browser: ' . $_SERVER['HTTP_USER_AGENT']);
            $company_id = $this->session->userdata('company_id');
            $data['voucherid'] = $invoiceid;
            
            $data['invoiceidp'] = $this->db->where('company_id', $company_id)->select('voucherid')->get('daily_sell_summary')->result();

            $this->session->set_userdata('success', 'Sales updated successfully');
            $data['selldata'] = $this->db->query("select d.*,p.product_name,u.name from daily_sell as d left join products as p on d.product_id=p.id left join product_unit as u on d.unit=u.id where d.invoice_id = '$invoiceid' AND d.company_id = '$company_id' ")->result();

            $data['summary'] = $this->db->query("select d.*,l.ledgername from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id where d.voucherid = '$invoiceid' AND d.company_id = '$company_id' ")->row();

            $this->load->view('sell_derectory/sellreturn', $data);
        else:
            redirect(base_url());
        endif;
    }

    function getProduct() {
        $company_id = $this->session->userdata('company_id');
        $class_id = $this->input->post('class_id');
            $plist = $this->db->query("select id,product_name from products where class_id='$class_id' AND company_id = '$company_id' AND status='1'")->result();
        echo json_encode($plist);
    }
    
    function getCustomer(){
        $company_id = $this->session->userdata('company_id');
        $getcustomer= $this->db->query("select id,ledgername from accountledger where accountgroupid = '16' and company_id = ".$company_id." order by ledgername")->result();
        echo json_encode($getcustomer);
    }

    /////-----------ajax-------------
    public function updatetempsell(){
        $tempid = $this->input->post('tempid');
        $comment = $this->input->post('comment');
        $price = $this->input->post('price');
        $quantity = $this->input->post('quantity');
        

        $this->db->query("update tempsell set unit_price='$price', qty='$quantity', comment='$comment', total_price='$quantity*$price' where id='$tempid'");

        echo "ok";
    }
}



?>