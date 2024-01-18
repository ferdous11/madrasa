<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {

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
        $data['activesubmenu'] = 'purchase';

        $data['page_title'] = 'Purchase products';
        
        $data['randomkey'] = time();
        $data['purchasedata'] = array();
        $data['suppliers'] = '';
        $data['date'] = date('Y-m-d H:i:s');
        $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
        $data['class_id']='';
        $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp group by randomkey order by randomkey desc")->result();
        
        $data['productlist']=$this->db->query("select id,product_name from products where class_id='1' order by product_name asc")->result();
        
        
            $this->load->view('purchase_derectory/purchase', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function save_temp() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'purchase';
            $data['page_title'] = 'Purchase products';
            $data['date'] = $this->input->post('date');
            $data['baseurl'] = $this->config->item('base_url');
            $data['randomkey']=$randomkey = $this->input->post('randomkey');
            $data['suppliers']=$this->input->post('suppliers');
            $bprice = ($this->input->post('buyprice'));
            $sales_price = ($this->input->post('sales_price'));
            $quantity = ($this->input->post('quantity'));
            $product_id = $this->input->post('product_id');
            $comment = $this->input->post('comment');
            $unit_id = $this->input->post('unit_id');

            

            $data['class_id']=$class_id = $this->input->post('class_id');
           
            $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
            $data['productlist']=$this->db->query("select id,product_name from products where class_id =".$data['class_id']. " order by product_name asc")->result();

            
            $datar = array(
                'randomkey'=>$randomkey,
                'product_id'=>$product_id,
                'unit_price'=>$bprice,
                'qty'=>$quantity,
                'sales_price'=>$sales_price,
                'comment' => $comment,
                'unit_id' => $unit_id,
            );
            $this->db->insert('temp', $datar);

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where  t.randomkey='$randomkey'")->result();

            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp group by randomkey order by randomkey desc")->result();

            $this->load->view('purchase_derectory/purchase', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function removedata() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $id = $_GET['id'];
           
            $class_id=$data['class_id'] = $_GET['class_id'];

            $data['date'] = $_GET['date'];
            $data['suppliers'] = $_GET['suppliers'];
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'purchase';
            $data['page_title'] = 'Purchase products';
            $data['baseurl'] = $this->config->item('base_url');
            $data['randomkey']=$randomkey = $_GET['randomkey'];

            
            $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
              
            $data['productlist']=$this->db->query("select id,product_name from products where  class_id =".$data['class_id']. " order by product_name asc")->result();
            $this->db->query("DELETE from temp where id='$id'");

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where t.randomkey='$randomkey'")->result();
            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp group by randomkey order by randomkey desc")->result();
            
            $this->load->view('purchase_derectory/purchase', $data);
        else:
            $this->load->view('login', $data);
        endif;

    }

    function savepurchase(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):

        $product_idl=$this->input->post('product_idl');
        if($product_idl===null){
            //$this->session->set_userdata('success', 'Sales updated successfully');
           
            $this->session->set_userdata('failed', 'Please Insert Product');
             redirect('Purchase');
        }
        $comment = $this->input->post('comment');
        $buyprice=$this->input->post('buyprice');
        $sales_price=$this->input->post('sales_price');
        $qtyl=$this->input->post('qtyl');
        $supplier_id = $this->input->post('suppliers');
        $unitl = $this->input->post('unitl');
        $date = $this->input->post('date');
        $data['randomkey']=$randomkey = $this->input->post('randomkey');
        $t_price=$this->input->post('t_price');
        $shippingcost=$this->input->post('shippingcost');
        $othercost=$this->input->post('othercost');
        $labourcost=$this->input->post('labourcost');
        $payment=$this->input->post('payment');
        $discount=$this->input->post('discount');
        $user_id = $this->session->userdata('user_id');

    


        $pamount = $t_price;
        $amount=$shippingcost+$othercost+$labourcost+$pamount;

        $datalist = array(
            'invoiceid' => $randomkey,
            'total_purchase' =>$t_price ,
            'date'=>$date,
            'payment'=>$payment,
            'supplier_id' => $supplier_id,
            'user_id' => $user_id
        );   
        $this->db->insert('purchase_summary', $datalist);   

        for ($i = 0; $i < count($product_idl); $i++) {
            $id = $product_idl[$i];
            $getpdetails = $this->db->query("select * from products where id = '$id'")->row();
            $total_quantity= $getpdetails->total_quantity + $qtyl[$i];
            $lastquantity = $getpdetails->available_quantity + $qtyl[$i];
            $datalist = array(
                'invoiceid' => $randomkey,
                'product_id' => $product_idl[$i],
                'supplier_id'=> $supplier_id,
                'buyprice'=> $buyprice[$i],
                'quantity'=>$qtyl[$i],
                'a_quantity'=>$qtyl[$i],
                'date' => $date,
                'comment' => $comment[$i]
            );   
            $this->db->insert('purchase', $datalist);
                
            $this->db->query("update products set available_quantity = '$lastquantity',total_quantity='$total_quantity',purchase_price='$buyprice[$i]',sale_price='$sales_price[$i]' where id = '$id'");
                    if($getpdetails->warning_quantity>=$getpdetails->available_quantity && $getpdetails->warning_quantity<$getpdetails->available_quantity+$qtyl[$i])
                            $this->session->set_userdata('notification',$this->session->userdata('notification')-1); 
        }

        $this->db->query("delete from temp where randomkey = '$randomkey'");
        // -----------------------insert purchase_summary ----------------
        
        

        //------------------------//

        $purchase_summary_id=$this->db->insert_id();
        $d_ledgerid = 3;    

        // -----------------------insert supplier and purcase ledger ----------------
        $datalist_payment1 = array(
            'voucherid' => $randomkey,
            'ledgerid' => $d_ledgerid,
            'date' =>  $this->input->post('date'),
            'vouchertype' => 'purchase',
            'debit' => $pamount,
            'credit' => '0',
            'description' => "Pur-". sprintf("%06d", $purchase_summary_id),
            'user_id' => $user_id,
        );
        $this->db->insert('ledgerposting', $datalist_payment1);


        $datalist_payment2 = array(
            'voucherid' => $randomkey,
            'ledgerid' => $supplier_id,
            'date' => $this->input->post('date'),
            'vouchertype' => 'purchase',
            'debit' => '0',
            'credit' => $amount,
            'description' => "Pur-". sprintf("%06d", $purchase_summary_id),
            'user_id' => $user_id
        );
        $this->db->insert('ledgerposting', $datalist_payment2);

        // -----------------------payment----------------
        if($payment>0):
            $datalist = array(
                'invoiceid'=>$randomkey,
                'ledgerid' => $supplier_id,
                'date' => $date,
                'amount' => $payment,
                'description' => "Pur-". sprintf("%06d", $purchase_summary_id),
                'user_id' => $this->session->userdata('user_id')
            );
            $this->db->insert('payments', $datalist);
            $lastid = $this->db->insert_id();

            //$this->db->where('id', $lastid);
            //$this->db->update('payments', array('invoiceid' => $lastid));

            $datalist = array(
                'voucherid' => $lastid,
                'ledgerid' => 1,
                'date' => $date,
                'debit' => 0,
                'credit' => $payment,
                'vouchertype' => 'Payment voucher',
                'description' => "Pur-". sprintf("%06d", $purchase_summary_id),
                'user_id' => $user_id
            );

            $this->db->insert('ledgerposting', $datalist);

            $datalist2 = array(
                'voucherid' => $lastid,
                'ledgerid' => $supplier_id,
                'date' => $date,
                'debit' => $payment,
                'credit' => 0,
                'vouchertype' => 'Payment voucher',
                'description' => "Pur-". sprintf("%06d", $purchase_summary_id),
                'user_id' => $user_id
            );
            $this->db->insert('ledgerposting', $datalist2);
        endif;

        $this->session->set_userdata('success', 'Purchase completed successfully.');
        //clean temp data
        redirect('purchase/detailspurchase/'.$randomkey);
        else:
        $this->load->view('login', $data);
        endif;    
    }

    function findpurchase() {
        $purchaseid = $this->input->post('invoiceid');
        if (is_numeric($purchaseid)):
            $data['invoiceid'] = $purchaseid;
            $data['purchasedata'] = $this->db->query("select p.*,u.name,i.pname from purchase as p left join products as i on p.product_id=i.id left join product_unit as u on i.unit_id=u.id   where p.invoiceid = '$purchaseid'")->result();
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'purchase_return';
            $data['page_title'] = 'Purchase products';
            $data['baseurl'] = $this->config->item('base_url');
            $this->load->view('purchase_return', $data);
        else:
            redirect('purchase');
        endif;
    }

    function tempremove(){
        if ($this->session->userdata('loggedin') == 'yes'):
        $randomkey = $_GET['randomkey'];
        $this->db->query("DELETE FROM temp WHERE randomkey='$randomkey'");
            return $this->index();
        else:
            $this->load->view('login', $data);
        endif;
    }

    function showtemp(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $randomkey = $_GET['randomkey'];
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'purchase';
            $data['page_title'] = 'Purchase products';
            $data['date'] = date("Y-m-d");
            $data['baseurl'] = $this->config->item('base_url');
            $data['randomkey']=$randomkey;

            //$data['uncomlitelist'] = $this->db->query("select randsellid,sum(total_price) as tprice, count(product_id) as titem from temp group by randsellid order by randsellid desc")->result();

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where  t.randomkey='$randomkey' order by t.id desc")->result();
            
            $pid= $data['purchasedata'][0]->product_id;
          
            $product = $this->db->query("select * from products where id='$pid'")->row();

            $data['class_id']=$product->class_id;
            
            $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
            $data['productlist'] = array();
            $data['suppliers'] = '';

            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp group by randomkey order by randomkey desc")->result();

            
            $this->load->view('purchase_derectory/purchase', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    //----------------- Update Purcchase? ------------

    function deletepurchase($voucherid){
        $data['baseurl'] = $this->config->item('base_url');
        $dailys = $this->db->query("select * from purchase_summary where invoiceid='$voucherid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):

        $dailysell =$this->db->query("select s.*,p.class_id,p.available_quantity,p.warning_quantity from purchase as s left join products as p on s.product_id=p.id where s.invoiceid='$voucherid'")->result();

        $ischange = $this->db->query("select * from purchase where quantity <> a_quantity and invoiceid='$voucherid'")->result();

        if($ischange!=null){
            $this->session->set_userdata('failed','Already You Have Sold This Purchase Items');
            redirect('purchase/purchasehistory');
            return 0;
        }


        foreach ($dailysell as $key ) {
            
            $updateqty = $this->db->query("update products set available_quantity = available_quantity-'$key->quantity' where id = '$key->product_id'");
            //Notificcation increase
            if($key->warning_quantity<$key->available_quantity && $key->warning_quantity>=$key->available_quantity-$key->quantity)
                $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
            
            
            savelog("Delete Sales product", " voucher= $voucherid".",daily_sell Id=$key->id".",quantity=$key->quantity".",buyprice=$key->buyprice".",product_id=$key->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        }
        $this->db->query("delete from purchase where invoiceid='$voucherid'");
        
        
        $this->db->query("delete from purchase_summary where invoiceid='$voucherid'");

        $this->db->query("Delete from ledgerposting where voucherid='$voucherid' and (vouchertype='purchase' or vouchertype='P Shipping Cost' or vouchertype='P Other Cost' or vouchertype='P Discount'  or vouchertype='P Labour Cost' or vouchertype='Payment voucher')");
        $dpayment = $this->db->query("select id from payments where invoiceid='$voucherid'")->row();
        if(isset($dpayment)){
            $this->db->query("delete from payments where invoiceid='$voucherid'");
            $this->db->query("Delete from ledgerposting where voucherid='$dpayment->id' and vouchertype='Payment voucher'");
        }

        savelog("Delete Purchase", " voucher= $voucherid".",supplier_id=$dailys->supplier_id".",total_purchase=$dailys->total_purchase".",shipping_cost=$dailys->shipping_cost".",other_cost=$dailys->other_cost".",labour_cost=$dailys->labour_cost".",discount=$dailys->discount".",Description=$dailys->Description".",date=$dailys->date"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        redirect('purchase/purchasehistory');
        
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    function edit_purchase_view($invoiceid){

        $data['baseurl'] = $this->config->item('base_url');
        $data['purchasesummary']=$this->db->query("select * from purchase_summary where invoiceid='$invoiceid'")->row();
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($data['purchasesummary']->date > $datef." 00:00:00" && $data['purchasesummary']->date < $datef." 23:59:59" ))):
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'purchasehistory';
        $data['baseurl'] = $this->config->item('base_url');
        $data['randsellid']=$invoiceid;
        $data['class_id']=1;
  
        
        $data['supplier'] = $data['purchasesummary']->supplier_id;
        $data['productlist']=$this->db->query("select id,product_name,class_id from products order by product_name asc")->result();
        $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();

        $data['purchasedata'] = $data['selldata'] = $this->db->query("select s.*,p.product_name as name,p.class_id,p.unit as unit_id ,u.name as unit_name from purchase as s left join products as p on s.product_id=p.id left join product_unit as u on p.unit_id=u.id where s.invoiceid='$invoiceid' order by id desc")->result();
        $data['getsupplier'] = $this->db->query("select id,ledgername from accountledger where accountgroupid = '5' order by ledgername")->result();
            $this->load->view('purchase_derectory/editpurchase',$data);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    function deletesp($invoiceid){
        $data['baseurl'] = $this->config->item('base_url');
        $dailysell =$this->db->query("select s.*,p.class_id,p.available_quantity,p.warning_quantity from purchase as s left join products as p on s.product_id=p.id where s.id='$invoiceid'")->row();
        if($dailysell->quantity!=$dailysell->a_quantity){
            $this->session->set_userdata('failed','Already You Have Sold This Purchase Items');
            $this->edit_purchase_view($dailysell->invoiceid); 
            return 0;
        }
        $datef= date('Y-m-d');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailysell->date > $datef." 00:00:00" && $dailysell->date < $datef." 23:59:59" ))):
        $dailys = $this->db->query("select supplier_id,id from purchase_summary where invoiceid='$dailysell->invoiceid'")->row();
        
        $updateqty = $this->db->query("update products set available_quantity = available_quantity-'$dailysell->quantity' where id = '$dailysell->product_id'");
        //Notificcation increase
        if($dailysell->warning_quantity<$dailysell->available_quantity && $dailysell->warning_quantity>=$dailysell->available_quantity-$dailysell->quantity)
            $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
        
        $p = ($dailysell->quantity * $dailysell->buyprice);

        $this->db->query("Update purchase_summary set total_purchase=total_purchase-'$p' where invoiceid='$dailysell->invoiceid'");
        $this->db->query("Update ledgerposting set debit=debit-'$p' where voucherid='$dailysell->invoiceid' and ledgerid='3' and vouchertype='purchase'");
        $this->db->query("Update ledgerposting set credit=credit-'$p' where voucherid='$dailysell->invoiceid' and ledgerid='$dailys->supplier_id' and vouchertype='purchase'");

        savelog("Delete Purchase product", " voucher= $dailysell->invoiceid".",daily_purchase_Id=$invoiceid".",quantity=$dailysell->quantity".",price=$dailysell->buyprice".",product_id=$dailysell->product_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->db->query("delete from purchase where id='$invoiceid'");
        $this->edit_purchase_view($dailysell->invoiceid); 
        
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    } 

    function sppedit(){
        $data['baseurl'] = $this->config->item('base_url');
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select supplier_id,id,date from purchase_summary where invoiceid='$randsellid'")->row();
        $datef= date('Y-m-d');

        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date > $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):
        $pquantity=$this->input->post("pquantity");
        $quantity=$this->input->post("quantity");

        $pprice=$this->input->post("pprice");
        $sellprice=$this->input->post("buyprice");

        $product_id=$this->input->post("product_id");
        $pproduct_id=$this->input->post("pproduct_id");

        $randsellid=$this->input->post("randsellid");
        $dailysellid=$this->input->post("dailysellid");
        $class_id = $this->input->post("class_id");

        $pclass_id = $this->input->post("pclass_id");

        $product = $this->db->query("select * from products where id='$product_id'")->row();
        $pproduct = $this->db->query("select * from products where id='$pproduct_id'")->row();
        
        $updateqty = $this->db->query("update products set available_quantity = available_quantity-'$pquantity' where id = '$pproduct_id'");
        //Notificcation increase
        if($pproduct->warning_quantity<$pproduct->available_quantity && $pproduct->warning_quantity>=$pproduct->available_quantity-$pquantity)
        $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
        
        
        $updateqty = $this->db->query("update products set available_quantity = available_quantity+'$quantity' where id = '$product_id'");
        //Notificcation decrease
        if($product->warning_quantity>=$product->available_quantity && $product->warning_quantity<$product->available_quantity+$quantity)
            $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
        
        
        $this->db->query("Update purchase set product_id='$product_id',buyprice='$sellprice',quantity='$quantity',unit_id='$product->unit' where id='$dailysellid'");

        $p = ($sellprice * $quantity) - ($pprice * $pquantity);

        $this->db->query("Update purchase_summary set total_purchase=total_purchase+'$p' where invoiceid='$randsellid'");
        
        $this->db->query("Update ledgerposting set debit=debit+'$p' where voucherid='$randsellid' and ledgerid='3' and vouchertype='purchase'");
        $this->db->query("Update ledgerposting set credit=credit+'$p' where voucherid='$randsellid' and ledgerid='$dailys->supplier_id' and vouchertype='purchase'");

        savelog("Update Purchase product", " voucher= $randsellid".",daily_Purchase Id=$dailysellid".",pquantity=$pquantity".",pprice=$pprice".",pproduct_id=$pproduct_id"." Browser " . $_SERVER['HTTP_USER_AGENT']);

        $this->edit_purchase_view($randsellid);

        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    function updatepurchase(){
        $randsellid=$this->input->post("randsellid");
        $dailys = $this->db->query("select id,date from purchase_summary where invoiceid='$randsellid'")->row();
        $datef= date('Y-m-d');
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && ($this->session->userdata('role') == 'admin'||($dailys->date >= $datef." 00:00:00" && $dailys->date < $datef." 23:59:59" ))):
        $supplier_id = $this->input->post('supplier_id');
        $psupplier_id = $this->input->post('psupplier_id');

        $date = $this->input->post('date');
        $pdate = $this->input->post('pdate');
        $payment = $this->input->post('payment');
        $ppayment = $this->input->post('ppayment');
        $total = $this->input->post('total');
        $totalprice=$this->input->post('totalprice');
        $comments = $this->input->post('comments');
        $pcomments = $this->input->post('pcomments');
        $randsellid = $this->input->post('randsellid');
        $p = $totalprice;
        
        
        
        // ----update purchase date and supplier
        $this->db->query("Update purchase set supplier_id='$supplier_id' ,date='$date' Where   invoiceid='$randsellid'");
        // ----update purchase summary date, supplier, cost etc;
        $this->db->query("Update purchase_summary set supplier_id=$supplier_id,date='$date',payment = '$payment' where invoiceid='$randsellid'");
        //find ledger 
        $pay = $this->db->query("select count(id) as d from payments  where invoiceid='$randsellid' and ledgerid='$psupplier_id'")->row()->d;
        

        //-----Update ledgerposting for purchase
        $this->db->query("Update ledgerposting set debit='$p',date='$date' where voucherid='$randsellid' and ledgerid='3' and vouchertype='purchase'");
        $this->db->query("Update ledgerposting set credit='$total',date='$date',ledgerid='$supplier_id' where voucherid='$randsellid' and ledgerid='$psupplier_id' and vouchertype='purchase'");
        
            if($pay==1){
                $payment_id = $this->db->query("select id as d from payments  where invoiceid='$randsellid' and ledgerid='$psupplier_id'")->row()->d;
                $this->db->query("Update payments set amount='$payment',date='$date',ledgerid='$supplier_id' where id='$payment_id'");
                $this->db->query("Update ledgerposting set debit='$payment',date='$date',ledgerid='$supplier_id' where voucherid='$payment_id' and ledgerid='$psupplier_id' and vouchertype='Payment voucher'");
                $this->db->query("Update ledgerposting set credit='$payment',date='$date' where voucherid='$payment_id' and ledgerid='1' and vouchertype='Payment voucher'");
            }

            else if($payment>0){
            $datalist = array(
                'invoiceid'=>$randsellid,
                'ledgerid' => $supplier_id,
                'date' => $date,
                'amount' => $payment,
                'description' => "Pur-". sprintf("%06d", $dailys->id),
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->insert('payments', $datalist);
            $lastid = $this->db->insert_id();

            $datalist = array(
                'voucherid' => $lastid,
                'ledgerid' => 1,
                'date' => $date,
                'debit' => 0,
                'credit' => $payment,
                'vouchertype' => 'Payment voucher',
                'description' => "Pur-". sprintf("%06d", $dailys->id),
                'user_id' => $this->session->userdata('user_id'),
            );
            
            $this->db->insert('ledgerposting', $datalist);

            $datalist2 = array(
                'voucherid' => $lastid,
                'ledgerid' => $supplier_id,
                'date' => $date,
                'debit' => $payment,
                'credit' => 0,
                'vouchertype' => 'Payment voucher',
                'description' => "Pur-". sprintf("%06d", $dailys->id),
                'user_id' => $this->session->userdata('user_id'),
            );
            $this->db->insert('ledgerposting', $datalist2);
            }
            
            savelog("Update Purchase", " voucher= $randsellid".",supplier=$psupplier_id".",pdate=$pdate"."payment=$payment Browser " . $_SERVER['HTTP_USER_AGENT']);
        $this->edit_purchase_view($randsellid);
        else:
        $this->session->set_userdata('failed','Only Admin Access This Function');
        $this->load->view('login',$data);
        endif;
    }

    //--------------------------Purchase Return-----------------------------//

    function purchase_return() {

        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
        $data['activemenu'] = 'transection';
        $data['activesubmenu'] = 'purchase_return';
        $data['page_title'] = 'Purchase Return';
        $data['baseurl'] = $this->config->item('base_url');
        $data['randomkey'] = time();
        $data['purchasedata'] = array();
        $data['suppliers'] = '';
        $data['date'] = date('Y-m-d H:i:s');
        $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
    
        $data['class_id']='';
        $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_purchasereturn group by randomkey order by randomkey desc")->result();
     
            $data['productlist']=$this->db->query("select id,product_name from products where  class_id='1' order by product_name asc")->result();
       
            $this->load->view('purchase_derectory/purchasereturn', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function purchasereturn_temp(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'purchase_return';
            $data['page_title'] = 'Purchase Return';
            $data['date'] = $this->input->post('date');
            $data['baseurl'] = $this->config->item('base_url');
        
            $data['randomkey']=$randomkey = $this->input->post('randomkey');
            $data['suppliers']=$this->input->post('suppliers');
            $bprice = ($this->input->post('buyprice'));
            $quantity = ($this->input->post('quantity'));
            $product_id = $this->input->post('product_id');
            $class_id = $data['class_id']=$class_id = $this->input->post('class_id');
            $comment = $this->input->post('comment');

            
            
            $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
        
            $data['productlist']=$this->db->query("select id,product_name from products where class_id =".$data['class_id']. " order by product_name asc")->result();


            $datar = array(
                'randomkey'=>$randomkey,
                'product_id'=>$product_id,
                'unit_price'=>$bprice,
                'unit_id'=>$this->input->post('unit_id'),
                'qty'=>$quantity,
                'comment' => $comment
            );
            $this->db->insert('temp_purchasereturn', $datar);

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp_purchasereturn as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where t.randomkey='$randomkey'")->result();

            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_purchasereturn group by randomkey order by randomkey desc")->result();

            $this->load->view('purchase_derectory/purchasereturn', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function purchase_return_submit() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):

        $product_idl=$this->input->post('product_idl');
        if($product_idl===null){
            //$this->session->set_userdata('success', 'Sales updated successfully');
           
            $this->session->set_userdata('failed', 'Please Insert Product');
             redirect('Purchase');
        }
        $pricel=$this->input->post('pricel');
        $qtyl=$this->input->post('qtyl');
        $supplier_id = $this->input->post('suppliers');
        $unitl = $this->input->post('unitl');
        $date = $this->input->post('date');
        $discount = $this->input->post('discount');
        $data['randomkey']=$randomkey = $this->input->post('randomkey');
        $t_price=$this->input->post('t_price');
        $comment = $this->input->post('comment');
        $received_amount = $this->input->post('received_amount');
        $user_id = $this->session->userdata('user_id');

        for ($i = 0; $i < count($product_idl); $i++) {
            $id = $product_idl[$i];
            $getpdetails = $this->db->query("select * from products where id = '$id'")->row();
            $total_quantity= $getpdetails->total_quantity - $qtyl[$i];
            $lastquantity = $getpdetails->available_quantity - $qtyl[$i];

            
            $tquantity= $qtyl[$i];
            $this->db->query("update products set available_quantity = '$lastquantity',total_quantity='$total_quantity' where id = '$id'");

            $purchase = $this->db->query("select id,a_quantity,buyprice from purchase where product_id='$id' and a_quantity>0 and supplier_id='$supplier_id' order by id asc")->result();

            $devcomment = array();
            foreach ($purchase as $pu){
                if($pu->a_quantity>=$tquantity){
                    $this->db->query("update purchase set a_quantity ='".($pu->a_quantity-$tquantity)."' where id='$pu->id'");
                    //$profit+=(($unit_price[$i]-$pu->buyprice) * $tquantity);
                    $devcomment[$pu->id] = $tquantity;
                    $lastquantity = $tquantity;
                    $tquantity =0;
                    break;
                }
                else {
                    $this->db->query("update purchase set a_quantity =0 where id='$pu->id'");
                    //$profit+=(($unit_price[$i]-$pu->buyprice) * $pu->a_quantity);
                    $devcomment[$pu->id] = $pu->a_quantity;
                    $tquantity -= $pu->a_quantity;
                }
            }

            if($getpdetails->warning_quantity<$getpdetails->available_quantity && $getpdetails->warning_quantity>=$getpdetails->available_quantity-$qtyl[$i])
                        $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
            

            $datalistsell = array(
                'invoice_id' => $randomkey,
                'product_id' => $product_idl[$i],
                'customer_id' => $supplier_id,
                'quantity' => $qtyl[$i],
                'return_price'=> $pricel[$i],
                'devcomment' => json_encode($devcomment),
                'unit_id' => $unitl[$i],
                'date' => $date,
                'comment' => $comment[$i]
                
            );   
            $this->db->insert('purchase_return', $datalistsell);   
            savelog('Purchase return or update', 'Product ID: ' . $product_idl[$i] . ' updated from IP:' . $_SERVER['REMOTE_ADDR'] . ' Browser: ' . $_SERVER['HTTP_USER_AGENT']);
        }

        $this->db->query("delete from temp_purchasereturn where randomkey = '$randomkey'");
        
        $datalist = array(
            'invoiceid' => $randomkey,
            'total_purchase' =>$t_price ,
            'date'=>$date,
            'user_id' => $user_id,
            'customer_id' => $supplier_id,
            'Description'=>$this->input->post('comments'),
            'received'=>$received_amount
        );   
        $this->db->insert('purchase_return_summary', $datalist);       
        $INSERT_ID = $this->db->insert_id();

        $datalist_payment1 = array(
            'voucherid' => $randomkey,
            'ledgerid' => 3,
            'date' =>  $this->input->post('date'),
            'vouchertype' => 'purchase return',
            'debit' => '0',
            'credit' => $t_price,
            'user_id' => $user_id,
            'description' => "Pr-". sprintf("%06d", $INSERT_ID)
        );
        $this->db->insert('ledgerposting', $datalist_payment1);


        $datalist_payment2 = array(
            'voucherid' => $randomkey,
            'ledgerid' => $supplier_id,
            'date' => $this->input->post('date'),
            'vouchertype' => 'purchase return',
            'debit' => $t_price,
            'credit' => '0',
            'description' => "Pr-". sprintf("%06d", $INSERT_ID),
            'user_id' => $user_id
        );
        $this->db->insert('ledgerposting', $datalist_payment2);

        if($discount>0){
            $datalist_payment1 = array(
                'voucherid' => $randomkey,
                'ledgerid' => 5,
                'date' =>  $this->input->post('date'),
                'vouchertype' => 'P R Discount',
                'debit' => $discount,
                'credit' => '0',
                'description' => "Pr-". sprintf("%06d", $INSERT_ID),
                'user_id' => $user_id
            );
            $this->db->insert('ledgerposting', $datalist_payment1);


            $datalist_payment2 = array(
                'voucherid' => $randomkey,
                'ledgerid' => $supplier_id,
                'date' => $this->input->post('date'),
                'vouchertype' => 'P R Discount',
                'debit' => '0',
                'credit' => $discount,
                'description' => "Pr-". sprintf("%06d", $INSERT_ID),
                'user_id' => $user_id
            );
            $this->db->insert('ledgerposting', $datalist_payment2);
        }

        if($received_amount>0){
            $datalist = array(
                    'invoiceid'=>$randomkey,
                    'ledgerid' => $supplier_id,
                    'date' => $this->input->post('date'),
                    'amount' => $received_amount,
                    'description' => "Pr-". sprintf("%06d", $INSERT_ID),
                    'user_id' => $user_id,

                );
                $this->db->insert('received', $datalist);
                $lastid = $this->db->insert_id();
                $this->db->where('id', $lastid);
               // $this->db->update('payments', array('invoiceid' => $lastid));

                $datalist = array(
                    'voucherid' => $lastid,
                    'ledgerid' => 1,
                    'date' => $date,
                    'debit' => $received_amount,
                    'credit' => 0,
                    'vouchertype' => 'Received voucher',
                    'description' => "Pr-". sprintf("%06d", $INSERT_ID),
                    'user_id' => $user_id
                );
                $this->db->insert('ledgerposting', $datalist);

                $datalist2 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => $supplier_id,
                    'date' => $date,
                    'debit' => 0,
                    'credit' => $received_amount,
                    'vouchertype' => 'Received voucher',
                    'description' => "Pr-". sprintf("%06d", $INSERT_ID),
                    'user_id' => $user_id
                );
                $this->db->insert('ledgerposting', $datalist2);
        }
        
        $this->session->set_userdata('success', 'Purchase return completed successfully.');
        //clean temp data
        redirect('purchase/showpurchasereturn/'.$randomkey);
        else:
        $this->load->view('login', $data);
        endif;   
    }

    function removedata_return() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $id = $_GET['id'];
            
            $class_id=$data['class_id'] = $_GET['class_id'];
            $data['date'] = $_GET['date'];
            $data['suppliers'] = $_GET['suppliers'];
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'purchase_return';
            $data['page_title'] = 'Purchase Return';
            $data['baseurl'] = $this->config->item('base_url');
            $data['randomkey']=$randomkey = $_GET['randomkey'];

            
            $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
            
             
            $data['productlist']=$this->db->query("select id,product_name from products where class_id =".$data['class_id']. "order by product_name asc")->result();
            $this->db->query("DELETE from temp_purchasereturn where id='$id'");

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp_purchasereturn as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where t.randomkey='$randomkey'")->result();
            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_purchasereturn group by randomkey order by randomkey desc")->result();
            
            $this->load->view('purchase_derectory/purchasereturn', $data);
        else:
            $this->load->view('login', $data);
        endif;

    }

    function tempremove_return(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
        $randomkey = $_GET['randomkey'];
        $this->db->query("DELETE FROM temp_purchasereturn WHERE randomkey='$randomkey'");
            redirect('purchase/purchase_return');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function showtemp_return(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $randomkey = $_GET['randomkey'];
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'purchase_return';
            $data['page_title'] = 'Purchase Return';            
            $data['date'] = date("Y-m-d");
            $data['baseurl'] = $this->config->item('base_url');
            $data['randomkey']=$randomkey;

            $data['class_id']='';
         
            $data['classes'] = $this->db->query("select id,class_name from classes order by id asc")->result();
            $data['productlist'] = array();
            $data['suppliers'] = '';

            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_purchasereturn group by randomkey order by randomkey desc")->result();

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp_purchasereturn as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where t.randomkey='$randomkey'")->result();
            $this->load->view('purchase_derectory/purchasereturn', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    //---------------------------Purchase Details--------------------------//

    function purchasedetails(){
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'purchasedetails';
        $data['page_title'] = 'Purchase Details';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $sdate = date("Y-m-d") . ' 00:00:00';
            $edate = date("Y-m-d") . ' 23:59:59';

            $data['sdate'] = date("Y-m-d");
            $data['edate'] = date("Y-m-d");

            $data['cname'] = '';
            $data['customerlist'] = $this->db->query("select l.ledgername,l.id,l.address,l.mobile,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid=5 and l.status<> 0")->result();

            $data['selldata'] = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,di.name as district_name,u.name as unit, ds.id as sellid from purchase as d left join products as p on d.product_id=p.id left join accountledger as l on d.supplier_id=l.id left join product_unit as u on p.unit_id=u.id left join purchase_summary as ds on d.invoiceid=ds.invoiceid left join districts as di on l.district=di.id   where d.date between '$sdate' AND '$edate'")->result();
            // $data['sellSummary']= $this->db->query("select sum(discount) as discount,sum(shipping_cost) as shipping_cost, sum(labour_cost) as labour_cost, sum(other_cost) as other_cost from purchase_summary WHERE date between '$sdate' AND '$edate'")->row();  
            $this->load->view('purchase_derectory/purchasedetails', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function viewPurchseDetails() {
      
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'purchasedetails';
        $data['page_title'] = 'Purchase Details';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):

        $sdate = $this->input->post('sdate') . ' 00:00:00';
        $edate = $this->input->post('edate') . ' 23:59:59';

        $data['sdate'] = $this->input->post('sdate');
        $data['edate'] = $this->input->post('edate');

        $data['user'] = $this->input->post('customer');
        $name = $this->input->post('customer');
        $data['cname'] = $name;

            $data['customerlist'] = $this->db->query("select l.ledgername,l.id,l.address,l.mobile,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid=5 and l.status<>0")->result();

            if ($data['user'] == 'all'):
                $data['selldata'] = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,di.name as district_name,u.name as unit,ds.id as sellid from purchase as d left join products as p on d.product_id=p.id left join accountledger as l on d.supplier_id=l.id left join product_unit as u on p.unit_id=u.id left join purchase_summary as ds on d.invoiceid=ds.invoiceid left join districts as di on l.district=di.id where d.date between '$sdate' AND '$edate'")->result();
                // $data['sellSummary']= $this->db->query("select sum(discount) as discount,sum(shipping_cost) as shipping_cost, sum(labour_cost) as labour_cost, sum(other_cost) as other_cost from purchase_summary WHERE date between '$sdate' AND '$edate'")->row(); 
            else:
                $data['selldata'] = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,di.name as district_name,u.name as unit,ds.id as sellid from purchase as d left join products as p on d.product_id=p.id left join accountledger as l on d.supplier_id=l.id left join product_unit as u on p.unit_id=u.id left join purchase_summary as ds on d.invoiceid=ds.invoiceid left join districts as di on l.district=di.id where d.date between '$sdate' AND '$edate' AND d.supplier_id='$name'")->result();
                $data['sellSummary']= $this->db->query("select sum(discount) as discount,sum(shipping_cost) as shipping_cost, sum(labour_cost) as labour_cost, sum(other_cost) as other_cost from purchase_summary WHERE date between '$sdate' AND '$edate' AND supplier_id='$name'")->row(); 
            endif;

        $this->load->view('purchase_derectory/purchasedetails', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    //---------------------------Purchase Report--------------------------//

    function purchasehistory() {
        $data['sdate'] = date("Y-m-d");
        $data['edate'] = date("Y-m-d");
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'purchasehistory';
        $data['page_title'] = 'Purchase history';
        $data['supplier_id'] = 'all';
        $data['baseurl'] = $this->config->item('base_url');
        $sdate = date("Y-m-d") . ' 00:00:00';
        $edate = date("Y-m-d") . ' 23:59:59';
  
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['purchasedata'] = $this->db->query("select p.*,u.fullname from purchase_summary as p left join alluser as u on p.user_id=u.id order by p.id desc limit 10")->result();
            $this->load->view('purchase_derectory/purchase_history', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function viewpurchasehistory() {
        
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'purchasehistory';
        $data['page_title'] = 'Purchase History';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):

                $sdate = $this->input->post('sdate') . ' 00:00:00';
                $edate = $this->input->post('edate') . ' 23:59:59';

                $data['sdate'] = $this->input->post('sdate');
                $data['edate'] = $this->input->post('edate');

                $data['user'] = $this->input->post('supplier_id');
                $supplier_id = $this->input->post('supplier_id');
                $data['supplier_id'] = $supplier_id;

            
            if ($supplier_id == 'all'):
                $data['purchasedata'] = $this->db->query("select p.*,u.fullname from purchase_summary as p left join alluser as u on p.user_id=u.id where p.date between '$sdate' AND '$edate' group by p.invoiceid")->result();
            else:
                $data['purchasedata'] = $this->db->query("select p.*,u.fullname from purchase_summary as p left join alluser as u on p.user_id=u.id  where p.date between '$sdate' AND '$edate' AND p.supplier_id='$supplier_id' group by p.invoiceid")->result();
                
            endif;
            $this->load->view('purchase_derectory/purchase_history', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }
    
    function detailspurchase($id = '') {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'purchasehistory';
            $data['page_title'] = 'Purchase history';
            $data['baseurl'] = $this->config->item('base_url');
            if (is_numeric($id)):
                $data['purchasedata'] = $this->db->query("select d.*,p.product_name,u.name as unit from purchase as d left join products as p on d.product_id=p.id left join product_unit as u on p.unit_id=u.id where d.invoiceid = '$id'")->result();
                $data['summary']=$this->db->query("select p.*,l.ledgername,l.father_name,l.mobile,l.address,di.name as district_name,u.fullname from purchase_summary as p left join accountledger as l on p.supplier_id=l.id left join districts as di on l.district=di.id left join alluser as u on p.user_id=u.id where p.invoiceid = '$id'")->row();

                $data['grossTotal'] = $data['summary']->total_purchase;
       
                $data['inword']= convert_number(round($data['grossTotal']));


                $this->load->view('purchase_derectory/details_purchase_history', $data);
                
            else:
                redirect('purchase/purchasehistory');
            endif;
        else:
            $this->load->view('login', $data);
        endif;
    }
    //sell
    function selldetails() {
        
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'selldetails';
        $data['page_title'] = 'Sales Details History';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $sdate = date("Y-m-d") . ' 00:00:00';
            $edate = date("Y-m-d") . ' 23:59:59';

            $data['sdate'] = date("Y-m-d");
            $data['edate'] = date("Y-m-d");

            $data['cname'] = 'all';
            $data['customerlist'] = $this->db->query("select l.ledgername,l.id,l.address,l.mobile,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid=16 and l.status<>0")->result();

            $data['selldata'] = $this->db->query("select d.*,p.product_name,l.ledgername,l.address,l.father_name,l.mobile,di.name as district_name,u.name as unit, ds.id as sellid from daily_sell as d left join products as p on d.product_id=p.id left join accountledger as l on d.customer_id=l.id left join product_unit as u on p.unit_id=u.id left join daily_sell_summary as ds on d.invoice_id=ds.voucherid left join districts as di on l.district=di.id   where d.date between '$sdate' AND '$edate'")->result();
            $data['sellSummary']= $this->db->query("select sum(discount) as discount, sum(labour_cost) as labour_cost from daily_sell_summary WHERE date between '$sdate' AND '$edate'")->row();  
            $this->load->view('purchase_derectory/selldetails', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function print_purchase($id = '') {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes'):
            $data['activemenu'] = 'reports';
            $data['activesubmenu'] = 'purchasehistory';
            $data['page_title'] = 'Purchase history';
            $data['baseurl'] = $this->config->item('base_url');

            $tem = $this->db->get_where("purcahse_sup_history",array('invoiceid'=>$id))->row();

            if (is_numeric($id)):
                $data['invoiceid'] = $id;
             
                $data['purchasedata'] = $this->db->query("select * from purchase where invoiceid = '$id'")->result();
                $data['supplierdata'] = $this->db->get_where('accountledger', array('id' => $data['purchasedata'][0]->supplier_id))->row();
                $data['controller']=$this;
                if($tem!=null):
                    $data['supplier_payable']=$tem->supplier_payable;
                    $data['paid_amount']=$tem->paid_amount;
                else:
                    $data['supplier_payable']=0.00;
                    $data['paid_amount']=0.00;
                endif;
                $this->load->view('print_purchase', $data);
            else:
                redirect('purchase/purchasehistory');
            endif;
        else:
            $this->load->view('login', $data);
        endif;
    }

    //-------------ajax---------------
    public function updatetemppurchase(){
        $tempid = $this->input->post('tempid');
        $comment = $this->input->post('comment');
        $sprice = $this->input->post('sprice');
        $bprice = $this->input->post('bprice');
        $quantity = $this->input->post('quantity');
        

        $this->db->query("update temp set unit_price='$bprice', qty='$quantity',sales_price='$sprice', comment='$comment' where id='$tempid'");

        echo "ok";
    }

    function getproduct_price() {
       
        $pid = $this->input->post('product_id');
        $getpdetails = $this->db->query("select p.*,u.name as unit from products as p left join product_unit as u on p.unit_id=u.id where p.id = '$pid' ")->row();
        echo json_encode($getpdetails);
    }
    function getpurchasedata() {
        $invoiceid = $this->input->post('invoiceid');
        $purchasedata = $this->db->get_where('purchase', array('invoiceid' => $invoiceid))->row();
        if (sizeof($purchasedata) > 0):
            echo json_encode($purchasedata);
        else:
            echo 'no';
        endif;
    }
}

?>