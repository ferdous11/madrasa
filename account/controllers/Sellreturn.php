<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sellreturn extends CI_Controller {
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
        $data['activesubmenu'] = 'sell_return';
        $data['page_title'] = 'Sales Return';
        $data['company_id'] = $this->session->userdata('company_id');
        $data['baseurl'] = $this->config->item('base_url');
        $data['randomkey'] = time();
        $data['purchasedata'] = array();
        $data['suppliers'] = '';
        $data['customer'] = '';
        $data['due']= 0;
        $data['date'] = date('Y-m-d H:i:s');
        $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$data['company_id']."' order by name asc")->result();
        $data['sub_category']='';
        $data['category_id']='';
        $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_sellreturn group by randomkey order by randomkey desc")->result();
        if($this->session->userdata('fcategory')!='true')
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id='1' order by product_name asc")->result();
        else
        $data['productlist']= array();
        $data['subCategory']= array();
        
            $this->load->view('sellreturn', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function save_temp() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'sell_return';
            $data['page_title'] = 'Sales Return';
            $data['date'] = $this->input->post('date');
            $data['baseurl'] = $this->config->item('base_url');
            $data['company_id']=$company_id=$this->session->userdata('company_id');
            $data['randomkey']=$randomkey = $this->input->post('randomkey');
            $customer_id=$this->input->post('customer_id');
            $bprice = ($this->input->post('buyprice'));
            $quantity = ($this->input->post('quantity'));
            $product_id = $this->input->post('product_id');
            $comment = $this->input->post('comment');
            $category_id = $data['category_id']=$category_id = $this->input->post('category_id');
            // check customer buy this product or not
            
            $full_package=-1;
            if($category_id==6 && $this->input->post('RadioOptions')!="")
                $full_package=$this->input->post('RadioOptions');

            
            $data['sub_category']=$sub_category = $this->input->post('sub_category');
            $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$data['company_id']."' order by name asc")->result();
            $data['subCategory'] = $this->db->query("select id,name from sub_category where company_id ='".$data['company_id']."' AND category_id='".$category_id."' order by name asc")->result();
            if($data['sub_category']==-1)
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id =".$data['category_id']. " order by product_name asc")->result();
            else    
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id =".$data['category_id']. " AND sub_category =".$data['sub_category']. " order by product_name asc")->result();

            $cp=$this->db->query("select * from daily_sell where product_id='$product_id' and customer_id='".$customer_id."' order by id desc limit 3")->result();
            
            if(!empty($cp)){
                $datar = array(
                    'randomkey'=>$randomkey,
                    'product_id'=>$product_id,
                    'unit_price'=>$bprice,
                    'full_package' => $full_package,
                    'unit_id'=>$this->input->post('unit_id'),
                    'qty'=>$quantity,
                    'comment'=>$comment,
                    'company_id'=>$company_id,
                    'customer_id'=>$this->input->post('customer_id')
                );
                $this->db->insert('temp_sellreturn', $datar);
            }

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp_sellreturn as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where t.company_id='$company_id' AND t.randomkey='$randomkey'")->result();
            if(!empty($data['purchasedata'])){
                $data['customer'] = $this->db->query("select l.*,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id='".$data['purchasedata'][0]->customer_id."'")->row();
                $data['customerid']=$data['purchasedata'][0]->customer_id;
                // customer due
                $ledgerid = $data['purchasedata'][0]->customer_id;
                $ledgerdata = $this->db->query("select * from accountledger where id = '$ledgerid'")->row();
                $lPosting = $this->db->query("select sum(debit) as aDebit,sum(credit) as aCredit from ledgerposting where ledgerid='$ledgerid'")->row();

                $debit = $ledgerdata->debit+$lPosting->aDebit;
                $credit = $ledgerdata->credit+$lPosting->aCredit;
                $data['due']= $debit-$credit;
                //-------------------
            }
            else{
                $data['due']= 0;
                $data['customer']='';
                $data['customerid']=0;
            }
            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_sellreturn group by randomkey order by randomkey desc")->result();

            if(empty($cp)){
                $this->session->set_userdata('failed', "The Customer didn't buy this product from here!!");
                $this->load->view('sellreturn', $data);
                return 0;
            }
            $this->load->view('sellreturn', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function removedata() {
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $_GET['id'];
            $sub_category=$data['sub_category'] = $_GET['sub_category'];
            $category_id=$data['category_id'] = $_GET['category_id'];
            $data['date'] = $_GET['date'];
            $data['suppliers'] = $_GET['suppliers'];
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'sell_return';
            $data['page_title'] = 'Sales Return';
            $data['baseurl'] = $this->config->item('base_url');
            $data['company_id']=$company_id=$this->session->userdata('company_id');
            $data['randomkey']=$randomkey = $_GET['randomkey'];

            
            $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$data['company_id']."' order by name asc")->result();
            $data['subCategory'] = $this->db->query("select id,name from sub_category where company_id ='".$data['company_id']."' AND category_id='".$category_id."' order by name asc")->result();
            if($data['sub_category']==-1)
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id =".$data['category_id']. " order by product_name asc")->result();
            else if($data['sub_category']>0)   
            $data['productlist']=$this->db->query("select id,product_name from products where company_id ='".$data['company_id']."' AND category_id =".$data['category_id']. " AND sub_category =".$data['sub_category']. " order by product_name asc")->result();
            else
            $data['productlist']=array();
            $this->db->query("DELETE from temp_sellreturn where id='$id'");
            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp_sellreturn as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where t.company_id='$company_id' AND t.randomkey='$randomkey'")->result();
            if(empty($data['purchasedata']))
            {
                return $this->index();
            }

            $data['customer'] = $this->db->query("select l.*,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id='".$data['purchasedata'][0]->customer_id."'")->row();
            // customer due
            $ledgerid = $data['purchasedata'][0]->customer_id;
            $ledgerdata = $this->db->query("select * from accountledger where id = '$ledgerid'")->row();
            $lPosting = $this->db->query("select sum(debit) as aDebit,sum(credit) as aCredit from ledgerposting where ledgerid='$ledgerid'")->row();

            $debit = $ledgerdata->debit+$lPosting->aDebit;
            $credit = $ledgerdata->credit+$lPosting->aCredit;
            $data['due']= $debit-$credit;
            //-------------------
            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_sellreturn group by randomkey order by randomkey desc")->result();
            
            $this->load->view('sellreturn', $data);
        else:
            $this->load->view('login', $data);
        endif;

    }

    function savepurchase(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

        $product_idl=$this->input->post('product_idl');
        if($product_idl===null){
            //$this->session->set_userdata('success', 'Sales updated successfully');
           
            $this->session->set_userdata('failed', 'Please Insert Product');
             redirect('sellreturn');
        }
        $pricel=$this->input->post('pricel');
        $full_package=$this->input->post('full_package');

        $qtyl=$this->input->post('qtyl');
        $supplier_id = $this->input->post('customer_id');
        $unitl = $this->input->post('unitl');
        $comment = $this->input->post('comment');
        $date = $this->input->post('date');
        $payment_amount = $this->input->post('payment_amount');
        $discount = $this->input->post('discount');
        $data['randomkey']=$randomkey = $this->input->post('randomkey');
        $company_id=$this->session->userdata('company_id');
        $t_price=$this->input->post('t_price');

        for ($i = 0; $i < count($product_idl); $i++) {
            $id = $product_idl[$i];$plastquantity=$lastbuyprice=0;
            $getpdetails = $this->db->query("select * from products where id = '$id'")->row();
            $lastquantity = $getpdetails->available_quantity + $qtyl[$i];
            if( $getpdetails->category_id==6){
                if($full_package[$i]==0){
                        $empty_cylinder = $getpdetails->empty_cylinder-$qtyl[$i];
                        $this->db->query("update products set available_quantity = '$lastquantity',empty_cylinder='$empty_cylinder' where id = '$id'");
                        $profit = ($pricel[$i] - $getpdetails->gas_price) * $qtyl[$i];
                        $lastbuyprice= $getpdetails->gas_price;
                }
                else if($full_package[$i]==2){
                        $empty_cylinder = $getpdetails->empty_cylinder+$qtyl[$i];
                        $this->db->query("update products set empty_cylinder='$empty_cylinder' where id = '$id'");
                        $profit = ($pricel[$i] - $getpdetails->cylinder_p_p) * $qtyl[$i];
                        $lastbuyprice= $getpdetails->cylinder_p_p;
                }
                else{
                    $this->db->query("update products set available_quantity = '$lastquantity' where id = '$id'");
                    $profit = ($pricel[$i] - $getpdetails->purchase_price) * $qtyl[$i];
                    $lastbuyprice= $getpdetails->purchase_price;
                }
                $plastquantity=$qtyl[$i];
                $devcomment = array();
            }
            else{
                $this->db->query("update products set available_quantity = '$lastquantity' where id = '$id'");
                $cp=$this->db->query("select * from daily_sell where product_id='$product_idl[$i]' and customer_id='$supplier_id' order by id desc limit 3")->result();
               
                $devcomment = array();
                $profit= 0;
                $tquantity= $qtyl[$i];
                if(!empty($cp)){
                    foreach ($cp as $pu){
                        $tarray = json_decode($pu->devcomment);
                        
                        if(!empty($tarray)){
                            foreach($tarray as $id => $row) {
                                $pu= $this->db->query("Select * from purchase where id='$id'")->row();
                                if($pu->a_quantity+$tquantity<=$pu->quantity){
                                    $this->db->query("update purchase set a_quantity=(a_quantity+'".$tquantity."') where id='$id'");
                                    $profit+=($pricel[$i]-$pu->buyprice)*$tquantity;
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
                                    $profit+=($pricel[$i]-$pu->buyprice)*$def;
                                    $devcomment[$pu->id] = $def;
                                }
                            } 
                        }
                        if($tquantity==0)
                            break;
                    }    
                }
                if($tquantity!=0){
                    $profit+=(($pricel[$i]-$getpdetails->opening_price) * $tquantity);
                    $lastquantity = $tquantity;
                    $lastbuyprice = $getpdetails->opening_price;
                }
            }
            if($getpdetails->warning_quantity>=$getpdetails->available_quantity && $getpdetails->warning_quantity<$getpdetails->available_quantity+$qtyl[$i])
            $this->session->set_userdata('notification',$this->session->userdata('notification')-1);

            $datalistsell = array(
                'invoice_id' => $randomkey,
                'product_id' => $product_idl[$i],
                'customer_id' => $supplier_id,
                'profit' => $profit,
                'full_package' => $full_package[$i],
                'quantity' => $qtyl[$i],
                'return_price'=> $pricel[$i],
                'unit' => $unitl[$i],
                'date' => $date,
                'comment' => $comment[$i],
                'lastbuyprice' => $lastbuyprice,
                'lastquantity' => $plastquantity,
                'company_id' => $company_id,
                'devcomment' => json_encode($devcomment)
            );   
            $this->db->insert('sell_return', $datalistsell);   

            savelog('Sales return or update', 'Product ID: ' . $product_idl[$i] . ' updated from IP:' . $_SERVER['REMOTE_ADDR'] . ' Browser: ' . $_SERVER['HTTP_USER_AGENT']);
        }

        $this->db->query("delete from temp_sellreturn where randomkey = '$randomkey'");
        
        $datalist = array(
            'invoiceid' => $randomkey,
            'total_purchase' =>$t_price ,
            'shipping_cost'=> 0,
            'other_cost'=> 0,
            'labour_cost'=> 0,
            'date'=>$date,
            'user_id' => $this->session->userdata('user_id'),
            'discount'=>$discount,
            'customer_id' => $supplier_id,
            'Description'=>$this->input->post('comments'),
            'company_id' => $company_id,
            'payment' => $payment_amount
        );   
        $this->db->insert('sell_return_summary', $datalist);       
        $INSERT_ID = $this->db->insert_id();

        $datalist_payment1 = array(
            'voucherid' => $randomkey,
            'ledgerid' => 2,
            'date' =>  $this->input->post('date'),
            'vouchertype' => 'sales return',
            'debit' => $t_price,
            'credit' => '0',
            'description' => "Sr-". sprintf("%06d", $INSERT_ID),
            'company_id' => $this->session->userdata('company_id')
        );
        $this->db->insert('ledgerposting', $datalist_payment1);


        $datalist_payment2 = array(
            'voucherid' => $randomkey,
            'ledgerid' => $supplier_id,
            'date' => $this->input->post('date'),
            'vouchertype' => 'sales return',
            'debit' => '0',
            'credit' => $t_price,
            'description' => "Sr-". sprintf("%06d", $INSERT_ID),
            'company_id' => $this->session->userdata('company_id')
        );
        $this->db->insert('ledgerposting', $datalist_payment2);

        if($discount>0){
            $datalist_payment1 = array(
                'voucherid' => $randomkey,
                'ledgerid' => 5,
                'date' =>  $this->input->post('date'),
                'vouchertype' => 'S R Discount',
                'debit' => '0',
                'credit' => $discount,
                'description' => "Sr-". sprintf("%06d", $INSERT_ID),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist_payment1);


            $datalist_payment2 = array(
                'voucherid' => $randomkey,
                'ledgerid' => $supplier_id,
                'date' => $this->input->post('date'),
                'vouchertype' => 'S R Discount',
                'debit' => $discount,
                'credit' => '0',
                'description' => "Sr-". sprintf("%06d", $INSERT_ID),
                'company_id' => $this->session->userdata('company_id')
            );
            $this->db->insert('ledgerposting', $datalist_payment2);
        }
        if($payment_amount>0){
             $datalist = array(
                    'invoiceid'=>$randomkey,
                    'ledgerid' => $supplier_id,
                    'date' => $date,
                    'amount' => $payment_amount,
                    'description' => "Sr-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id'),
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
                    'credit' => $payment_amount,
                    'vouchertype' => 'Payment voucher',
                    'description' => "Sr-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist);

                $datalist2 = array(
                    'voucherid' => $lastid,
                    'ledgerid' => $supplier_id,
                    'date' => $date,
                    'debit' => $payment_amount,
                    'credit' => 0,
                    'vouchertype' => 'Payment voucher',
                    'description' => "Sr-". sprintf("%06d", $INSERT_ID),
                    'company_id' => $this->session->userdata('company_id')
                );
                $this->db->insert('ledgerposting', $datalist2);
        }

        $this->session->set_userdata('success', 'Sales return completed successfully.');
        //clean temp data
        redirect('reports/showsellreturn/'.$randomkey);
        else:
        $this->load->view('login', $data);
        endif;    
    }

    function tempremove(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
        $randomkey = $_GET['randomkey'];
        $this->db->query("DELETE FROM temp_sellreturn WHERE randomkey='$randomkey'");
            redirect('sellreturn');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function showtemp(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $randomkey = $_GET['randomkey'];
            $data['activemenu'] = 'transection';
            $data['activesubmenu'] = 'sell_return';
            $data['page_title'] = 'Sales Return';            
            $data['date'] = date("Y-m-d");
            $data['baseurl'] = $this->config->item('base_url');
            $company_id = $data['company_id']=$company_id=$this->session->userdata('company_id');
            $data['randomkey']=$randomkey;

            $data['category_id']='';
            $data['sub_category']='';
            $data['allcategory'] = $this->db->query("select id,name from category where company_id ='".$data['company_id']."' order by name asc")->result();
            $data['subCategory'] = array();
            $data['productlist'] = array();
            $data['customerid'] = '';

            $data['uncomlitelist'] = $this->db->query("select randomkey,sum(qty*unit_price) as tprice, count(product_id) as titem from temp_sellreturn group by randomkey order by randomkey desc")->result();

            $data['purchasedata']= $this->db->query("select t.*,u.name as unit,p.product_name from temp_sellreturn as t left join product_unit as u on t.unit_id=u.id left join products as p on t.product_id= p.id  where t.company_id='$company_id' AND t.randomkey='$randomkey'")->result();

            $data['customer'] = $this->db->query("select l.*,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.id='".$data['purchasedata'][0]->customer_id."'")->row();

            // customer due
            $ledgerid = $data['purchasedata'][0]->customer_id;
            $ledgerdata = $this->db->query("select * from accountledger where id = '$ledgerid'")->row();
            $lPosting = $this->db->query("select sum(debit) as aDebit,sum(credit) as aCredit from ledgerposting where ledgerid='$ledgerid'")->row();

            $debit = $ledgerdata->debit+$lPosting->aDebit;
            $credit = $ledgerdata->credit+$lPosting->aCredit;
            $data['due']= $debit-$credit;
            //-------------------

            $this->load->view('sellreturn', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }
}
?>