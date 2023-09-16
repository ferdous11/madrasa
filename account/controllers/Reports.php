<?php
// deff from jibon store
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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

    //----------------------Sales Return Report And Update Return---------------------//

    function salesreturn() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'sellreturn';
            $data['page_title'] = 'Sales return history';
            $data['baseurl'] = $this->config->item('base_url');
            $data['sdate'] = date('Y-m-d');
            $data['edate'] = date('Y-m-d');
            $sdate = date('Y-m-d') . ' 00:00:00';
            $edate = date('Y-m-d') . ' 23:59:59';
            $comid = $this->session->userdata('company_id');
            $data['selldata'] = $this->db->query("select p.*,l.ledgername,l.address,l.mobile,l.father_name,d.name as district_name,u.fullname from sell_return_summary as p left join accountledger as l on p.customer_id=l.id left join districts as d on l.district=d.id left join alluser as u on p.user_id=u.id where p.date between '$sdate' and '$edate'")->result();
            $this->load->view('report_derectory/sellreturn_log', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function sellreturnhistory() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'sellreturn';
            $data['page_title'] = 'Sales return history';
            $data['baseurl'] = $this->config->item('base_url');
            $data['sdate'] = $this->input->post('sdate');
            $data['edate'] = $this->input->post('edate');
            $sdate = $this->input->post('sdate') . ' 00:00:00';
            $edate = $this->input->post('edate') . ' 23:59:59';
            $comid = $this->session->userdata('company_id');
            $data['selldata'] = $this->db->query("select p.*,l.ledgername,l.address,l.father_name,l.mobile,d.name as district_name,u.fullname from sell_return_summary as p left join accountledger as l on p.customer_id=l.id left join districts as d on l.district=d.id left join alluser as u on p.user_id=u.id where p.date between '$sdate' and '$edate'")->result();
            $this->load->view('report_derectory/sellreturn_log', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function showsellreturn($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
      if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellreturn';
        $company_id = $this->session->userdata('company_id');
        $data['company'] = $this->db->query("select * from company where company_id = '$company_id'")->row();
        $data['baseurl'] = $this->config->item('base_url');
        $data['invoicedata'] = $this->db->query("select d.*,p.product_name ,u.name as unit_name from sell_return as d left join products as p on d.product_id=p.id left join product_unit as u on d.unit=u.id where d.invoice_id = '$invoiceid' AND d.company_id = '$company_id'")->result();

        $data['voicedata'] = $this->db->query("select d.*,l.ledgername as customer_name,l.father_name,l.mobile,l.address,di.name as district_name,u.fullname as user_name from sell_return_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as u on d.user_id=u.id where d.invoiceid = '$invoiceid' AND d.company_id = '$company_id'")->row();

        // Current Due Calculation
        $ledgerid= $data['voicedata']->customer_id;
        $ledgerdata = $this->db->query("select * from accountledger where id = '$ledgerid'")->row();
        $lPosting = $this->db->query("select sum(debit) as aDebit,sum(credit) as aCredit from ledgerposting where ledgerid='$ledgerid'")->row();
        $debit = $ledgerdata->debit+$lPosting->aDebit;
        $credit = $ledgerdata->credit+$lPosting->aCredit;
        $data['current_due']=$debit-$credit;
        // ---------------------

        $data['totalPrice'] =$data['voicedata']->total_purchase;
        $data['payment'] =$data['voicedata']->payment;
        $data['discount'] =$data['voicedata']->discount;

        $data['grossTotal'] =$data['totalPrice'] - $data['discount'];
        $data['inword']= convert_number(round($data['grossTotal']));

        $data['backtoreport']=true;
        $this->load->view('report_derectory/showsellreturn', $data);
       else:
        $this->load->view('login', $data);
       endif;
    }

    function editsellreturn($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
        $data['sellsummary']=$this->db->query("select * from sell_return_summary where invoiceid='$invoiceid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($data['sellsummary']->date > $datef." 00:00:00" && $data['sellsummary']->date < $datef." 23:59:59" ))):
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellreturn';
        $company_id = $this->session->userdata('company_id');
        $data['baseurl'] = $this->config->item('base_url');
        $data['randsellid']=$invoiceid;
        $data['category_id']=1;
        $data['sub_category']=1;
        
        $data['customer'] = $data['sellsummary']->customer_id;
        $data['productlist']=$this->db->query("select id,product_name,category_id,sub_category from products where company_id ='".$company_id."' order by product_name asc")->result();
        $data['allcategory']=$this->db->query("select * from category")->result();
        $data['subCategory']=$this->db->query("select * from sub_category")->result();
        $data['selldata'] = $this->db->query("select s.*,p.product_name as name,p.category_id,p.sub_category,p.unit as unit_id ,u.name as unit_name from sell_return as s left join products as p on s.product_id=p.id left join product_unit as u on p.unit=u.id where s.invoice_id='$invoiceid' order by id desc")->result();
        //$data['getcustomer'] = $this->db->query("select l.id,l.ledgername,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid = '16' and l.company_id = '$company_id' order by l.ledgername")->result();
        $data['supplierinfo'] = $this->db->query("select l.id,l.ledgername,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id = '".$data['customer']."' ")->row();
            $this->load->view('report_derectory/editsellreturn',$data);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    public function spsredit(){
        $data['baseurl'] = $this->config->item('base_url');
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select customer_id,id,date from sell_return_summary where invoiceid='$randsellid'")->row();
        $datef= date('Y-m-d');

        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):
        $pquantity=$this->input->post("pquantity");
        $quantity=$this->input->post("quantity");
        $pprice=$this->input->post("pprice");
        $sellprice=$this->input->post("sellprice");
        $pproduct_id=$this->input->post("pproduct_id");
        $product_id=$this->input->post("product_id");
        
        
        $dailysellid = $this->input->post("dailysellid");
        $category_id = $this->input->post("category_id");

        $pcategory_id = $this->input->post("pcategory_id");
       
    
        $product = $this->db->query("select * from products where id='$product_id'")->row();
        $pproduct = $this->db->query("select * from products where id='$pproduct_id'")->row();

        
            $this->db->query("update products set available_quantity = available_quantity-'$pquantity' where id = '$pproduct_id'");
            $dailysell = $this->db->query("select * from sell_return where id ='$dailysellid'")->row();
            $tarray = json_decode($dailysell->devcomment);
            if(!empty($tarray)){
                foreach($tarray as $id => $row) {
                    $this->db->query("update purchase set a_quantity=(a_quantity-'".$row."') where id='$id'");
                } 
            }
            //Notificcation increase
            if($pproduct->warning_quantity < $pproduct->available_quantity && $pproduct->warning_quantity >= $pproduct->available_quantity - $pquantity)
                         $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
        

        $devcomment = array();$profit=$lastbuyprice=$lastquantity=0;
       
            $updateqty = $this->db->query("update products set available_quantity = available_quantity+'$quantity' where id = '$product_id'");


                $cp=$this->db->query("select * from daily_sell where product_id='$product_id' and customer_id='$dailys->customer_id' order by id desc limit 3")->result();
                $devcomment = array();
                $profit= 0;
                $tquantity= $quantity;
                if(!empty($cp)){
                    foreach ($cp as $pu){
                        $tarray = json_decode($pu->devcomment);
                        
                        if(!empty($tarray)){
                            foreach($tarray as $id => $row) {
                                $pu= $this->db->query("Select * from purchase where id='$id'")->row();
                                if($pu->a_quantity+$tquantity<=$pu->quantity){
                                    $this->db->query("update purchase set a_quantity=(a_quantity+'".$tquantity."') where id='$id'");
                                    $profit+=($sellprice-$pu->buyprice)*$tquantity;
                                    $devcomment[$pu->id] = $tquantity;
                                    $lastbuyprice = $pu->buyprice;
                                    $plastquantity = $tquantity;
                                    $tquantity=0;
                                    break;
                                }
                                else{
                                    $def = $pu->quantity-$pu->a_quantity;
                                    $tquantity-=$def;
                                    $lastbuyprice = $pu->buyprice;
                                    $plastquantity = $def;
                                    $this->db->query("update purchase set a_quantity=quantity where id='$id'");
                                    $profit+=($sellprice-$pu->buyprice)*$def;
                                    $devcomment[$pu->id] = $def;
                                }
                            } 
                        }
                        if($tquantity==0)
                            break;
                    }    
                }
                if($tquantity!=0){
                    $profit+=(($sellprice-$product->opening_price) * $tquantity);
                    $lastquantity = $tquantity;
                    $lastbuyprice = $product->opening_price;
                }
            
            //Notificcation decrease
            if($product->warning_quantity>=$product->available_quantity && $product->warning_quantity<$product->available_quantity+$quantity)
                         $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
        

        $devcomment = json_encode($devcomment);
        $this->db->query("Update sell_return set product_id='$product_id',return_price='$sellprice',quantity='$quantity',unit='$product->unit',profit='$profit',lastbuyprice='$lastbuyprice',lastquantity='$lastquantity',devcomment	='$devcomment' where id='$dailysellid'");

        $p = ($sellprice * $quantity) - ($pprice * $pquantity);
        $this->db->query("Update sell_return_summary set total_purchase=total_purchase+'$p' where invoiceid='$randsellid'");
        
        $this->db->query("Update ledgerposting set credit=credit+'$p' where voucherid='$randsellid' and ledgerid='$dailys->customer_id' and vouchertype='sales return'");

        $this->db->query("Update ledgerposting set debit=debit+'$p' where voucherid='$randsellid' and vouchertype='sales return' and ledgerid='2'");

        savelog("Update Sales Return Product", " voucher= $randsellid".",purchase_return Id=$dailysellid".",pquantity=$pquantity".",pprice=$pprice".",pproduct_id=$pproduct_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        $this->editsellreturn($randsellid);

        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    public function updatesellreturnedit(){
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select id,date from sell_return_summary where invoiceid='$randsellid'")->row();
        $datef= date('Y-m-d');
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):

            $customer_id = $this->input->post('pcustomer_id');
            $pcustomer_id = $this->input->post('pcustomer_id');
            $date = $this->input->post('date');
            $pdate = $this->input->post('pdate');
            $discount = $this->input->post('discount');
            $pdiscount = $this->input->post('pdiscount');
            
            $totalprice = $this->input->post('totalprice');
            $payment = $this->input->post('payment');


            
        // ----update sellreturn_summary
            $this->db->query("Update sell_return set customer_id='$customer_id' ,date='$date' Where  invoice_id='$randsellid'");

            $this->db->query("Update sell_return_summary set customer_id=$customer_id,date='$date',total_purchase = $totalprice,discount = $discount,payment=$payment where invoiceid='$randsellid'");

            
            $d = $this->db->query("select count(id)as d  from ledgerposting where voucherid='$randsellid' and vouchertype='S R Discount'")->row()->d;

            $p = $this->db->query("select count(id)as d  from payments where invoiceid='$randsellid'")->row()->d;

        //-----Update ledgerposting for sell return
        
        $this->db->query("Update ledgerposting set date='$date',ledgerid='$customer_id' where voucherid='$randsellid' and ledgerid='$pcustomer_id' and vouchertype='sales return'");
        $this->db->query("Update ledgerposting set date='$date' where voucherid='$randsellid' and ledgerid='2' and vouchertype='sales return'");
            

        //-----Update ledgerposting for discount
            if($d==2){
                $this->db->query("Update ledgerposting set credit='$discount',date='$date' where voucherid='$randsellid' and ledgerid='5' and vouchertype='S R Discount'");
                $this->db->query("Update ledgerposting set debit='$discount',date='$date',ledgerid='$customer_id' where voucherid='$randsellid' and ledgerid='$pcustomer_id' and vouchertype='S R Discount'");
                }
            else if($discount>0){
                $d_ledgerid = 5;
                $datalist_payment1 = array(
                    'voucherid' => $randsellid,
                    'ledgerid' => 5,
                    'date' =>  $date,
                    'vouchertype' => 'S R Discount',
                    'debit' => 0,
                    'credit' => $discount,
                    'description' => "Sr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment1);


                $datalist_payment2 = array(
                    'voucherid' => $randsellid,
                    'ledgerid' => $customer_id,
                    'date' => $date,
                    'vouchertype' => 'S R Discount',
                    'debit' => $discount,
                    'credit' => 0,
                    'description' => "Sr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment2);
            }
            if($p==1){
                $receivedid = $this->db->query("select id from payments where invoiceid='$randsellid'")->row()->id;
                $this->db->query("Update ledgerposting set credit='$payment',date='$date' where voucherid='$receivedid' and ledgerid='1' and vouchertype='Payment voucher'");
                $this->db->query("Update ledgerposting set debit='$payment',date='$date',ledgerid='$customer_id' where voucherid='$receivedid' and ledgerid='$pcustomer_id' and vouchertype='Payment voucher'");
                $this->db->query("update payments set amount='$payment',ledgerid='$customer_id',date='$date' where invoiceid='$randsellid'");
                }
            else if($payment>0){

                $datalist = array(
                    'invoiceid'=>$randsellid,
                    'ledgerid' => $customer_id,
                    'date' => $date,
                    'amount' => $payment,
                    'description' => "Sr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id'),
                    'user_id' => $this->session->userdata('user_id')
                );
                $this->db->insert('payments', $datalist);
                $lastid = $this->db->insert_id();
                $this->db->where('id', $lastid);

                
                $d_ledgerid = 1;
                $datalist_payment1 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => 1,
                    'date' =>  $date,
                    'vouchertype' => 'Payment voucher',
                    'debit' => '0',
                    'credit' => $payment,
                    'description' => "Sr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment1);


                $datalist_payment2 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => $customer_id,
                    'date' => $date,
                    'vouchertype' => 'Payment voucher',
                    'credit' => '0',
                    'debit' => $payment,
                    'description' => "Sr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment2);
            }
        //-----Update ledgerposting for labour_charge 
        
            $this->session->set_userdata("success","Sales Return is Updated successfully");
            savelog("Update seles Return ", " voucher= $randsellid".",pcustomer_id=$pcustomer_id".",pdate=$pdate".",pdiscount=$pdiscount"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->editsellreturn($randsellid);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    public function deletesellreturn($voucherid){
        $data['baseurl'] = $this->config->item('base_url');
        $dailys = $this->db->query("select * from sell_return_summary where invoiceid='$voucherid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):

        $dailysell =$this->db->query("select s.*,p.category_id,p.available_quantity,p.warning_quantity from sell_return as s left join products as p on s.product_id=p.id where s.invoice_id='$voucherid'")->result();

        foreach ($dailysell as $key ) {
            $tarray = json_decode($key->devcomment);
            if(!empty($tarray)){
                foreach($tarray as $id => $row) {
                    $this->db->query("update purchase set a_quantity=(a_quantity-'".$row."') where id='$id'");
                } 
            }
            
            $updateqty = $this->db->query("update products set available_quantity = available_quantity-'$key->quantity' where id = '$key->product_id'");
            //Notificcation increase
            if($key->warning_quantity<$key->available_quantity && $key->warning_quantity>=$key->available_quantity-$key->quantity)
                $this->session->set_userdata('notification',$this->session->userdata('notification')+1);

            

            savelog("Delete Sales product", " voucher= $key->invoice_id".",sell_return Id=$key->id".",quantity=$key->quantity".",price=$key->return_price".",product_id=$key->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        }
        $this->db->query("delete from sell_return where invoice_id='$voucherid'");
        

        
        $this->db->query("delete from sell_return_summary where invoiceid='$voucherid'");

        $this->db->query("Delete from ledgerposting where voucherid='$voucherid' and (vouchertype='sales return' or vouchertype='S R Discount')");
        $dpayment = $this->db->query("select id from payments where invoiceid='$voucherid'")->row();
        if(isset($dpayment)){
            $this->db->query("delete from payments where invoiceid='$voucherid'");
            $this->db->query("Delete from ledgerposting where voucherid='$dpayment->id' and vouchertype='Payment voucher'");
        }

        savelog("Delete Sales Return", " voucher= $voucherid".",pcustomer_id=$dailys->customer_id".",pdate=$dailys->date".",pdiscount=$dailys->discount".",total_price=$dailys->total_purchase"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->salesreturn();
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    public function deletesellse($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
        $dailysell =$this->db->query("select s.*,p.category_id,p.available_quantity,p.warning_quantity from sell_return as s left join products as p on s.product_id=p.id where s.id='$invoiceid'")->row();

        $tarray = json_decode($dailysell->devcomment);
        if(!empty($tarray)){
            foreach($tarray as $id => $row) {
                $this->db->query("update purchase set a_quantity=(a_quantity-'".$row."') where id='$id'");
            } 
        }
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailysell->date > $datef." 00:00:00" && $dailysell->date < $datef." 23:59:59" ))):

        

        $this->db->query("delete from sell_return where id='$invoiceid'");

        $dailys = $this->db->query("select customer_id,id from sell_return_summary where invoiceid='$dailysell->invoice_id'")->row();

        
        $updateqty = $this->db->query("update products set available_quantity = available_quantity-'$dailysell->quantity' where id = '$dailysell->product_id'");
        //Notificcation increase
        if($dailysell->warning_quantity<$dailysell->available_quantity && $dailysell->warning_quantity>=$dailysell->available_quantity-$dailysell->quantity)
            $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
        

        $p = ($dailysell->quantity * $dailysell->return_price);

        $this->db->query("Update sell_return_summary set total_purchase=total_purchase-'$p' where invoiceid='$dailysell->invoice_id'");
        
        $this->db->query("Update ledgerposting set credit=credit-'$p' where voucherid='$dailysell->invoice_id' and ledgerid='2' and vouchertype='sell return'");
        $this->db->query("Update ledgerposting set debit=debit-'$p' where voucherid='$dailysell->invoice_id' and ledgerid='$dailys->customer_id' and vouchertype='sell return'");

        savelog("Delete Sell Return product", " voucher= $dailysell->invoice_id".",daily_sell Id=$invoiceid".",quantity=$dailysell->quantity".",price=$dailysell->return_price".",product_id=$dailysell->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->db->query("delete from daily_sell where id='$invoiceid'");
        $this->editsellreturn($dailysell->invoice_id);
        
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    //---------------- Purchase Return Report And Update Return -----------------
    
    function purchasereturn() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'purchasereturn';
            $data['page_title'] = 'Purchase return history';
            $data['baseurl'] = $this->config->item('base_url');
            $data['sdate'] = date('Y-m-d');
            $data['edate'] = date('Y-m-d');
            $sdate = date('Y-m-d') . ' 00:00:00';
            $edate = date('Y-m-d') . ' 23:59:59';
            $comid = $this->session->userdata('company_id');
            $data['selldata'] = $this->db->query("select p.*,l.ledgername,l.address,l.father_name,l.mobile,d.name as district_name,u.fullname from purchase_return_summary as p left join accountledger as l on p.customer_id=l.id left join districts as d on l.district=d.id left join alluser as u on p.user_id=u.id where p.date between '$sdate' and '$edate'")->result();
            $this->load->view('report_derectory/purchasereturn_log', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function purchasereturnhistory() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'purchasereturn';
            $data['page_title'] = 'Purchase return history';
            $data['baseurl'] = $this->config->item('base_url');
            $data['sdate'] = $this->input->post('sdate');
            $data['edate'] = $this->input->post('edate');
            $sdate = $this->input->post('sdate') . ' 00:00:00';
            $edate = $this->input->post('edate') . ' 23:59:59';
            $comid = $this->session->userdata('company_id');
            $data['selldata'] = $this->db->query("select p.*,l.ledgername,l.address,l.father_name,l.mobile,d.name as district_name,u.fullname from purchase_return_summary as p left join accountledger as l on p.customer_id=l.id left join districts as d on l.district=d.id left join alluser as u on p.user_id=u.id where p.date between '$sdate' and '$edate'")->result();
            $this->load->view('report_derectory/purchasereturn_log', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function showpurchasereturn($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
      if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'purchasereturn';
        $company_id = $this->session->userdata('company_id');
        $data['company'] = $this->db->query("select * from company where company_id = '$company_id'")->row();
        $data['baseurl'] = $this->config->item('base_url');
        $data['invoicedata'] = $this->db->query("select d.*,p.product_name ,u.name as unit_name from purchase_return as d left join products as p on d.product_id=p.id left join product_unit as u on d.unit=u.id where d.invoice_id = '$invoiceid' AND d.company_id = '$company_id'")->result();

        $data['voicedata'] = $this->db->query("select d.*,l.ledgername as customer_name,l.mobile,l.address,l.father_name,di.name as district_name,u.fullname as user_name from purchase_return_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as u on d.user_id=u.id where d.invoiceid = '$invoiceid' AND d.company_id = '$company_id'")->row();

        $data['totalPrice'] =$data['voicedata']->total_purchase;
        $data['discount'] =$data['voicedata']->discount;

        $data['grossTotal'] =$data['totalPrice'] - $data['discount'];
        $data['inword']= convert_number(round($data['grossTotal']));
        $data['received'] =$data['voicedata']->received;
        $data['backtoreport']=true;
        $this->load->view('report_derectory/showpurchasereturn', $data);
       else:
        $this->load->view('login', $data);
       endif;
    }

    function editpurchasereturn($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
        $data['sellsummary']=$this->db->query("select * from purchase_return_summary where invoiceid='$invoiceid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($data['sellsummary']->date > $datef." 00:00:00" && $data['sellsummary']->date < $datef." 23:59:59" ))):
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'purchasereturn';
        $company_id = $this->session->userdata('company_id');
        $data['baseurl'] = $this->config->item('base_url');
        $data['randsellid']=$invoiceid;
        $data['category_id']=1;
        $data['sub_category']=1;
        
        $data['customer'] = $data['sellsummary']->customer_id;
        $data['productlist']=$this->db->query("select id,product_name,category_id,sub_category from products where company_id ='".$company_id."' order by product_name asc")->result();
        $data['allcategory']=$this->db->query("select * from category")->result();
        $data['subCategory']=$this->db->query("select * from sub_category")->result();
        $data['selldata'] = $this->db->query("select s.*,p.product_name as name,p.category_id,p.sub_category,p.unit as unit_id ,u.name as unit_name from purchase_return as s left join products as p on s.product_id=p.id left join product_unit as u on p.unit=u.id where s.invoice_id='$invoiceid' order by id desc")->result();
        // $data['getcustomer'] = $this->db->query("select l.id,l.ledgername,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid = '15' and l.company_id = '$company_id' order by l.ledgername")->result();
        $data['supplierinfo'] = $this->db->query("select l.id,l.ledgername,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id = '".$data['customer']."' ")->row();
            $this->load->view('report_derectory/editpurchasereturn',$data);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    function sppredit(){
        $data['baseurl'] = $this->config->item('base_url');
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select customer_id,id,date from purchase_return_summary where invoiceid='$randsellid'")->row();
        $datef= date('Y-m-d');

        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):
        $pquantity=$this->input->post("pquantity");
        $quantity=$this->input->post("quantity");

        $pprice=$this->input->post("pprice");
        $sellprice=$this->input->post("sellprice");

        $pproduct_id=$this->input->post("pproduct_id");
        $product_id=$this->input->post("product_id");

        $psupplier_id=$this->input->post("psupplier_id");

        $dailysellid = $this->input->post("dailysellid");

        $category_id = $this->input->post("category_id");
        $pcategory_id = $this->input->post("category_id");
        
    
        $product = $this->db->query("select * from products where id='$product_id'")->row();
        $pproduct = $this->db->query("select * from products where id='$pproduct_id'")->row();

            $this->db->query("update products set available_quantity = available_quantity+'$pquantity',total_quantity=total_quantity+'$pquantity' where id = '$pproduct_id'");
            $dailysell = $this->db->query("select * from purchase_return where id ='$dailysellid'")->row();
            $tarray = json_decode($dailysell->devcomment);
            if(!empty($tarray)){
                foreach($tarray as $id => $row) {
                    $this->db->query("update purchase set a_quantity=(a_quantity+'".$row."') where id='$id'");
                } 
            }
            //Notificcation decrease
            if($pproduct->warning_quantity>=$pproduct->available_quantity && $pproduct->warning_quantity<$pproduct->available_quantity+$pquantity)
                            $this->session->set_userdata('notification',$this->session->userdata('notification')-1);


        
            $this->db->query("update products set available_quantity = available_quantity-'$quantity',total_quantity=total_quantity-'$pquantity' where id = '$product_id'");
            if($category_id!=6){
                $purchase = $this->db->query("select id,a_quantity,buyprice from purchase where product_id='$product_id' and a_quantity>0 and supplier_id='$psupplier_id' order by id asc")->result();
                $devcomment = array();
                //$profit= 0;
                $tquantity= $quantity;
                foreach ($purchase as $pu){
                    if($pu->a_quantity>=$tquantity){
                        $this->db->query("update purchase set a_quantity ='".($pu->a_quantity-$tquantity)."' where id='$pu->id'");
                        //$profit+=(($sellprice-$pu->buyprice) * $tquantity);
                        $devcomment[$pu->id] = $tquantity;
                        $lastquantity = $tquantity;
                        $lastbuyprice = $pu->buyprice;
                        $tquantity =0;
                        break;
                    }
                    else {
                        $this->db->query("update purchase set a_quantity =0 where id='$pu->id'");
                        //$profit+=(($sellprice-$pu->buyprice) * $pu->a_quantity);
                        $devcomment[$pu->id] = $pu->a_quantity;
                        $tquantity -= $pu->a_quantity;
                    }
                }
                // if($tquantity!=0){
                //     $profit+=(($sellprice-$product->opening_price) * $tquantity);
                //     $lastquantity = $tquantity;
                //     $lastbuyprice = $product->opening_price;
                // }
            }
            else{
                // $profit = ($sellprice - $product->purchase_price) * $quantity;
                // $lastbuyprice= $product->purchase_price;
                // $lastquantity=$quantity;
                $devcomment = array();
            }

            //Notificcation increase
            if($product->warning_quantity<$product->available_quantity && $product->warning_quantity>=$product->available_quantity-$quantity)
                         $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
        
        $devcomment = json_encode($devcomment);

        $this->db->query("Update purchase_return set product_id='$product_id',return_price='$sellprice',quantity='$quantity',unit='$product->unit',devcomment	='$devcomment' where id='$dailysellid'");

        $p = ($sellprice * $quantity) - ($pprice * $pquantity);
        $this->db->query("Update purchase_return_summary set total_purchase=total_purchase+'$p' where invoiceid='$randsellid'");
        
        $this->db->query("Update ledgerposting set credit=credit+'$p' where voucherid='$randsellid' and ledgerid='3' and vouchertype='purchase return'");

        $this->db->query("Update ledgerposting set debit=debit+'$p' where voucherid='$randsellid' and vouchertype='purchase return' and ledgerid='$dailys->customer_id'");

        savelog("Update Purchase Return Product", " voucher= $randsellid".",purchase_return Id=$dailysellid".",pquantity=$pquantity".",pprice=$pprice".",pproduct_id=$pproduct_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        $this->editpurchasereturn($randsellid);

        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    function updatepurchasereturnedit(){
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select id,date from purchase_return_summary where invoiceid='$randsellid'")->row();
        $datef= date('Y-m-d');
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):
            //$customer_id = $this->input->post('customer_id');
            $pcustomer_id = $this->input->post('pcustomer_id');
            $date = $this->input->post('date');
            $pdate = $this->input->post('pdate');
            $discount = $this->input->post('discount');
            $pdiscount = $this->input->post('pdiscount');
          
            $totalprice = $this->input->post('totalprice');
            $received = $this->input->post('received');

            
        // ----update daily_sell_summary
            $this->db->query("Update purchase_return set date='$date' Where  invoice_id='$randsellid'");

            $this->db->query("Update purchase_return_summary set date='$date',total_purchase = $totalprice,discount = $discount,received=$received where invoiceid='$randsellid'");

            
            $d = $this->db->query("select count(id)as d  from ledgerposting where voucherid='$randsellid' and vouchertype='P R Discount'")->row()->d;

            $p = $this->db->query("select count(id)as d  from received where invoiceid='$randsellid'")->row()->d;
            


        //-----Update ledgerposting for discount
            if($d==2){
                $this->db->query("Update ledgerposting set debit='$discount',date='$date' where voucherid='$randsellid' and ledgerid='5' and vouchertype='P R Discount'");
                $this->db->query("Update ledgerposting set credit='$discount',date='$date' where voucherid='$randsellid' and ledgerid='$pcustomer_id' and vouchertype='P R Discount'");
            }
            else if($discount>0){
                $d_ledgerid = 5;
                $datalist_payment1 = array(
                    'voucherid' => $randsellid,
                    'ledgerid' => $d_ledgerid,
                    'date' =>  $date,
                    'vouchertype' => 'P R Discount',
                    'debit' => $discount,
                    'credit' => '0',
                    'description' => "Pr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment1);


                $datalist_payment2 = array(
                    'voucherid' => $randsellid,
                    'ledgerid' => $pcustomer_id,
                    'date' => $date,
                    'vouchertype' => 'P R Discount',
                    'debit' => '0',
                    'credit' => $discount,
                    'description' => "Pr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment2);
            }

            if($p==1){
                $receivedid = $this->db->query("select id from received where invoiceid='$randsellid'")->row()->id;
                $this->db->query("Update ledgerposting set debit='$received',date='$date' where voucherid='$receivedid' and ledgerid='1' and vouchertype='Received voucher'");
                $this->db->query("Update ledgerposting set credit='$received',date='$date',ledgerid='$pcustomer_id' where voucherid='$receivedid' and ledgerid='$pcustomer_id' and vouchertype='Received voucher'");
                $this->db->query("Update received set amount='$received' where invoiceid='$randsellid'");
            }
            else if($received>0){

                $datalist = array(
                    'invoiceid'=>$randsellid,
                    'ledgerid' => $pcustomer_id,
                    'date' => $this->input->post('date'),
                    'amount' => $received,
                    'description' => "Pr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id'),
                    'user_id' => $this->session->userdata('user_id')

                );
                $this->db->insert('received', $datalist);
                $lastid = $this->db->insert_id();
                $this->db->where('id', $lastid);

                $d_ledgerid = 1;
                $datalist_payment1 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => $d_ledgerid,
                    'date' =>  $date,
                    'vouchertype' => 'Received voucher',
                    'debit' => $received,
                    'credit' => '0',
                    'description' => "Pr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment1);


                $datalist_payment2 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => $pcustomer_id,
                    'date' => $date,
                    'vouchertype' => 'Received voucher',
                    'debit' => '0',
                    'credit' => $received,
                    'description' => "Pr-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment2);
            }
        //-----Update ledgerposting for labour_charge 
        
            $this->session->set_userdata("success","Purchase Return is Updated successfully");
            savelog("Update Purchase Return ", " voucher= $randsellid".",pcustomer_id=$pcustomer_id".",pdate=$pdate".",pdiscount=$pdiscount"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->editpurchasereturn($randsellid);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    function deletepurchasereturn($voucherid){
        $data['baseurl'] = $this->config->item('base_url');
        $dailys = $this->db->query("select * from purchase_return_summary where invoiceid='$voucherid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):

        $dailysell =$this->db->query("select s.*,p.category_id,p.available_quantity,p.warning_quantity from purchase_return as s left join products as p on s.product_id=p.id where s.invoice_id='$voucherid'")->result();

        foreach ($dailysell as $key ) {
            $tarray = json_decode($key->devcomment);
            if(!empty($tarray)){
                foreach($tarray as $id => $row) {
                    $this->db->query("update purchase set a_quantity=(a_quantity+'".$row."') where id='$id'");
                } 
            }
            
            $updateqty = $this->db->query("update products set available_quantity = available_quantity+'$key->quantity',total_quantity=total_quantity+'$key->quantity' where id = '$key->product_id'");
            //notification decrease
            if($key->warning_quantity>=$key->available_quantity && $key->warning_quantity<$key->available_quantity+$key->quantity)
                $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
            
            
            savelog("Delete Sales product", " voucher= $key->invoice_id".",purchase_return Id=$key->id".",quantity=$key->quantity".",price=$key->return_price".",product_id=$key->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        }
        $this->db->query("delete from purchase_return where invoice_id='$voucherid'");
        

        
        $this->db->query("delete from purchase_return_summary where invoiceid='$voucherid'");

        $this->db->query("Delete from ledgerposting where voucherid='$voucherid' and (vouchertype='purchase return' or vouchertype='P R Discount')");

        $dpayment = $this->db->query("select id from received where invoiceid='$voucherid'")->row();
        if(isset($dpayment)){
            $this->db->query("delete from received where invoiceid='$voucherid'");
            $this->db->query("Delete from ledgerposting where voucherid='$dpayment->id' and vouchertype='Received voucher'");
        }

        savelog("Delete Purchase Return", " voucher= $voucherid".",pcustomer_id=$dailys->customer_id".",pdate=$dailys->date".",pdiscount=$dailys->discount".",total_price=$dailys->total_purchase"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->purchasereturn();
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    function deletepurchasese($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
        $dailysell =$this->db->query("select s.*,p.category_id,p.available_quantity,p.warning_quantity from purchase_return as s left join products as p on s.product_id=p.id where s.id='$invoiceid'")->row();
        $tarray = json_decode($dailysell->devcomment);
        if(!empty($tarray)){
            foreach($tarray as $id => $row) {
                $this->db->query("update purchase set a_quantity=(a_quantity+'".$row."') where id='$id'");
            } 
        }
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailysell->date > $datef." 00:00:00" && $dailysell->date < $datef." 23:59:59" ))):


        $dailys = $this->db->query("select customer_id,id from purchase_return_summary where invoiceid='$dailysell->invoice_id'")->row();

        
        $updateqty = $this->db->query("update products set available_quantity = available_quantity+'$dailysell->quantity',total_quantity=total_quantity+'$dailysell->quantity' where id = '$dailysell->product_id'");
        //notification decrease
        if($dailysell->warning_quantity>=$dailysell->available_quantity && $dailysell->warning_quantity<$dailysell->available_quantity+$dailysell->quantity)
            $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
        


        $p = ($dailysell->quantity * $dailysell->return_price);

        $this->db->query("Update purchase_return_summary set total_purchase=total_purchase-'$p' where invoiceid='$dailysell->invoice_id'");
        
        $this->db->query("Update ledgerposting set credit=credit-'$p' where voucherid='$dailysell->invoice_id' and ledgerid='3' and vouchertype='purchase return'");
        $this->db->query("Update ledgerposting set debit=debit-'$p' where voucherid='$dailysell->invoice_id' and ledgerid='$dailys->customer_id' and vouchertype='purchase return'");

        savelog("Delete Purchase Return product", " voucher= $dailysell->invoice_id".",daily_sell Id=$invoiceid".",quantity=$dailysell->quantity".",price=$dailysell->return_price".",product_id=$dailysell->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->db->query("delete from purchase_return where id='$invoiceid'");
        $this->editpurchasereturn($dailysell->invoice_id);
        
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    //--------------------------- Sales History ----------------------------

    function viewsellhistory() {
        $company_id = $this->session->userdata('company_id');
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellhistory';
        $data['page_title'] = 'Sales History';
        $data['baseurl'] = $this->config->item('base_url');
        $data['role']=$this->session->userdata('role');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $sdate = $this->input->post('sdate') . ' 00:00:00';
            $edate = $this->input->post('edate') . ' 23:59:59';

            $data['sdate'] = $this->input->post('sdate');
            $data['edate'] = $this->input->post('edate');

            $data['cname'] = $this->input->post('customer');
            $data['salesman'] = $this->input->post('salesman');

            $name = $this->input->post('customer');
            $man = $this->input->post('salesman');


            $data['customerlist'] = $this->db->query("select l.ledgername,l.mobile,l.id,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where (l.accountgroupid=7 or l.accountgroupid=6) and l.status<>0")->result();

            if ($data['cname'] == 'all'):
               
                $data['selldata'] = $this->db->query("select d.*,l.ledgername as customer_name,l.mobile,l.address,l.father_name,di.name as district_name,al.fullname from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as al on d.user_id=al.id WHERE  d.date between '$sdate' AND '$edate' AND d.company_id = '$company_id' order by d.id desc")->result();
                
            else:
                
                $data['selldata'] = $this->db->query("select d.*,l.ledgername as customer_name,l.mobile,l.address,l.father_name,di.name as district_name,al.fullname from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id  left join alluser as al on d.user_id=al.id  WHERE  d.date between '$sdate' AND '$edate' AND d.customer_id=".$data['cname']." AND d.company_id = '$company_id' order by d.id desc")->result();
                
            endif;

            $this->load->view('report_derectory/sellhistory', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function printinvoiceagain($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
      if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellhistory';
        $company_id = $this->session->userdata('company_id');
        $data['company'] = $this->db->query("select * from company where company_id = '$company_id'")->row();
        $data['baseurl'] = $this->config->item('base_url');
        $data['invoicedata'] = $this->db->query("select d.*,p.product_name ,u.name as unit_name from daily_sell as d left join products as p on d.product_id=p.id left join product_unit as u on d.unit=u.id where d.invoice_id = '$invoiceid' AND d.company_id = '$company_id'")->result();
        // echo '<pre>';
        // print_r($data['invoicedata']);
        // echo '</pre>';
        // die();
        $data['voicedata'] = $this->db->query("select d.*,l.ledgername as customer_name,l.father_name,l.mobile,l.address,di.name as district_name,ui.fullname  from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as ui on d.user_id=ui.id  where d.voucherid = '$invoiceid' AND d.company_id = '$company_id'")->row();

        $data['totalPrice'] =$data['voicedata']->total_price;
        //$data['vat'] =$data['voicedata']->vat;
        $data['discount'] =$data['voicedata']->discount;
   
        // $tem = ($data['totalPrice'] *$data['vat']/100);
         
        $data['grossTotal'] =$data['totalPrice'] - $data['discount'];
        $data['paidAmount'] = $data['voicedata']->paid_amount;
        $data['preDue'] =$data['voicedata']->pre_due;
        $data['fTotalDue'] =$data['preDue']+$data['grossTotal']-$data['paidAmount'];
        $data['inword']= convert_number(round($data['grossTotal']));
        
        $data['backtoreport']=true;
        $this->load->view('sell_derectory/printinvoice', $data);
       else:
        $this->load->view('login', $data);
       endif;
    }

    function viewsellhistoryDetails() {
        $company_id = $this->session->userdata('company_id');
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'selldetails';
        $data['page_title'] = 'Sales History';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

        $sdate = $this->input->post('sdate') . ' 00:00:00';
        $edate = $this->input->post('edate') . ' 23:59:59';

        $data['sdate'] = $this->input->post('sdate');
        $data['edate'] = $this->input->post('edate');

        $data['user'] = $this->input->post('customer');
        $name = $this->input->post('customer');
        $data['cname'] = $name;

            $data['customerlist'] = $this->db->query("select l.ledgername,l.mobile,l.id,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where (l.accountgroupid=7 or l.accountgroupid=6) and l.status<>0")->result();

            if ($data['user'] == 'all'):
                $data['selldata'] = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,l.father_name,l.mobile,di.name as district_name,u.name as unit,ds.id as sellid from daily_sell as d left join products as p on d.product_id=p.id left join accountledger as l on d.customer_id=l.id left join product_unit as u on d.unit=u.id left join daily_sell_summary as ds on d.invoice_id=ds.voucherid left join districts as di on l.district=di.id where d.date between '$sdate' AND '$edate' AND d.company_id = '$company_id'")->result();
                $data['sellSummary']= $this->db->query("select sum(discount) as discount from daily_sell_summary WHERE date between '$sdate' AND '$edate' AND company_id = '$company_id'")->row(); 
            else:
                $data['selldata'] = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,l.father_name,l.mobile,di.name as district_name,u.name as unit,ds.id as sellid from daily_sell as d left join products as p on d.product_id=p.id left join accountledger as l on d.customer_id=l.id left join product_unit as u on d.unit=u.id left join daily_sell_summary as ds on d.invoice_id=ds.voucherid left join districts as di on l.district=di.id where d.date between '$sdate' AND '$edate' AND d.company_id = '$company_id' AND d.customer_id='$name'")->result();
                $data['sellSummary']= $this->db->query("select sum(discount) as discount from daily_sell_summary WHERE date between '$sdate' AND '$edate' AND company_id = '$company_id' AND customer_id='$name'")->row(); 
            endif;

        $this->load->view('report_derectory/selldetails', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function sellhistory() {
        $company_id = $this->session->userdata('company_id');
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellhistory';
        $data['page_title'] = 'Sales History';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $sdate = date("Y-m-d") . ' 00:00:00';
            $edate = date("Y-m-d") . ' 23:59:59';

            $data['sdate'] = date("Y-m-d");
            $data['edate'] = date("Y-m-d");

            $data['cname'] = 'all';
            $data['salesman']='';

            $data['selldata'] = $this->db->query("select d.*,l.ledgername as customer_name,l.mobile,l.address,l.father_name,di.name as district_name,al.fullname from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as al on d.user_id=al.id WHERE d.company_id = '$company_id' order by d.id desc limit 10")->result();
            $data['customerlist'] = $this->db->query("select l.ledgername,l.id,l.address,l.mobile,l.father_name,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where (l.accountgroupid=7 or l.accountgroupid=6) and l.status<>0")->result();

            $this->load->view('report_derectory/sellhistory', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function detailssell($invoiceid = '0') {
        $company_id = $this->session->userdata('company_id');
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellhistory';
        $data['page_title'] = 'Sales History';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $sdate = date("Y-m-d") . ' 00:00:00';
            $edate = date("Y-m-d") . ' 23:59:59';

            $data['sdate'] = $sdate;
            $data['edate'] = $edate;

            $data['cname'] = '';
            $data['invoiceid'] = $invoiceid;
            $data['customerlist'] = $this->db->query("select l.ledgername,l.id,l.address,l.mobile,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where (l.accountgroupid=7 or l.accountgroupid=6) and l.status<>0")->result();

            $data['selldata'] = $this->db->query("select d.*,p.product_name,l.ledgername,l.father_name,l.mobile,u.name as unit, ds.id as sellid from daily_sell as d left join products as p on d.product_id=p.id left join daily_sell_summary as ds on d.invoice_id=ds.voucherid left join accountledger as l on ds.customer_id=l.id left join product_unit as u on d.unit=u.id left join daily_sell_summary as ds on d.invoice_id=ds.voucherid where d.invoice_id = '$invoiceid' AND d.company_id = '$company_id'")->result();
            $data['sellSummary']= $this->db->query("select discount from daily_sell_summary WHERE voucherid = '$invoiceid' AND company_id = '$company_id'")->row();
            
            $this->load->view('report_derectory/selldetails', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    //---------------------------------------------------------------------

    function viewtransection($id = '') {
        $data['supplier'] = $id;
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'supplier_report';
        $data['page_title'] = 'Supplier reports';
        $data['baseurl'] = $this->config->item('base_url');
        $comid = $this->session->userdata('company_id');
        $data['sdate'] = date("Y-m-d") . ' 00:00:00';
        $data['edate'] = date("Y-m-d") . ' 23:59:59';
        $data['purchasedata'] = $this->db->query("select sum(total_buyprice) as totalpurchase,id,date,invoiceid from purchase where supplier_id = '$id' AND company_id = '$comid' group by invoiceid")->result();
        $data['paymentdata'] = $this->db->query("select sum(amount) as totalpayment,id,date,invoiceid from payments where ledgerid = '$id' AND company_id = '$comid' group by invoiceid")->result();
        $this->load->view('supplier_reports', $data);
    }
    
    function searchpurchasehistory() {
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'supplier_report';
        $data['page_title'] = 'Supplier reports';

        $data['sdate'] = $this->input->post('sdate');
        $data['edate'] = $this->input->post('edate');

        $data['baseurl'] = $this->config->item('base_url');
        $comid = $this->session->userdata('company_id');

        $sdate = $this->input->post('sdate') . ' 00:00:00';
        $edate = $this->input->post('edate') . ' 23:59:59';

        $supplier = $this->input->post('supplier');
        $data['supplier'] = $supplier;


        if ($supplier == 'all'):
            $data['purchasedata'] = $this->db->query("select sum(total_buyprice) as totalpurchase,id,date,invoiceid from purchase where date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoiceid")->result();
            $data['paymentdata'] = $this->db->query("select sum(amount) as totalpayment,id,date,invoiceid from payments where date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoiceid")->result();
        else:
            $data['purchasedata'] = $this->db->query("select sum(total_buyprice) as totalpurchase,id,date,invoiceid from purchase where supplier_id = '$supplier' AND date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoiceid")->result();
            $data['paymentdata'] = $this->db->query("select sum(amount) as totalpayment,id,date,invoiceid from payments where ledgerid = '$supplier' AND date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoiceid")->result();
        endif;
        $this->load->view('supplier_reports', $data);
    }

    function customer_report($id = '') {
        $data['customer'] = $id;
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'customer_report';
        $data['page_title'] = 'Customer reports';
        $data['baseurl'] = $this->config->item('base_url');
        $comid = $this->session->userdata('company_id');

        $data['sdate'] = date("Y-m-d");
        $data['edate'] = date("Y-m-d");

        $sdate = date("Y-m-d") . ' 00:00:00';
        $edate = date("Y-m-d") . ' 23:59:59';

        $data['selldata'] = $this->db->query("select * from daily_sell where buyername = '$id' AND date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoice_id")->result();
        $data['receivedata'] = $this->db->query("select * from received where ledgerid = '$id' AND date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoiceid")->result();
        $this->load->view('customer_reports', $data);
    }

    function viewcustomerreport() {
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'customer_report';
        $data['page_title'] = 'Customer reports';

        $data['baseurl'] = $this->config->item('base_url');

        $comid = $this->session->userdata('company_id');

        $sdate = $this->input->post('sdate') . ' 00:00:00';
        $edate = $this->input->post('edate') . ' 23:59:59';
        $data['sdate'] = $this->input->post('sdate');
        $data['edate'] = $this->input->post('edate');

        $customerid = $this->input->post('customer');

        $data['customer'] = $customerid;

        $data['selldata'] = $this->db->query("select * from daily_sell where buyername = '$customerid' AND date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoice_id")->result();
        $data['receivedata'] = $this->db->query("select * from received where ledgerid = '$customerid' AND date between '$sdate' AND '$edate' AND company_id = '$comid' group by invoiceid")->result();
        $this->load->view('customer_reports', $data);
    }

    function rawstock($subCategory=0,$cat=0) {

        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'rawstock';
        $data['page_title'] = 'Available stock';
        $data['baseurl'] = $this->config->item('base_url');
        $data['pgname'] = '';
        $qmax= -999999; $qmin= 999999;
        $company_id = $this->session->userdata('company_id');
        $data['sub_category']= $data['category_id']=$category =$sub_category='';
        if($this->session->userdata('fcategory')!='true')
        $data['sub_category']= $data['category_id']=$category =$sub_category='1';
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

             $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$company_id."' order by name asc")->result();


            if($this->input->post('sub_category'))
            {
                $data['sub_category']=$sub_category = $this->input->post('sub_category');
                $data['category_id']=$category = $this->input->post('category_id');
                if(($this->input->post('qgt')&& $this->input->post('qmax')=='')||($this->input->post('qlt')&& $this->input->post('qmin')=='')){
                    $this->session->set_userdata('failed', 'Pls Insert Number if checked any');
                    redirect('reports/rawstock');
                }
                else if($this->input->post('qgt')) $qmax= $this->input->post('qmax');
                else if($this->input->post('qlt')) $qmax= $this->input->post('qmin');
            }

  
            if($sub_category==-1&&$category!=-1){
            $data['product'] = $this->db->query("select p.*,u.name  as unit_name,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id where p.company_id='$company_id' and c.id='$category' order by p.product_name asc")->result();
            }

            else if($sub_category==-1&&$category==-1){
            $data['product'] = $this->db->query("select p.*,u.name  as unit_name,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id where p.company_id='$company_id' order by p.product_name asc")->result();
            }
            else if($sub_category!=-1&&$category!=-1&&$sub_category!=''){
            $data['product'] = $this->db->query("select p.*,u.name  as unit_name,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id where p.company_id='$company_id' and sc.id='$sub_category' and c.id='$category' order by p.product_name asc")->result();
            }
            else{
                $data['product'] = array();
            }

            if($category==-1)
            $data['subCategory'] = $this->db->get_where('sub_category', array('company_id' => $this->session->userdata('company_id')))->result();
            else
                $data['subCategory'] = $this->db->get_where('sub_category', array('company_id' => $this->session->userdata('company_id'),'category_id'=>$category))->result();

            $data['sub_category'] = $sub_category;
            $data['cat'] = $category;
            

            $this->load->view('report_derectory/stock', $data);
        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;
    }

    function viewstock() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'rawstock';
            $data['page_title'] = 'Available stock';
            $data['baseurl'] = $this->config->item('base_url');
            
            $data['pgname'] = $this->input->post('pgname');

            
            $pgname = $this->input->post('pgname');

            $comapnyid = $this->session->userdata('company_id');

            if ($pgname == 'all'):
                $data['product'] = $this->db->query("select p.*,u.name  as unit_name,c.name as category_name,l.ledgername as suppliername,l.id as ledger_id from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category=c.id left join accountledger as l on p.supplier=l.id where p.company_id='$comapnyid' order by p.pname asc")->result();
            else:
                $data['product'] = $this->db->query("select p.*,u.name  as unit_name,c.name as category_name,l.ledgername as suppliername,l.id as ledger_id from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category=c.id left join accountledger as l on p.supplier=l.id where p.company_id='$comapnyid' order by p.pname asc")->result();
            endif;

            $this->load->view('stock', $data);
        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;
    }

    function singlelist($id) {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'stockDetails';
            $data['page_title'] = 'Available stock';
            $data['baseurl'] = $this->config->item('base_url');
            $data['sdate']= date("Y-m-d")." 00:00:00";
            $data['edate']= date("Y-m-d")." 23:59:59";
            $data['unit']="unit";
            $data['openingECQuantity'] = 0;
            

            if($this->input->post('search')=="Submit"){
                if($this->input->post('productid')!=""){
                    $temp2= $this->db->query("select id from products where product_id='".$this->input->post('productid')."'")->row();
                    if($temp2)
                     $id = $data['product'] = $temp2->id;
                    else {
                        $id=$data['product'] = $this->input->post('product');
                    }
                }
                else
                $id=$data['product'] = $this->input->post('product');

                $data['sdate']= date("Y-m-d",strtotime($this->input->post('sdate'))). " 00:00:00";
                $data['edate']= date("Y-m-d",strtotime($this->input->post('edate')))." 23:59:59";
                
            }

            else
            {
                $data['sdate']= date('Y-m-d', strtotime(' -7 day'));
                $data['edate']= date("Y-m-d H:i:s");
                $data['product'] = $id;
            }

            $temp =  $this->db->query("select p.*,u.name as unit from products as p left join product_unit as u on p.purchase_unit=u.id where p.id='".$id."'")->row();
            $data['unit'] =  $temp->unit;   
            $data['openingQuantity']= $temp->opening_quantity;
            $openingadjustment = 0;
            $data['category_id']=$temp->category_id;


            $purchaseQuantity = $this->db->query("select sum(quantity) as q from purchase where product_id='".$data['product']."' and date < '".$data['sdate']."'")->row()->q;
            $sellQuantity = $this->db->query("select sum(quantity) as q from daily_sell where product_id='".$data['product']."' and date < '".$data['sdate']."'")->row()->q;
            $purchasereturnQuantity = $this->db->query("select sum(quantity) as q from purchase_return where product_id='".$data['product']."' and date < '".$data['sdate']."'")->row()->q;
            $sellreturnQuantity = $this->db->query("select sum(quantity) as q from sell_return where product_id='".$data['product']."' and date < '".$data['sdate']."'")->row()->q;
            
            $data['openingQuantity'] += ($purchaseQuantity-$sellQuantity-$purchasereturnQuantity+$sellreturnQuantity+$openingadjustment);

            //1st sell data 2nd purchase 3rd sell retyrn 4th purchase return 
            $data['items'] = $this->db->query("(SELECT s.sellprice as price,s.invoice_id,s.date,s.quantity,s.customer_id as buyername,s.type,d.id as voucherid FROM daily_sell as s left join daily_sell_summary as d on s.invoice_id=d.voucherid WHERE s.product_id='".$data['product']."' and s.date between '".$data['sdate']."' and '".$data['edate']."')
                UNION ALL
                (SELECT s.buyprice as price,s.invoiceid as invoice_id,s.date,s.quantity,s.supplier_id as buyername,s.type,d.id as voucherid FROM purchase as s left join purchase_summary as d on s.invoiceid=d.invoiceid WHERE s.product_id='".$data['product']."'  and s.date between '".$data['sdate']."' and '".$data['edate']."')
                UNION ALL
                (SELECT s.return_price as price,s.invoice_id as invoice_id,s.date,s.quantity,s.customer_id as buyername,s.type,d.id as voucherid FROM sell_return as s left join sell_return_summary as d on s.invoice_id=d.invoiceid WHERE s.product_id='".$data['product']."'  and s.date between '".$data['sdate']."' and '".$data['edate']."')
                UNION ALL
                (SELECT s.return_price as price,s.invoice_id as invoice_id,s.date,s.quantity,s.customer_id as buyername,s.type,d.id as voucherid FROM purchase_return as s left join purchase_return_summary as d on s.invoice_id=d.invoiceid WHERE s.product_id='".$data['product']."'  and s.date between '".$data['sdate']."' and '".$data['edate']."')
                ORDER BY date")->result();

            $this->load->view('report_derectory/singleproduct', $data);
            
        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;
    }

    //-------------------------- CSV file export --------------------------------->

    function exports_data($sub_category,$category){
        $company_id = $this->session->userdata('company_id');
            if($sub_category==-1&&$category!=-1){
            $product = $this->db->query("select p.product_id,p.category_id,p.product_name,c.name as category_name,sc.name as sub_category,p.purchase_price,available_quantity, u.name  as unit_name,(purchase_price*available_quantity) as total from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id where p.company_id='$company_id' and c.id='$category' order by p.id asc")->result_array();
            }

            else if($sub_category==-1&&$category==-1){
            $product= $this->db->query("select p.product_id,p.category_id,p.product_name,c.name as category_name,sc.name as sub_category,p.purchase_price,available_quantity, u.name  as unit_name,(purchase_price*available_quantity) as total from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id where p.company_id='$company_id' order by p.id asc")->result_array();
            }
            else if($sub_category!=-1&&$category!=-1&&$sub_category!=''){
            $product = $this->db->query("select p.product_id,p.category_id,p.product_name,c.name as category_name,sc.name as sub_category,p.purchase_price,available_quantity, u.name  as unit_name,(purchase_price*available_quantity) as total  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id where p.company_id='$company_id' and sc.id='$sub_category' and c.id='$category' order by p.id asc")->result_array();
            }

           $sum = 0;

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Stock".date('Y-m-d H:i:s').".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Id","Name","Category","Sub Category","Unit Price","Available Quantity","Unit","Total Price"); 
            fputcsv($handle, $header);

            foreach ($product as $key=>$line){ 
               $arr[0]= $line['product_id']; 
               $data = chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE", $line['product_name']);
               $arr[1]=substr($data,2);
               $arr[2]= $line['category_name'];   
               $arr[3]= $line['sub_category'];   
               $arr[4]= $line['purchase_price']; 
               $arr[5]= $line['available_quantity'];  
               $arr[6]= $line['unit_name']; 
               $arr[7]= $line['total'];
               $sum = $sum + $arr[7];    
               fputcsv($handle, $arr);         
            }
            $header2 = array("","","","","","","Total",$sum); 
            fputcsv($handle, $header2);
            
            fclose($handle);
            exit;
    }

    function exports_sup_cus($enddate,$typesc){
        $sdate =$enddate;
        $enddate = $enddate.' 23:59:59';
        $accountgroupid='';
        $type='';
        if($typesc==15){
            $accountgroupid = 15; 
        }   
        else if($typesc==16){
            $accountgroupid = 16;
        }

        $ledgerdata = $this->db->query("select sum(lp.debit) as pdebit, sum(lp.credit) as pcredit,l.debit,l.credit,l.id,l.ledgername,l.address,l.mobile,l.father_name,ds.name as district_name from accountledger  as l left join ledgerposting as lp on l.id=lp.ledgerid left join districts as ds on l.district=ds.id  where l.accountgroupid='$accountgroupid' group by l.id order by district_name,l.address asc")->result_array(); 
             
        
        
        $tbalance=0;
        

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=".$type.' Balance '.$sdate.".csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            $handle = fopen('php://output', 'w');
            $header = array("Ledger Name","Father's Name","Mobile No.","Address","Debit","Credit","Balance"); 
            fputcsv($handle, $header);

            foreach ($ledgerdata as $key=>$line){  
               $debit = $line['pdebit']+$line['debit'];
               $credit = $line['pcredit']+$line['credit'];
               $balance = $debit - $credit;
               $tbalance += $balance;
               if ($balance==0)
                continue;
               $arr[0]=$line['ledgername'];
               $arr[1]=$line['father_name'];
               $arr[2]=$line['mobile'];
               $arr[3]=$line['address'].', '.$line['district_name'];
               $arr[4]= $debit;   
               $arr[5]= $credit;   
               $arr[6]= $balance;      
               fputcsv($handle, $arr);         
            }
            $header2 = array("","","","","","Total",$tbalance); 
            fputcsv($handle, $header2);
            
            fclose($handle);
            exit;
    }
   //<------------------- end ------------------------->

    function supcusbalance(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'supcusbalance';
            $data['page_title'] = 'Supplier / Customer Balance';
            $data['baseurl'] = $this->config->item('base_url');
            $data['ledgerdata']=array();
            $data['typesc']='';
            $data['district']=0;
            $data['enddate']=date('Y-m-d');
            $accountgroupid=0;
            if($this->input->post('typesc')){
                $typesc = $this->input->post('typesc');
                $district = $this->input->post('district');
               
                $data['enddate']=$this->input->post('enddate');
                
                if($district==0){

                    $data['ledgerdata'] = $this->db->query("select sum(lp.debit) as pdebit, sum(lp.credit) as pcredit,l.debit,l.credit,l.id,l.ledgername,l.address,l.mobile,l.father_name,ds.name as district_name from accountledger  as l LEFT OUTER JOIN ledgerposting as lp on l.id=lp.ledgerid  left join districts as ds on l.district=ds.id  where l.accountgroupid='$typesc' group by l.id order by district_name,l.address asc")->result();
                     
                }
                else{

                    $data['ledgerdata'] = $this->db->query("select sum(lp.debit) as pdebit, sum(lp.credit) as pcredit,l.debit,l.credit,l.id,l.ledgername,l.address,l.mobile,l.father_name,ds.name as district_name from accountledger  as l left join ledgerposting as lp on l.id=lp.ledgerid left join districts as ds on l.district=ds.id  where l.accountgroupid='$typesc' AND l.district='$district'  group by l.id order by district_name,l.address asc")->result(); 
                }
                     
                $data['typesc']= $typesc;
                $data['district']= $district;
                
            }
            $this->load->view('report_derectory/supcusbalance', $data);

        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;
    }

    //------------------ received report --------------------------->

    function received(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'received';
            $company_id = $this->session->userdata('company_id');
            $data['baseurl'] = $this->config->item('base_url');
            $sdate=$data['sdate']=date('Y-m-d');
            $edate=$data['edate']=date('Y-m-d');
            $sdate=$sdate.' 00:00:00';
            $edate=$edate.' 23:59:59';
            if($this->input->post('sdate')!=null){
                $sdate=$data['sdate']=$this->input->post('sdate');
                $edate=$data['edate']=$this->input->post('edate');
                $sdate=$sdate.' 00:00:00';
                $edate=$edate.' 23:59:59';
            }

            $data['payments'] = $this->db->query("select p.*,u.fullname from received as p left join alluser as u on p.user_id=u.id where p.company_id = '$company_id' and p.date between '$sdate' and '$edate' order by p.id desc ")->result();


            $this->load->view('report_derectory/received', $data);
            
        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;

    }

    function receivedshow($id){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'received';
            $company_id = $this->session->userdata('company_id');
            $data['baseurl'] = $this->config->item('base_url');
            $data['received'] = $this->db->query("select p.*,l.ledgername,l.accountgroupid,l.address,d.name as district_name,l.father_name,l.mobile,u.fullname  from received as p left join accountledger as l on p.ledgerid=l.id left join districts as d on l.district=d.id left join alluser as u on p.user_id=u.id where p.company_id = '$company_id' and p.id ='$id'")->row();

            $data['inword'] = convert_number(round($data['received']->amount));
            

            //customer current Due----------------------

            $ledgerdata = $this->db->query("select * from accountledger where id = ".$data['received']->ledgerid)->row();
            $lPosting = $this->db->query("select sum(debit) as aDebit,sum(credit) as aCredit from ledgerposting where ledgerid=".$data['received']->ledgerid)->row();
            $debit = $ledgerdata->debit+$lPosting->aDebit;
            $credit = $ledgerdata->credit+$lPosting->aCredit;
            $data['currentdue'] = $debit-$credit;

            $this->load->view('report_derectory/received_voucher_show', $data);


            //-------------------------------------------

        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;
    }

    //------------------------ Payment report ---------------------------->

    function payment(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'payment';
            $company_id = $this->session->userdata('company_id');
            $data['baseurl'] = $this->config->item('base_url');
            $sdate=$data['sdate']=date('Y-m-d');
            $edate=$data['edate']=date('Y-m-d');
            $sdate=$sdate.' 00:00:00';
            $edate=$edate.' 23:59:59';
            if($this->input->post('sdate')!=null){
                $sdate=$data['sdate']=$this->input->post('sdate');
                $edate=$data['edate']=$this->input->post('edate');
                $sdate=$sdate.' 00:00:00';
                $edate=$edate.' 23:59:59';
            }

            $data['payments'] = $this->db->query("select p.*,u.fullname from payments as p left join alluser as u on p.user_id=u.id where p.company_id = '$company_id' and p.date between '$sdate' and '$edate' order by p.id desc ")->result();


            $this->load->view('report_derectory/payment', $data);



        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;
    }

    function paymentshow($id){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'payment';
            $company_id = $this->session->userdata('company_id');
            $data['baseurl'] = $this->config->item('base_url');
            $data['payments'] = $this->db->query("select p.*,l.ledgername,l.address,l.accountgroupid,d.name as district_name,l.father_name,l.mobile,u.fullname  from payments as p left join accountledger as l on p.ledgerid=l.id left join districts as d on l.district=d.id  left join alluser as u on p.user_id=u.id  where p.company_id = '$company_id' and p.id ='$id'")->row();

            $data['inword'] = convert_number(round($data['payments']->amount));
            $this->load->view('report_derectory/payment_voucher_show', $data);
        else:
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('login', $data);
        endif;
    }

    //------------------------------------- admin panel--------------------------//---->

    //---------------------- update sell ---------->
    function edit($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
        $data['sellsummary']=$this->db->query("select * from daily_sell_summary where voucherid='$invoiceid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($data['sellsummary']->date > $datef." 00:00:00" && $data['sellsummary']->date < $datef." 23:59:59" ))):
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'sellhistory';
        $company_id = $this->session->userdata('company_id');
        $data['baseurl'] = $this->config->item('base_url');
        $data['randsellid']=$invoiceid;
        $data['category_id']=1;
        $data['sub_category']=1;
        
        if($data['sellsummary']=="")
            return $this->sellhistory();
           
        $data['customer'] = $data['sellsummary']->customer_id;
        $data['productlist']=$this->db->query("select id,product_name,category_id,sub_category from products where company_id ='".$company_id."' order by product_name asc")->result();
        $data['allcategory']=$this->db->query("select * from category")->result();
        $data['subCategory']=$this->db->query("select * from sub_category")->result();
        $data['selldata'] = $this->db->query("select s.*,p.product_name as name,p.category_id,p.sub_category,p.unit as unit_id ,u.name as unit_name from daily_sell as s left join products as p on s.product_id=p.id left join product_unit as u on p.unit=u.id where s.invoice_id='$invoiceid' order by id desc")->result();
        $data['getcustomer'] = $this->db->query("select l.id,l.ledgername,l.address,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid = '16' and l.company_id = '$company_id' order by l.ledgername")->result();
            $this->load->view('report_derectory/editsell',$data);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }
    function updateedit(){
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select id,date from daily_sell_summary where voucherid='$randsellid'")->row();
        $datef= date('Y-m-d');
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):
            $customer_id = $this->input->post('customer_id');
            $pcustomer_id = $this->input->post('pcustomer_id');
            $date = $this->input->post('date');
            $pdate = $this->input->post('pdate');
            $discount = $this->input->post('discount');
            $pdiscount = $this->input->post('pdiscount');
            
            $totaldue = $this->input->post('totaldue');
            $ptotaldue = $this->input->post('ptotaldue');
            $paidAmount = $this->input->post('paidAmount');
            $ppaidAmount = $this->input->post('ppaidAmount');
            $preDue = $this->input->post('preDue');
            $ppreDue = $this->input->post('ppreDue');
            $fTotalDue = $this->input->post('fTotalDue');
            $pfTotalDue = $this->input->post('pfTotalDue');
            $randsellid = $this->input->post('randsellid');
            $totalprice = $this->input->post('totalprice');
            $sumComment= $this->input->post('sumComment');
            


            
        // ----update daily_sell_summary
            $this->db->query("Update daily_sell set customer_id='$customer_id' ,date='$date' Where   invoice_id='$randsellid'");
            
            $this->db->query("Update daily_sell_summary set customer_id=$customer_id,date='$date',total_price = $totalprice,paid_amount = $paidAmount, due = $fTotalDue,pre_due = $preDue,discount = $discount,comment='$sumComment' where voucherid='$randsellid'");
            if($customer_id==$pcustomer_id){
                $p=$fTotalDue-$pfTotalDue;
                $this->db->query("Update daily_sell_summary set pre_due=+'$p',due=due+'$p' where customer_id='$customer_id' and  id > '$dailys->id'");
            }

            $d = $this->db->query("select count(id)as d  from ledgerposting where voucherid='$randsellid' and vouchertype='Discount'")->row()->d;
            $p = $this->db->query("select count(id)as d from ledgerposting where voucherid='$randsellid' and vouchertype='Received voucher'")->row()->d;

        //-----Update ledgerposting for sell
            $temp = $totalprice;
            $this->db->query("Update ledgerposting set credit='$temp',date='$date' where voucherid='$randsellid' and ledgerid='2' and vouchertype='sales'");
            $this->db->query("Update ledgerposting set debit='$temp',date='$date',ledgerid='$customer_id' where voucherid='$randsellid' and ledgerid='$pcustomer_id' and vouchertype='sales'");
        //-----Update ledgerposting for discount
            if($d==2){
                $this->db->query("Update ledgerposting set debit='$discount',date='$date' where voucherid='$randsellid' and ledgerid='5' and vouchertype='Discount'");
                $this->db->query("Update ledgerposting set credit='$discount',date='$date',ledgerid='$customer_id' where voucherid='$randsellid' and ledgerid='$pcustomer_id' and vouchertype='Discount'");
                }
            else if($discount>0){
                $d_ledgerid = 5;
                $datalist_payment1 = array(
                    'voucherid' => $randsellid,
                    'ledgerid' => $d_ledgerid,
                    'date' =>  $date,
                    'vouchertype' => 'Discount',
                    'debit' => $discount,
                    'credit' => '0',
                    'description' => "Inv-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment1);


                $datalist_payment2 = array(
                    'voucherid' => $randsellid,
                    'ledgerid' => $customer_id,
                    'date' => $date,
                    'vouchertype' => 'Discount',
                    'debit' => '0',
                    'credit' => $discount,
                    'description' => "Inv-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist_payment2);
            }
        
        if($p==2) {
            $this->db->query("Update ledgerposting set debit='$paidAmount',date='$date' where voucherid='$randsellid' and ledgerid='1' and vouchertype='Received voucher'");
            $this->db->query("Update ledgerposting set credit='$paidAmount',date='$date',ledgerid='$customer_id' where voucherid='$randsellid' and ledgerid='$pcustomer_id' and vouchertype='Received voucher'");
            $this->db->query("Update received set ledgerid=$customer_id,date='$date',amount = $paidAmount where invoiceid='$randsellid'");
        }
        else if($paidAmount>0){
            $cashInHand = 1;

            $datalist = array(
                'voucherid' => $randsellid,
                'ledgerid' => $cashInHand,
                'date' => $date,
                'debit' => $paidAmount,
                'credit' => 0,
                'vouchertype' => 'Received voucher',
                'description' => "Inv-". sprintf("%06d", $dailys->id),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist);
            $datalist2 = array(
                'voucherid' => $randsellid,
                'ledgerid' => $customer_id,
                'date' => $date,
                'debit' => 0,
                'credit' => $paidAmount,
                'vouchertype' => 'Received voucher',
                'description' => "Inv-". sprintf("%06d", $dailys->id),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist2);

            $datalist = array(
                    'invoiceid'=>$randsellid,
                    'ledgerid' => $customer_id,
                    'date' => $date,
                    'amount' => $paidAmount,
                    'user_id' => $this->session->userdata('user_id'),
                    'description' => "Inv-". sprintf("%06d", $dailys->id),
                    'company_id' => $this->session->userdata('company_id'),
                );
                $this->db->insert('received', $datalist);

        }

            savelog("Update Sales", " voucher= $randsellid".",pcustomer_id=$pcustomer_id".",pdate=$pdate".",pdiscount=$pdiscount".",ptotaldue=$ptotaldue".",ppaidAmount=$ppaidAmount".",ppreDue=$ppreDue".",pfTotalDue=$pfTotalDue"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->edit($randsellid);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }
    function deletese($invoiceid){
        
        $data['baseurl'] = $this->config->item('base_url');
        $dailysell =$this->db->query("select s.*,p.category_id,p.available_quantity,p.warning_quantity from daily_sell as s left join products as p on s.product_id=p.id where s.id='$invoiceid'")->row();

        $tarray = json_decode($dailysell->devcomment);
        if(!empty($tarray)){
            foreach($tarray as $id => $row) {
                $this->db->query("update purchase set a_quantity=(a_quantity+'".$row."') where id='$id'");
            } 
        }
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailysell->date > $datef." 00:00:00" && $dailysell->date < $datef." 23:59:59" ))):


        $dailys = $this->db->query("select customer_id,id from daily_sell_summary where voucherid='$dailysell->invoice_id'")->row();

        
        $updateqty = $this->db->query("update products set available_quantity = available_quantity+'$dailysell->quantity' where id = '$dailysell->product_id'");
        //notification decrease
        if($dailysell->warning_quantity>=$dailysell->available_quantity && $dailysell->warning_quantity<$dailysell->available_quantity+$dailysell->quantity)
            $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
        
        
        $p = ($dailysell->quantity * $dailysell->sellprice);

        $this->db->query("Update daily_sell_summary set total_price=total_price-'$p',due=due-'$p' where voucherid='$dailysell->invoice_id'");
        
        $this->db->query("Update ledgerposting set credit=credit-'$p' where voucherid='$dailysell->invoice_id' and ledgerid='2' and vouchertype='sales'");
        $this->db->query("Update ledgerposting set debit=debit-'$p' where voucherid='$dailysell->invoice_id' and ledgerid='$dailys->customer_id' and vouchertype='sales'");

        savelog("Delete Sales product", " voucher= $dailysell->invoice_id".",daily_sell Id=$invoiceid".",quantity=$dailysell->quantity".",price=$dailysell->sellprice".",product_id=$dailysell->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->db->query("delete from daily_sell where id='$invoiceid'");
        $this->edit($dailysell->invoice_id);
        
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }
    function deletesell($voucherid){
        $data['baseurl'] = $this->config->item('base_url');
        $dailys = $this->db->query("select * from daily_sell_summary where voucherid='$voucherid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):

        $dailysell =$this->db->query("select s.*,p.category_id,p.available_quantity,p.warning_quantity from daily_sell as s left join products as p on s.product_id=p.id where s.invoice_id='$voucherid'")->result();
        foreach ($dailysell as $key ) {

            $tarray = json_decode($key->devcomment);
            if(!empty($tarray)){
                foreach($tarray as $id => $row) {
                    $this->db->query("update purchase set a_quantity=(a_quantity+'".$row."') where id='$id'");
                } 
            }
            
                $updateqty = $this->db->query("update products set available_quantity = available_quantity+'$key->quantity' where id = '$key->product_id'");
                
                //notification decrease
                if($key->warning_quantity>=$key->available_quantity && $key->warning_quantity<$key->available_quantity+$key->quantity)
                         $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
            
            savelog("Delete Sales product", " voucher= $key->invoice_id".",daily_sell Id=$key->id".",quantity=$key->quantity".",price=$key->sellprice".",product_id=$key->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        }
        $this->db->query("delete from daily_sell where invoice_id='$voucherid'");
        

        //$this->db->query("Update daily_sell_summary set pre_due=pre_due-'$dailys->due',due=due-'$dailys->due' where customer_id='$dailys->customer_id' and  id > '$dailys->id'");
        $this->db->query("delete from daily_sell_summary where voucherid='$voucherid'");

        $this->db->query("Delete from ledgerposting where voucherid='$voucherid' and (vouchertype='sales' or vouchertype='Discount')");
        $this->db->query("Delete from received where invoiceid='$voucherid'");


        savelog("Delete Sales", " voucher= $voucherid".",pcustomer_id=$dailys->customer_id".",pdate=$dailys->date".",pdiscount=$dailys->discount".",total_price=$dailys->total_price".",paid_amount=$dailys->paid_amount".",due=$dailys->due".",pre_due=$dailys->pre_due"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        redirect('reports/sellhistory');
        
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }
    function spedit(){
        $data['baseurl'] = $this->config->item('base_url');
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select customer_id,id,date from daily_sell_summary where voucherid='$randsellid'")->row();
        $datef= date('Y-m-d');

        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):
        $pquantity=$this->input->post("pquantity");
        $pprice=$this->input->post("pprice");
        $dailysellid=$this->input->post("dailysellid");
        $pproduct_id=$this->input->post("pproduct_id");
        
        $product_id=$this->input->post("product_id");
        $sellprice=$this->input->post("sellprice");
        $quantity=$this->input->post("quantity");
        $category_id = $this->input->post("category_id");

        $pcategory_id = $this->input->post("pcategory_id");


        $product = $this->db->query("select * from products where id='$product_id'")->row();
        $pproduct = $this->db->query("select * from products where id='$pproduct_id'")->row();
        
            $this->db->query("update products set available_quantity = available_quantity+'$pquantity' where id = '$pproduct_id'");
            $dailysell = $this->db->query("select * from daily_sell where id ='$dailysellid'")->row();
            $tarray = json_decode($dailysell->devcomment);
            if(!empty($tarray)){
                foreach($tarray as $id => $row) {
                    $this->db->query("update purchase set a_quantity=(a_quantity+'".$row."') where id='$id'");
                } 
            }
            //Notificcation decrease
            if($pproduct->warning_quantity>=$pproduct->available_quantity && $pproduct->warning_quantity<$pproduct->available_quantity+$pquantity)
            $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
        
            $this->db->query("update products set available_quantity = available_quantity-'$quantity' where id = '$product_id'");

            if($category_id!=6){
                $purchase = $this->db->query("select id,a_quantity,buyprice from purchase where product_id='$product_id' and a_quantity>0 order by id asc")->result();
                $devcomment = array();
                $profit= 0;
                $tquantity= $quantity;
                foreach ($purchase as $pu){
                    if($pu->a_quantity>=$tquantity){
                        $this->db->query("update purchase set a_quantity ='".($pu->a_quantity-$tquantity)."' where id='$pu->id'");
                        $profit+=(($sellprice-$pu->buyprice) * $tquantity);
                        $devcomment[$pu->id] = $tquantity;
                        $lastquantity = $tquantity;
                        $lastbuyprice = $pu->buyprice;
                        $tquantity =0;
                        break;
                    }
                    else {
                        $this->db->query("update purchase set a_quantity =0 where id='$pu->id'");
                        $profit+=(($sellprice-$pu->buyprice) * $pu->a_quantity);
                        $devcomment[$pu->id] = $pu->a_quantity;
                        $tquantity -= $pu->a_quantity;
                    }
                }
                if($tquantity!=0){
                    $profit+=(($sellprice-$product->opening_price) * $tquantity);
                    $lastquantity = $tquantity;
                    $lastbuyprice = $product->opening_price;
                }
            }
            else{
                $profit = ($sellprice - $product->purchase_price) * $quantity;
                $lastbuyprice= $product->purchase_price;
                $lastquantity=$quantity;
                $devcomment = array();
            }

            //Notificcation increase
            if($product->warning_quantity<$product->available_quantity && $product->warning_quantity>=$product->available_quantity-$quantity)
                         $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
        

        $devcomment = json_encode($devcomment);
        
        $this->db->query("Update daily_sell set product_id='$product_id',sellprice='$sellprice',quantity='$quantity',unit='$product->unit',profit='$profit',lastbuyprice='$lastbuyprice',lastquantity='$lastquantity',devcomment	='$devcomment' where id='$dailysellid'");

        $p = ($sellprice * $quantity) - ($pprice * $pquantity);
        $this->db->query("Update daily_sell_summary set total_price=total_price+'$p',due=due+'$p' where voucherid='$randsellid'");
        
        $this->db->query("Update ledgerposting set credit=credit+'$p' where voucherid='$randsellid' and ledgerid='2' and vouchertype='sales'");
        $this->db->query("Update ledgerposting set debit=debit+'$p' where voucherid='$randsellid' and ledgerid='$dailys->customer_id' and vouchertype='sales'");

        savelog("Update Sales product", " voucher= $randsellid".",daily_sell Id=$dailysellid".",pquantity=$pquantity".",pprice=$pprice".",pproduct_id=$pproduct_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        $this->edit($randsellid);

        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    // ----------------------Notification-----------------
    function notification(){
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'notification';
        $data['page_title'] = 'Notification';
        $data['baseurl'] = $this->config->item('base_url');
        $companyid = $this->session->userdata('company_id');
        
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['product'] = $this->db->query("select p.*,u.name  as unit_name,au.fullname,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id left join alluser as au on p.user_id=au.id where p.company_id='$companyid' and p.available_quantity <= p.warning_quantity and p.status=1 order by p.product_name asc")->result();

            $this->load->view('report_derectory/notification', $data);
        else:
        $this->load->view('login',$data);
        endif;
    }

    public function delete_notification(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('role') == 'admin'):
            $id =  $this->input->post('notificationid');
            $this->db->query("delete from notification where id='$id'");
         
        endif;
    }

    public function exports_notification(){
        $companyid = $this->session->userdata('company_id');
        
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $product = $this->db->query("select p.*,u.name  as unit_name,au.fullname,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id left join alluser as au on p.user_id=au.id where p.company_id='$companyid' and p.available_quantity <= p.warning_quantity and p.status=1 order by p.product_name asc")->result();

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Notification".date('Y-m-d H:i:s').".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Name","Product ID","Category","Sub Category","Available Quantity","Warning Quantity","Unit"); 
            fputcsv($handle, $header);

            foreach ($product as $prodata){  
               $data = chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE", $prodata->product_name);
               $arr[0]=substr($data,2);
               $arr[1]= $prodata->product_id;   
               $arr[2]= $prodata->category_name;   
               $arr[3]= $prodata->sub_category; 
               $arr[4]=number_format(($prodata->available_quantity),2);
               $arr[5]=number_format(($prodata->warning_quantity),2); 
               $arr[6]=$prodata->unit_name; 
 
               fputcsv($handle, $arr);         
            }
            
            fclose($handle);
            exit;

        else:
        $this->load->view('login',$data);
        endif;
    }


    // ----------------------ajax-----------------
    function getcustomerdetails() {

        $ledgerid = $this->input->post('ledgerid');
        $ledgerdata = $this->db->query("select * from accountledger where id = '$ledgerid'")->row();
        
        $lPosting = $this->db->query("select sum(debit) as aDebit,sum(credit) as aCredit from ledgerposting where ledgerid='$ledgerid'")->row();

        $debit = $ledgerdata->debit+$lPosting->aDebit;
        $credit = $ledgerdata->credit+$lPosting->aCredit;
            $ldata = array(
                'due' => $debit-$credit
            );
        echo json_encode($ldata);
    }

    // ------------------excel export------------- 
    function export_sellhistory($sdate1,$edate1,$cname) {
        $company_id = $this->session->userdata('company_id');
        
        $sdate =  $sdate1. ' 00:00:00';
        $edate =  $edate1. ' 23:59:59';

        $data['customerlist'] = $this->db->query("select l.ledgername,l.id,l.address,l.mobile,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id='$cname'  and l.status<>0")->row();

        if($cname=='all'){
            $customer = 'All Customer';
            $selldata = $this->db->query("select d.*,l.ledgername as customer_name,l.mobile,l.address,l.father_name,di.name as district_name,al.fullname from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as al on d.user_id=al.id WHERE d.company_id = '$company_id' and d.date between '$sdate' and '$edate'")->result();
        }
        else {
            $customer=$data['customerlist']->ledgername;
            $selldata = $this->db->query("select d.*,l.ledgername as customer_name,l.mobile,l.address,l.father_name,di.name as district_name,al.fullname from daily_sell_summary as d left join accountledger as l on d.customer_id=l.id left join districts as di on l.district=di.id left join alluser as al on d.user_id=al.id WHERE d.customer_id='$cname' and d.company_id = '$company_id' and d.date between '$sdate' and '$edate'")->result();
        }


        header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Sales Summary.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Voucher ID","Date","Party","Father's Name","Mobile No.","Total Bill","Payment","Due","Inserted By"); 
            fputcsv($handle, $header);

            foreach ($selldata as $sell){  
               
               $arr[0]= "Inv-".sprintf("%06d", $sell->id);
               $arr[1]= $sell->date;   
               $arr[2]= $sell->customer_name." (".$sell->address.", ".$sell->district_name.")"; 
               $arr[3]= $sell->father_name; 
               $arr[4]= $sell->mobile;   
               $arr[5]= number_format($sell->total_price - $sell->discount, 2); 
               $arr[6]= number_format($sell->paid_amount, 2);
               $arr[7]= number_format($sell->total_price - $sell->discount-$sell->paid_amount, 2); 
               $arr[8]= $sell->fullname; 
 
               fputcsv($handle, $arr);         
            }
            
            fclose($handle);
            exit;
    }

    function export_purchasehistory($sdate1,$edate1,$cname) {
        $company_id = $this->session->userdata('company_id');
        
        $sdate =  $sdate1. ' 00:00:00';
        $edate =  $edate1. ' 23:59:59';

        if($cname=='all'){
            $purchasedata = $this->db->query("select p.*,l.ledgername,l.address,l.father_name,l.mobile,d.name as district_name,al.fullname from purchase_summary as p left join accountledger as l on p.supplier_id=l.id left join districts as d on l.district=d.id left join alluser as al on p.user_id=al.id  where p.company_id = '$company_id' and p.date between '$sdate' and '$edate' order by p.id desc")->result();
        }
        else{
            $purchasedata = $this->db->query("select p.*,l.ledgername,l.address,l.father_name,l.mobile,d.name as district_name,al.fullname from purchase_summary as p left join accountledger as l on p.supplier_id=l.id left join districts as d on l.district=d.id left join alluser as al on p.user_id=al.id where p.company_id = '$company_id' and p.date between '$sdate' and '$edate' and p.supplier_id='$cname' order by p.id desc")->result();
        }


            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Purchase Summary.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Invoice ID","Date","Party","Father's Name","Mobile No.","Total Bill","Payment","Due","Comment","Inserted By"); 
            fputcsv($handle, $header);

            foreach ($purchasedata as $buy){  

               $totalpurchase = $buy->total_purchase-$buy->discount;
               
               $arr[0]= "Pur-".sprintf("%06d", $buy->id);
               $arr[1]= $buy->date;   
               $arr[2]= $buy->ledgername." (".$buy->address.", ".$buy->district_name.")";    
               $arr[3]= $buy->father_name;
               $arr[4]= $buy->mobile;
               $arr[5]= number_format($totalpurchase, 2); 
               $arr[6]= number_format($buy->payment, 2); 
               $arr[7]= number_format($totalpurchase-$buy->payment, 2); 
               $arr[8]= $buy->Description;
               $arr[9]= $buy->fullname;
              
               fputcsv($handle, $arr);         
            }
            
            fclose($handle);
            exit;
    }

    function export_selldetails($sdate1,$edate1,$cname) {
        $company_id = $this->session->userdata('company_id');
        
        $sdate =  $sdate1. ' 00:00:00';
        $edate =  $edate1. ' 23:59:59';

        if($cname=='all'){
            $selldata = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,l.father_name,l.mobile,di.name as district_name,u.name as unit, ds.id as sellid from daily_sell as d left join products as p on d.product_id=p.id left join accountledger as l on d.customer_id=l.id left join product_unit as u on d.unit=u.id left join daily_sell_summary as ds on d.invoice_id=ds.voucherid left join districts as di on l.district=di.id   where d.date between '$sdate' AND '$edate' AND d.company_id = '$company_id'")->result();
        }
        else{
            $selldata = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,l.father_name,l.mobile,di.name as district_name,u.name as unit, ds.id as sellid from daily_sell as d left join products as p on d.product_id=p.id left join accountledger as l on d.customer_id=l.id left join product_unit as u on d.unit=u.id left join daily_sell_summary as ds on d.invoice_id=ds.voucherid left join districts as di on l.district=di.id   where d.date between '$sdate' AND '$edate' AND d.company_id = '$company_id' and d.customer_id='$cname'")->result();
        }

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Sales Details.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
       
        $header = array("Date","Invoice ID","Product Name","Customer Name","Comment","Quantity","Unit Price","Total Price"); 
        fputcsv($handle, $header);

        foreach ($selldata as $sell){  

           $netprice = $sell->quantity * $sell->sellprice;
           $date=date_create($sell->date);

           $pname = $sell->product_name; 
           
           $arr[0]= date_format($date,"d-m-Y H:i:s");
           $arr[1]= "Inv-". sprintf("%06d", $sell->sellid);  
           $arr[2]= $pname;  
           $arr[3]= $sell->ledgername." (".$sell->address.", ".$sell->district_name.")";
           $arr[4]= $sell->comment;
           $arr[5]= number_format($sell->quantity,2) . $sell->unit;
           $arr[6]= number_format($sell->sellprice,2);
           $arr[7]= number_format($sell->sellprice*$sell->quantity, 2);
          
           fputcsv($handle, $arr);         
        }
        
        fclose($handle);
        exit;
    }

    function export_purchasedetails($sdate1,$edate1,$cname='all') {
        $company_id = $this->session->userdata('company_id');
        
        $sdate =  $sdate1. ' 00:00:00';
        $edate =  $edate1. ' 23:59:59';

        if($cname=='all'){
            $selldata = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,l.father_name,l.mobile,di.name as district_name,u.name as unit, ds.id as sellid from purchase as d left join products as p on d.product_id=p.id left join accountledger as l on d.supplier_id=l.id left join product_unit as u on d.unit=u.id left join purchase_summary as ds on d.invoiceid=ds.invoiceid left join districts as di on l.district=di.id   where d.date between '$sdate' AND '$edate' AND d.company_id = '$company_id'")->result();
        }
        else{
            $selldata = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,l.father_name,l.mobile,di.name as district_name,u.name as unit, ds.id as sellid from purchase as d left join products as p on d.product_id=p.id left join accountledger as l on d.supplier_id=l.id left join product_unit as u on d.unit=u.id left join purchase_summary as ds on d.invoiceid=ds.invoiceid left join districts as di on l.district=di.id   where d.date between '$sdate' AND '$edate' AND d.company_id = '$company_id' and d.supplier_id='$cname'")->result();
        }

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Purchase Details.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
       
        $header = array("Date","Invoice ID","Product Name","Supplier Name","Quantity","Unit Price","Total Price"); 
        fputcsv($handle, $header);
        if(count($selldata)!=0):
            foreach ($selldata as $sell){  

            $netprice = $sell->quantity * $sell->buyprice;
            $date=date_create($sell->date);

            $pname = $sell->product_name; 
            if($sell->comment!=""){
                $pname.= "(".$sell->comment.")";
            }
            
            $arr[0]= date_format($date,"d-m-Y H:i:s");
            $arr[1]= "Pur-". sprintf("%06d", $sell->sellid);  
            $arr[2]= $pname;  
            $arr[3]= $sell->ledgername." (".$sell->address.", ".$sell->district_name.")"; 
            $arr[4]= $sell->quantity . $sell->unit;
            $arr[5]= $sell->buyprice;
            $arr[6]= number_format($sell->buyprice*$sell->quantity, 2);
            
            fputcsv($handle, $arr);         
            }
        endif;
        
        fclose($handle);
        exit;
    }

    function export_payment($sdate1,$edate1) {
        $company_id = $this->session->userdata('company_id');
        
        $sdate =  $sdate1. ' 00:00:00';
        $edate =  $edate1. ' 23:59:59';
        
        $payments = $this->db->query("select p.*,u.fullname from payments as p left join alluser as u on p.user_id=u.id where p.company_id = '$company_id' and p.date between '$sdate' and '$edate' order by p.id desc ")->result();


        header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Payment.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Date","Ledger Name","Fathe's Name","Mobile No.","Amount","Comment","Inserted By"); 
            fputcsv($handle, $header);
            $totalamount=0;
            foreach ($payments as $pay){
                $totalamount = $totalamount + $pay->amount;
                $party =$this->db->query("select a.ledgername,a.address,a.father_name,a.mobile,accountgroupid,d.name as district_name from accountledger as a left join districts as d on a.district=d.id where a.id='$pay->ledgerid'")->row();
               
               $arr[0]= "Pay-".sprintf("%06d", $pay->id);
               $arr[1]= ($party->accountgroupid==16||$party->accountgroupid==15)? $party->ledgername." (".$party->address.", ".$party->district_name.")":$party->ledgername;  
               
               $arr[2]= $party->father_name;
               $arr[3]= $party->mobile;
               $arr[4]= $pay->amount;
               $arr[5]= $pay->description;
               $arr[6]= $pay->fullname;
               fputcsv($handle, $arr);         
            }

            $header2 = array("","","","Total",$totalamount,"",""); 
            fputcsv($handle, $header2);
            
            fclose($handle);
            exit;
    }

    function export_received($sdate1,$edate1) {
        $company_id = $this->session->userdata('company_id');
        
        $sdate =  $sdate1. ' 00:00:00';
        $edate =  $edate1. ' 23:59:59';
        
        $payments = $this->db->query("select p.*,u.fullname from received as p left join alluser as u on p.user_id=u.id where p.company_id = '$company_id' and p.date between '$sdate' and '$edate' order by p.id desc ")->result();


        header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Received.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Date","Ledger Name","Fathe's Name","Mobile No.","Amount","Comment","Inserted By"); 
            fputcsv($handle, $header);
            $totalamount=0;
            foreach ($payments as $pay){
                $totalamount = $totalamount + $pay->amount;
                $party =$this->db->query("select a.ledgername,a.address,a.father_name,a.mobile,accountgroupid,d.name as district_name from accountledger as a left join districts as d on a.district=d.id where a.id='$pay->ledgerid'")->row();
               
               $arr[0]= "Rec-".sprintf("%06d", $pay->id);
               $arr[1]= ($party->accountgroupid==16||$party->accountgroupid==15)? $party->ledgername." (".$party->address.", ".$party->district_name.")":$party->ledgername;  
               
               $arr[2]= $party->father_name;
               $arr[3]= $party->mobile;
               $arr[4]= $pay->amount;
               $arr[5]= $pay->description;
               $arr[6]= $pay->fullname;
               fputcsv($handle, $arr);         
            }

            $header2 = array("","","","Total",$totalamount,"",""); 
            fputcsv($handle, $header2);
            
            fclose($handle);
            exit;
    }

    function export_salesreturn($sdate1,$edate1) {
        $company_id = $this->session->userdata('company_id');
        
        $sdate =  $sdate1. ' 00:00:00';
        $edate =  $edate1. ' 23:59:59';
        
        $sellreurn = $this->db->query("select p.*,l.ledgername,l.address,l.father_name,l.mobile,d.name as district_name,u.fullname from sell_return_summary as p left join accountledger as l on p.customer_id=l.id left join districts as d on l.district=d.id left join alluser as u on p.user_id=u.id where p.date between '$sdate' and '$edate'")->result();


            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"Sales Return.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
           
            $header = array("Invoice Id","Date","Customer Name","Father's Name","Mobile No.","Total Price","Payment","Due","Inserted By"); 
            fputcsv($handle, $header);
            $totalamount=0;
            foreach ($sellreurn as $sell){
                
               $arr[0]= "Sr-".sprintf("%06d", $sell->id);
               $arr[1]= $sell->date;  
               
               $arr[2]= $sell->ledgername."(".$sell->address.",".$sell->district_name.")";
               $arr[3]= $sell->father_name;
               $arr[4]= $sell->mobile;
               $arr[5]= $sell->total_purchase;
               $arr[6]= $sell->payment;
               $arr[7]= number_format($sell->total_purchase-$sell->payment,2) ;
               $arr[8]= $sell->fullname;
               fputcsv($handle, $arr);         
            }
            
            fclose($handle);
            exit;
    }
    
}

?>