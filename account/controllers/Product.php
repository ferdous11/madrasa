<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('form', 'file');
        $this->load->helper('url');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->load->helper('common');
        $this->load->helper('csv');
        $this->load->helper('download');
        $this->load->library('image_lib');
        $this->load->library('form_validation');
        $this->load->library('encryption');
    }

    public function index() {
        
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'products';
        $data['page_title'] = 'Products';
        $data['baseurl'] = $this->config->item('base_url');
        $companyid = $this->session->userdata('company_id');
        $data['class_id'] = 1;
    
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $data['classes'] = $this->db->query("select id,class_name from classes where company_id ='".$companyid."' order by id asc")->result();

            if($this->input->post('class_id'))
            {
                $data['section_id'] = $section_id = $this->input->post('section_id');
                $data['class_id'] = $class_id = $this->input->post('class_id');
                $data['product'] = $this->db->query("select p.*,u.name  as unit_name,au.fullname,c.class_name,sc.section_name  from products as p left join product_unit as u on p.unit_id=u.id left join classes as c on p.class_id=c.id left join sections as sc on p.section_id=sc.id left join alluser as au on p.user_id=au.id where p.company_id='$companyid' and p.class_id='$class_id' order by p.product_name asc")->result();
            }
            else{
                $data['product'] = $this->db->query("select p.*,u.name  as unit_name,au.fullname,c.class_name,sc.section_name  from products as p left join product_unit as u on p.unit_id=u.id left join classes as c on p.class_id=c.id left join sections as sc on p.section_id=sc.id left join alluser as au on p.user_id=au.id where p.company_id='$companyid' order by p.product_name asc")->result();
            }
            $this->load->view('product_derectory/product', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function export_product() {
        $companyid = $this->session->userdata('company_id');
        
        $category = $this->session->userdata('catagory_id');
        $sub_category = $this->session->userdata('subcategory_id');

        if($sub_category==-1&&$category!=-1){
        $product = $this->db->query("select p.*,u.name  as unit_name,au.fullname,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id left join alluser as au on p.user_id=au.id where p.company_id='$companyid' and c.id='$category' order by p.product_name asc")->result();
        }

        else if($sub_category==-1&&$category==-1){
        $product = $this->db->query("select p.*,u.name  as unit_name,au.fullname,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id left join alluser as au on p.user_id=au.id where p.company_id='$companyid' order by p.product_name asc")->result();
        }
        
        else if($sub_category!=-1&&$category!=-1){
        $product = $this->db->query("select p.*,u.name  as unit_name,au.fullname,c.name as category_name,sc.name as sub_category  from products as p left join product_unit as u on p.unit=u.id left join category as c on p.category_id=c.id left join sub_category as sc on p.sub_category=sc.id left join alluser as au on p.user_id=au.id where p.company_id='$companyid' and sc.id='$sub_category' and c.id='$category' order by p.product_name asc")->result();
        }
        else
            $product = array();

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Products.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
       
        $header = array("Name","Product Id","Category","Sub Category","Unit","Purchase price","Sales price","Opening Quantity","Available Quantity","Warning Quantity","Inserted By","Status"); 
        fputcsv($handle, $header);
        if(count($product)!=0):
            foreach ($product as $prodata){  

            $arr[0]= $prodata->product_name;
            $arr[1]= $prodata->product_id; 
            $arr[2]= $prodata->category_name;
            $arr[3]= $prodata->sub_category;
            $arr[4]= $prodata->unit_name;
            $arr[5]= number_format($prodata->purchase_price,2);
            $arr[6]= number_format(($prodata->retail_sale_price),2);
            $arr[7]= number_format(($prodata->opening_quantity),2);
            $arr[8]= ($prodata->category_id==6)? number_format(($prodata->available_quantity),2)."|".number_format(($prodata->empty_cylinder),2):number_format(($prodata->available_quantity),2);
            $arr[9]= ($prodata->warning_quantity);
            $arr[10]= ($prodata->fullname);
            if($prodata->status==0)
                $arr[11]= "Inactive";
            else 
                $arr[11]= "Active";
            fputcsv($handle, $arr);         
            }
        endif;
        fclose($handle);
        exit;
    }

    function addproduct_form() {
        $company_id = $this->session->userdata('company_id');
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'products';
        $data['page_title'] = 'Add new product';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $this->load->view('product_derectory/addnewproducts', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function addproduct() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'products';
        $data['page_title'] = 'Add new product';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $p_price = $this->input->post('purchase_price');
            $s_price = $this->input->post('sale_price');
            $quantity =$this->input->post('quantity');

            $data = array(
                'product_name' => $this->input->post('product_name'),
                'purchase_price' => $p_price,
                'sale_price' => $s_price,
                'total_quantity' => $quantity,
                'available_quantity' => $quantity,
                'date' => date("Y-m-d H:i:s"),
                'class_id' => $this->input->post('class_id'),
                'unit_id' => $this->input->post('unit_id'),
                'warning_quantity' => $this->input->post('warning_quantity'),
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'status' => '1'
            );
            savelog("New product added", "New product added; Product name:" . $this->input->post('productname'));
            $this->db->insert('products', $data);
            $product_id = $this->db->insert_id();
            $datalist = array(
                'invoiceid' => time(),
                'product_id' => $product_id,
                'supplier_id'=> 3,
                'buyprice'=> $p_price,
                'quantity'=>$quantity,
                'a_quantity'=>$quantity,
                'date' => date("Y-m-d H:i:s"),
                'company_id' => $this->session->userdata('company_id')
            );   
            $this->db->insert('purchase', $datalist);

            $this->session->set_userdata('success_message', 'Product added successfully');
            redirect("product/addproduct_form");
        else:
            $this->session->set_userdata('failed_message', 'Product not added ');
            $this->load->view('login', $data);
        endif;
    }

    function getpdetails() {
        $id = $this->input->post('product_id');
        $pdata = $this->db->query("select p.*,u.name as unit from products as p left join product_unit as u on p.unit_id=u.id where p.id = '$id'")->row();
        echo json_encode($pdata);
    }

    function getpdetailsbyuniqid() {
        $id = $this->input->post('product_id');
        $pdata = $this->db->query("select p.*,u.name as unit from products as p left join product_unit as u on p.unit=u.id where p.product_id = '$id'")->row();
        echo json_encode($pdata);
    }

    function editproduct($id = '') {
        
        if ($id == ''):
            redirect(base_url());
        else:
            $data['activemenu'] = 'master';
            $data['activesubmenu'] = 'products';
            $data['pdata'] = $this->db->query("select p.* from products as p where p.id = '$id'")->row();
            //dataview($data['pdata']);

            $data['company_id']=$this->session->userdata('company_id');
            $data['page_title'] = 'Products';
            $data['baseurl'] = $this->config->item('base_url');
            
            $data['getsubcategory'] = $this->db->get_where('sub_category', array('company_id' => $data['company_id'],'category_id'=>$data['pdata']->category_id))->result();

            $this->load->view('product_derectory/editproduct', $data);
        endif;
    }

    function saveupdateproduct() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'products';
        $data['page_title'] = 'Add new product';
        $data['baseurl'] = $this->config->item('base_url');

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $this->input->post('id');
            // $imagename = $this->input->post('preimage');

            // if ($_FILES["productpicture"]['name'] != ''):
            //     $config['upload_path'] = './assets/uploads/';
            //     $config['allowed_types'] = 'gif|jpg|png';
            //     $config['max_size'] = '1000';
            //     $config['max_width'] = '800';
            //     $config['max_height'] = '600';
            //     $new_name = $_FILES["productpicture"]['name'];
            //     $part = explode('.', $new_name);
            //     $newname = time() . 'g.' . $part[1];
            //     $config['file_name'] = trim($newname);
            //     $config['overwrite'] = TRUE;
            //     $this->load->library('upload', $config);
            //     if ($this->upload->do_upload('productpicture')):
            //         $imagename = $newname;
            //     else:
            //         $imagename = '';
            //     endif;
            // endif;

            $preopening_quantity = $this->input->post('preopening_quantity');
            $nopening_quantity = $this->input->post('opening_quantity');
            // if($preopening_quantity==0)
            //     $opening_quantity=($this->input->post('opening_quantity'));
            // else
            //     $opening_quantity=$this->input->post('preopening_quantity');

            if($nopening_quantity==$preopening_quantity)
            {
                $opening_quantity = $nopening_quantity;
                $total_quantity = $this->input->post('pretotal_quantity');
                $available_quantity = $this->input->post('preavailable_quantity');
            }
            else if($nopening_quantity>$preopening_quantity){
                $opening_quantity = $nopening_quantity;
                $total_quantity = $this->input->post('pretotal_quantity') + ($nopening_quantity - $preopening_quantity);
                $available_quantity = $this->input->post('preavailable_quantity') + ($nopening_quantity - $preopening_quantity); 
            }
            else
            {
                $opening_quantity = $nopening_quantity;
                $total_quantity = $this->input->post('pretotal_quantity') - ($preopening_quantity - $nopening_quantity);
                $available_quantity = $this->input->post('preavailable_quantity') - ($preopening_quantity - $nopening_quantity);
            }


            
            $purchase_price = $this->input->post('purchase_price');
            $sale_price = $this->input->post('sale_price');
            

            $data = array(
                'category_id' => $this->input->post('category_id'),
                'sub_category' => $this->input->post('sub_category'),
                'product_id' => $this->input->post('product_id'),
                'product_name' => $this->input->post('product_name'),
                'unit' => $this->input->post('purchase_unit'),
                'purchase_price' => $purchase_price,
                'sale_price' => $sale_price,
                //'image' => $imagename,
                'date' => date("Y-m-d H:i:s"),
                'decimale_multiplier' => $this->input->post('decimale_multiplier'),
                'warning_quantity' => $this->input->post('warning_quantity'),
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'status' => '1',
                'total_quantity' => $total_quantity,
                'available_quantity' => $available_quantity,
                'opening_quantity' => $opening_quantity,
            );
            
            $this->db->where('id', $id);
            $this->db->update('products', $data);

            $this->session->set_userdata('success_message', 'Product updated successfully');

            $supp = $this->input->post('supplier');
            $cat = $this->input->post('category');

            redirect("product/$supp/$cat");
        else:
            $this->load->view('login', $data);
        endif;
    }

    function deleteproduct($did = '',$supp='',$cat='') {
        if ($did == ''):
            redirect(base_url());
        else:
            $dquery = $this->db->query("delete from products where id = '$did'");
            $this->session->set_userdata('success_message', 'Product deleted successfully');
            redirect("product/$supp/$cat");
        endif;
    }

    function summary(){
        $company_id = $this->session->userdata('company_id');
        $data['activemenu'] = 'reports';
        $data['activesubmenu'] = 'productsummary';
        $data['page_title'] = 'Product Summary';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $sdate = date("Y-m-d") . ' 00:00:00';
            $edate = date("Y-m-d") . ' 23:59:59';
            $data['sdate'] = date("Y-m-d");
            $data['edate'] = date("Y-m-d");

            if($this->input->post("sdate")){
                $data['sdate'] = $this->input->post("sdate");
                $data['edate'] = $this->input->post("edate");
                $sdate = $data['sdate'] . ' 00:00:00';
                $edate = $data['edate'] . ' 23:59:59';

            }
            
            $data['purchaseQ']=$data['purchaseP']=$data['saleQ']=$data['saleP']=$data['purchaseReturnQ']=$data['purchaseReturnP']=$data['saleReturnQ']=$data['saleReturnP']=array();
            $data['purchaseQE']=$data['purchasePE']=$data['saleQE']=$data['salePE']=$data['purchaseReturnQE']=$data['purchaseReturnPE']=$data['saleReturnQE']=$data['saleReturnPE']=array();
            $data['purchaseQR']=$data['purchasePR']=$data['saleQR']=$data['salePR']=$data['purchaseReturnQR']=$data['purchaseReturnPR']=$data['saleReturnQR']=$data['saleReturnPR']=array();


            //--------product----------
            $data['product'] = $this->db->query("select p.*,u.name from products as p left join product_unit as u on p.unit=u.id where p.status=1 order by p.category_id,p.product_name")->result();

            //---------Purchase--------
            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(buyprice*quantity) as buyprice from purchase where date between '$sdate' and '$edate' and  (full_package =-1 or full_package =1)group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['purchaseQ'][$key->product_id]=$key->quantity;
                $data['purchaseP'][$key->product_id]=$key->buyprice;
            }
            //----------Sales------------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(sellprice*quantity) as buyprice from daily_sell where date between '$sdate' and '$edate' and  (full_package =-1 or full_package =1)group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['saleQ'][$key->product_id]=$key->quantity;
                $data['saleP'][$key->product_id]=$key->buyprice;
            }
            //-----------Purchase Return-----
            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(return_price*quantity) as buyprice from purchase_return where date between '$sdate' and '$edate' and  (full_package =-1 or full_package =1)group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['purchaseReturnQ'][$key->product_id]=$key->quantity;
                $data['purchaseReturnP'][$key->product_id]=$key->buyprice;
            }

            //-----------Sales Return---------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(return_price*quantity) as buyprice from sell_return where date between '$sdate' and '$edate' and  (full_package =-1 or full_package =1)group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['saleReturnQ'][$key->product_id]=$key->quantity;
                $data['saleReturnP'][$key->product_id]=$key->buyprice;
            }
            //------------------ Empty Cylinder--------------

            //---------Purchase--------
            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(buyprice*quantity) as buyprice from purchase where date between '$sdate' and '$edate' and  full_package =2 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['purchaseQE'][$key->product_id]=$key->quantity;
                $data['purchasePE'][$key->product_id]=$key->buyprice;
            }
            //----------Sales------------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(sellprice*quantity) as buyprice from daily_sell where date between '$sdate' and '$edate' and  full_package =2 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['saleQE'][$key->product_id]=$key->quantity;
                $data['salePE'][$key->product_id]=$key->buyprice;
            }
            //-----------Purchase Return-----
            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(return_price*quantity) as buyprice from purchase_return where date between '$sdate' and '$edate' and  full_package =2 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['purchaseReturnQE'][$key->product_id]=$key->quantity;
                $data['purchaseReturnPE'][$key->product_id]=$key->buyprice;
            }

            //-----------Sales Return---------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(return_price*quantity) as buyprice from sell_return where date between '$sdate' and '$edate' and  full_package =2 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['saleReturnQE'][$key->product_id]=$key->quantity;
                $data['saleReturnPE'][$key->product_id]=$key->buyprice;
            }
            //------------------ Refil Cylinder-----------

            //---------Purchase--------
            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(buyprice*quantity) as buyprice from purchase where date between '$sdate' and '$edate' and  full_package =0 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['purchaseQR'][$key->product_id]=$key->quantity;
                $data['purchasePR'][$key->product_id]=$key->buyprice;
            }
            //----------Sales------------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(sellprice*quantity) as buyprice from daily_sell where date between '$sdate' and '$edate' and  full_package =0 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['saleQR'][$key->product_id]=$key->quantity;
                $data['salePR'][$key->product_id]=$key->buyprice;
            }
            //-----------Purchase Return-----
            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(return_price*quantity) as buyprice from purchase_return where date between '$sdate' and '$edate' and  full_package =0 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['purchaseReturnQR'][$key->product_id]=$key->quantity;
                $data['purchaseReturnPR'][$key->product_id]=$key->buyprice;
            }

            //-----------Sales Return---------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,sum(return_price*quantity) as buyprice from sell_return where date between '$sdate' and '$edate' and  full_package =0 group by product_id order by product_id asc")->result();

            foreach ($temp as $key) {
                $data['saleReturnQR'][$key->product_id]=$key->quantity;
                $data['saleReturnPR'][$key->product_id]=$key->buyprice;
            }


            $data['selldata'] = array();
            

            $this->load->view('product_derectory/product_summary', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    public function changeStatus(){
        $product_id =  $this->input->post('product_id');
        $this->db->query("update products set status= NOT status where id='$product_id'");
        $product = $this->db->query("select status,warning_quantity,available_quantity from products where id='$product_id'")->row();
        if($product->status==0 && $product->warning_quantity>=$product->available_quantity)
            $this->session->set_userdata('notification',$this->session->userdata('notification')-1);
        else if($product->status==1 && $product->warning_quantity>=$product->available_quantity)
             $this->session->set_userdata('notification',$this->session->userdata('notification')+1);
    }

    //---------------------------------------------  category
    function category() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'category';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');
        
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['categorydata'] = $this->db->query("select c.*,u.fullname from category as c left join alluser as u on c.user_id=u.id where c.company_id='".$this->session->userdata('company_id')."'")->result();
            $this->load->view('product_derectory/category', $data);

        else:
            $this->load->view('login', $data);
        endif;
    }

    function addcategory() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('category', $data);
            $this->session->set_userdata('success', 'Category added successfully');
            savelog('New category added', 'Category ' . $this->input->post('name') . ' added successfully');
            redirect('product/category');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function updatecategory() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $this->input->post('cat_id');
            $data = array(
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'user_id' => $this->session->userdata('user_id')
            );
            $this->db->where('id', $id);
            $this->db->update('category', $data);
            $this->session->set_userdata('success', 'Category updated successfully');
            redirect('product/category');
        else:
            $this->load->view('login', $data);
        endif;
    }
    //--------------------------------------------  sub category
    function subcategory() {
        $data['activemenu'] = 'master';
        $data['activesubmenu'] = 'subcategory';
        $data['page_title'] = 'Master Setting';
        $data['baseurl'] = $this->config->item('base_url');

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['categorydata'] = $this->db->query("select c.*, l.name as category_name,u.fullname from sub_category as c left join category as l on c.category_id=l.id left join alluser as u on c.user_id=u.id where c.company_id='".$this->session->userdata('company_id')."' order by id asc")->result();
            $this->load->view('product_derectory/subcategory', $data);

        else:
            $this->load->view('login', $data);
        endif;
    }

    function addsubcategory() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data = array(
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category_id'),
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('sub_category', $data);
            $this->session->set_userdata('success', 'Sub Category added successfully');
            savelog('New sub category added', 'Sub Category ' . $this->input->post('name') . ' added successfully');
            redirect('product/subcategory');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function updatesubcategory() {
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $id = $this->input->post('cat_id');
            $data = array(
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category_id'),
                'user_id' => $this->session->userdata('user_id')

            );
            $this->db->where('id', $id);
            $this->db->update('sub_category', $data);
            $this->session->set_userdata('success', 'Sub Category updated successfully');
            redirect('product/subcategory');
        else:
            $this->load->view('login', $data);
        endif;
    }
    //--------------------------------------------  ajax
    function getSubCategory(){

        $classId = $this->input->post('classId');
        $data = $this->db->query("select * from sections where class_id = ".$classId." and company_id='".$this->session->userdata('company_id')."'")->result();
        echo json_encode($data);
    }

    function productUplodCsv(){

        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != '' && $_FILES['upload_data_file']  )
        {

            $file_name = $_FILES['upload_data_file']['name'];
            $chk_ext = explode(".", $file_name);

            if( (strtolower(end($chk_ext)) !== "csv") ) {
                $this->session->set_userdata('failed', 'Your file type must be in csv format');
                //$this->load->view('admin/upload_product_csv', $data);
                redirect('product/addproduct_form');
                return false;
            }
            
            $fname = $_FILES['upload_data_file']['tmp_name'];
            
            $handle = fopen($fname, "r");
            
            $my_data = array();
            while ( ($up_data = fgetcsv($handle,1000,",","\0")) !== FALSE ) {
                $up_data = array_map("utf8_encode", $up_data );
                $my_data[]=$up_data;
            }
            fclose($handle);
 
            $j=1;
            for($i=1;$i<count($my_data);$i++){
                if($my_data[$i][0]==null)
                    continue;
                $newProduct = array(
                'product_id' => $my_data[$i][0],
                'product_name' => $my_data[$i][1],
                'purchase_price' => $my_data[$i][5],
                'opening_price' => $my_data[$i][5],
               // 'gross_sale_price' => ($this->input->post('gross_sale_price')),
                'sale_price' => $my_data[$i][6],
                'image' => 'by csv',
                'total_quantity' => $my_data[$i][7],
                'available_quantity' => $my_data[$i][7],
                'date' => date("Y-m-d H:i:s"),
                'category_id' => $my_data[$i][2],
                'sub_category' => $my_data[$i][3],
                'unit' => $my_data[$i][4],
                'opening_quantity' => $my_data[$i][7],
                'warning_quantity' => $my_data[$i][8],
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'status' => '1',
                'decimale_multiplier' =>10 

                );
                if($this->db->insert('products', $newProduct))
                    $j++;
            }
            if($i==$j)
                $this->session->set_userdata('success', 'Upload csv file successfully');
            else
                $this->session->set_userdata('failed', 'Failed to upload '.$file_name);
            redirect('product');
        }
        else
            $this->load->view('login');
    }

    function ifproductidexist(){
        $product_id = $this->input->post('product_id');
        $temp = $this->db->query("select id from products where product_id='$product_id'")->result();
        $data = count($temp);
        echo json_encode($data);
    }
    function ifproductidexistedit(){
        $product_id = $this->input->post('product_id');
        $pid = $this->input->post('pid');
        $temp = $this->db->query("select id from products where product_id='$product_id' and id <>'$pid'")->result();
        $data = count($temp);
        echo json_encode($data);
    }
}

?>