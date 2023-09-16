<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profitloss extends CI_Controller {

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


    function index() {
        $data['baseurl'] = $this->config->item('base_url');
        
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('role') == 'admin' && $this->session->userdata('company_id') != ''):
        $data['title'] = "Profit And Loss Analysis";
        $data['activemenu'] = "accountstatement";
        $data['activesubmenu'] = "profitloss";
       
        $data['sdate'] = date('Y-m-01');
        $data['edate'] = date('Y-m-d');
        
       
        if($this->input->post('sdate')){
            $data['sdate'] = $this->input->post('sdate');
            $data['edate'] = $this->input->post('edate');
        }

        $sdate = $data['sdate'].' 00:00:00';
        $edate = $data['edate'].' 23:59:59';

        $companyid = $this->session->userdata('company_id');

        $data['categorys'] = $this->db->query("select * from category where company_id='$companyid'")->result();
        $profitarray= array();
        $salereturnarray = array();

        $profit = $this->db->query("select p.category_id,sum(profit) as profit from daily_sell as d left join products as p on d.product_id=p.id  where d.date between '$sdate' and '$edate' group by p.category_id order by p.category_id")->result();

        foreach ($profit as $key){
            $profitarray[$key->category_id]=$key->profit;
        }

        $profit = $this->db->query("select p.category_id,sum(profit) as profit from sell_return as d left join products as p on d.product_id=p.id  where d.date between '$sdate' and '$edate' group by p.category_id order by p.category_id")->result();

        foreach ($profit as $key){
            $salereturnarray[$key->category_id]=$key->profit;
        }

        $data['sales']=  $profitarray;
        $data['salesReturn']=  $salereturnarray;

        
            // other Expense Income
        
            $legderopeningquery= $this->db->query("select id,(debit-credit) as debit from accountledger")->result();
            $data['legderopeninarray']=array();
            foreach ($legderopeningquery as $key) {
                $data['legderopeninarray'][$key->id]=$key->debit;
            }

            // Expense
            // discount ----------
            $data['discountquery'] = $this->db->query("select sum(debit) as debit, sum(credit) as credit from ledgerposting where ledgerid=5 and date between '$sdate' and '$edate'")->row();
            //labourcost
            $data['labourcostquery'] = $this->db->query("select sum(debit-credit) as debit from ledgerposting where ledgerid=4 and vouchertype<>'Labour Cost' and date between '$sdate' and '$edate'")->row();
            //transportcost
            $data['transportcostquery'] = $this->db->query("select sum(debit-credit) as debit from ledgerposting where ledgerid=8 and vouchertype<>'Transport Cost' and date between '$sdate' and '$edate'")->row();
            //shippingcost
            $data['shippingcostquery'] = $this->db->query("select sum(debit-credit) as debit from ledgerposting where ledgerid=10 and date between '$sdate' and '$edate'")->row();
            //othercost
            $data['othercostquery'] = $this->db->query("select sum(debit-credit) as debit from ledgerposting where ledgerid=11 and date between '$sdate' and '$edate'")->row();
            //employee expense
            $data['employeequery'] = $this->db->query("select sum(lp.debit) as debit from ledgerposting as lp right join accountledger as l on lp.ledgerid=l.id  where l.accountgroupid=18 and lp.date between '$sdate' and '$edate'")->row();


        
            //other expense
            $data['expenseledgerquery'] = $this->db->query("select l.ledgername,l.id,sum(lp.debit-lp.credit) as debit from ledgerposting as lp right join accountledger as l on lp.ledgerid=l.id  where l.accountgroupid=4 and lp.ledgerid not in(4,5,8,10,11) and lp.date between '$sdate' and '$edate'  group by lp.ledgerid order by lp.ledgerid ")->result();

            //other incom
            $data['otherincomquery'] = $this->db->query("select l.ledgername,l.id, sum(lp.credit-lp.debit) as credit from ledgerposting as lp right join accountledger as l on lp.ledgerid=l.id  where l.accountgroupid=3 and lp.date between '$sdate' and '$edate' group by lp.ledgerid")->result();
        
        
        $this->load->view('profitloss', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }


        //localhost/newJibonVaraityStore/index.php/profitloss/profitlossbyc/2021-01/1

    function profitlossbyc($datepickermonth=null,$categoryid) {
        $data['baseurl'] = $this->config->item('base_url');
        
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('role') == 'admin' && $this->session->userdata('company_id') != ''):
        $data['title'] = "Profit And Loss Analysis";
        $data['activemenu'] = "accountstatement";
        $data['activesubmenu'] = "profitloss";
        $companyid = $this->session->userdata('company_id');
       
        $data['edate'] = date('Y-m');
        $data['products'] = $this->db->query("select * from products where category_id=$categoryid and  company_id='$companyid'")->result();
       
        if($datepickermonth){
            $data['edate'] = $datepickermonth;
            
        }

        $sdate = $data['edate'].'-01 00:00:00';
        $edate = $data['edate'].'-05 23:59:59';
        //opening
    {   
        //--------------opening view create 
        $this->db->query("CREATE OR REPLACE VIEW purchase_view  as select p.product_id,sum(p.quantity) as quantity from purchase as p left join products as pr on p.product_id=pr.id where p.date < '$sdate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid group by p.product_id order by p.product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW purchase_price_view  as select product_id,buyprice from purchase WHERE id IN (SELECT MAX(id) AS id FROM purchase where  date < '$sdate' and  (full_package =-1 or full_package =1)group by product_id) order by product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW sales_view  as select p.product_id,sum(p.quantity) as quantity from daily_sell as p left join products as pr on p.product_id=pr.id where p.date < '$sdate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid group by p.product_id order by p.product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW purchase_return_view  as select p.product_id,sum(p.quantity) as quantity from purchase_return as p left join products as pr on p.product_id=pr.id where p.date < '$sdate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid  group by p.product_id order by p.product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW sales_return_view  as select p.product_id,sum(p.quantity) as quantity from sell_return as p left join products as pr on p.product_id=pr.id where p.date < '$sdate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid  group by p.product_id order by p.product_id asc");


        $op = $this->db->query("SELECT p.id,sum((p.opening_quantity+ifnull(pu.quantity,0) + ifnull(sr.quantity,0) - ifnull(s.quantity,0) - ifnull(pur.quantity,0))*ifnull(pup.buyprice,p.opening_price)) as opening from products as p left join sales_view as s on p.id=s.product_id left join  purchase_view as pu on p.id=pu.product_id left join sales_return_view as sr on p.id=sr.product_id left join purchase_return_view as pur on p.id=pur.product_id left join purchase_price_view as pup on p.id=pup.product_id where p.category_id=$categoryid group by p.id")->result();

        // opening stock---------------
     

        $data['openingarray']= array() ;
        $data['openingstock']= 0;



        //$openingquery= $this->db->query("select sum((opening_quantity*opening_price)+(opening_e_cylinder*opening_e_c_price)) as openingstock, category_id from products group by category_id order by category_id asc")->result();

        foreach ($op as $key) {
            if($categoryid!=6){
                $data['openingarray'][$key->id]=$key->opening;
                $data['openingstock'] +=$key->opening;
            }
        }


        // Refil Gas Cylinder Calculation 
        // all gas product 
        if($categoryid==6):
            $arrbuypricefullp = $arrbuypriceempty = array();
            $tempopening = $this->db->query("select id,opening_quantity,opening_price,opening_e_cylinder,opening_e_c_price from products where category_id=6 ")->result();
            foreach ($tempopening as $key) {
                $arropening_quantity[$key->id]=$key->opening_quantity;
                $arropening_price[$key->id]=$key->opening_price;
                $arropening_e_cylinder[$key->id]=$key->opening_e_cylinder;
                $arropening_e_c_price[$key->id]=$key->opening_e_c_price;
            }

            //------------------full package ----------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase where date < '$sdate' and full_package=1 group by product_id")->result();

            //last price each product
            $temp_price = $this->db->query("select product_id,buyprice from purchase WHERE id IN (SELECT MAX(id) AS id FROM purchase where date < '$sdate' and full_package=1 group by product_id)")->result();

            foreach ($temp_price as $key) { 
                $arrbuypricefullp[$key->product_id]=$key->buyprice;
            }

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
            }
        
            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase_return where date < '$sdate' and full_package=1 group by product_id")->result();

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from daily_sell where date < '$sdate' and full_package=1 group by product_id")->result();

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from sell_return where date < '$sdate' and full_package=1 group by product_id")->result();

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
            }

            //---------------refil---------------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,avg(buyprice) as buyprice from purchase where date < '$sdate' and full_package=0 group by product_id")->result();

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }
        
            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase_return where date < '$sdate' and full_package=0 group by product_id")->result();

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from daily_sell where date < '$sdate' and full_package=0 group by product_id")->result();

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from sell_return where date < '$sdate' and full_package=0 group by product_id")->result();

            foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }

            //-------------empty---------------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase where date < '$sdate' and full_package=2 group by product_id")->result();

            //last price each product
            $temp_price = $this->db->query("select product_id,buyprice from purchase WHERE id IN (SELECT MAX(id) AS id FROM purchase where date < '$sdate' and full_package=2 group by product_id)")->result();

            if(sizeof($temp_price)>0) foreach ($temp_price as $key) {
                $arrbuypriceempty[$key->product_id]=$key->buyprice;
            }

            if(sizeof($temp)>0) foreach ($temp as $key) {
               
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }
        
            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase_return where date < '$sdate' and full_package=2 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from daily_sell where date < '$sdate' and full_package=2 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from sell_return where date < '$sdate' and full_package=2 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }

            
            foreach ($tempopening as $key){
                $data['openingarray'][$key->id]=0;
                if(array_key_exists($key->id,$arrbuypricefullp))
                $data['openingarray'][$key->id]+=($arrbuypricefullp[$key->id] * $arropening_quantity[$key->id]);
                else
                $data['openingarray'][$key->id]+=($arropening_price[$key->id] * $arropening_quantity[$key->id]); 

                if(array_key_exists($key->id,$arrbuypriceempty))
                $data['openingarray'][$key->id]+=($arrbuypriceempty[$key->id] * $arropening_e_cylinder[$key->id]);
                else
                $data['openingarray'][$key->id]+=($arropening_e_c_price[$key->id] * $arropening_e_cylinder[$key->id]); 

                $data['openingstock'] +=$data['openingarray'][$key->id];
            }
 
        endif;

        

        //--------------opening Quantity end
    }
        //purchase sales
    {
        // Purchase stock---------------
        $data['purchasearray']= array() ;
        $data['purchase']= 0;

        $purchasequery= $this->db->query("select sum(pu.buyprice*pu.quantity) as openingstock, p.id from purchase as pu right join products as p on pu.product_id=p.id where pu.date between '$sdate' And  '$edate' and p.category_id=$categoryid group by p.id order by p.id asc")->result();

        foreach ($purchasequery as $key) {
            $data['purchasearray'][$key->id]=$key->openingstock;
            $data['purchase'] +=$key->openingstock;
        }

        

        // sales ------------------------
        $data['salesarray']= array() ;
        $data['sales']= 0;

        $salesquery= $this->db->query("select sum(d.sellprice*d.quantity) as openingstock, p.id from daily_sell as d right join products as p on d.product_id=p.id where d.date between '$sdate' And '$edate' and p.category_id=$categoryid group by p.id order by p.id asc")->result();

        foreach ($salesquery as $key) {
            $data['salesarray'][$key->id]=$key->openingstock;
            $data['sales'] +=$key->openingstock;
        }
        
        

        // sales Return----------------
        $data['salesreturnarray']= array() ;
        $data['salesreturn']= 0;

        $salesreturnquery= $this->db->query("select sum(d.return_price*d.quantity) as openingstock, p.id from sell_return as d right join products as p on d.product_id=p.id where d.date between '$sdate' And '$edate' and p.category_id=$categoryid group by p.id order by p.id asc")->result();

        foreach ($salesreturnquery as $key) {
            $data['salesreturnarray'][$key->id]=$key->openingstock;
            $data['salesreturn'] +=$key->openingstock;
        }

        // Purchase Return----------------
        $data['purchasereturnarray']= array() ;
        $data['purchasereturn']= 0;

        $purchasereturnquery= $this->db->query("select sum(pu.return_price*pu.quantity) as openingstock, p.id from purchase_return as pu right join products as p on pu.product_id=p.id where pu.date between '$sdate' And '$edate' and p.category_id=$categoryid group by p.id order by p.id asc")->result();

        foreach ($purchasereturnquery as $key) {
            $data['purchasereturnarray'][$key->id]=$key->openingstock;
            $data['purchasereturn'] +=$key->openingstock;
        }
    }

        //closing
    {

        //--------------clossing view create 
        $this->db->query("CREATE OR REPLACE VIEW purchase_view  as select p.product_id,sum(p.quantity) as quantity from purchase as p left join products as pr on p.product_id=pr.id where p.date < '$edate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid group by p.product_id order by p.product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW purchase_price_view  as select product_id,buyprice from purchase WHERE id IN (SELECT MAX(id) AS id FROM purchase where  date < '$edate' and  (full_package =-1 or full_package =1)group by product_id) order by product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW sales_view  as select p.product_id,sum(p.quantity) as quantity from daily_sell  as p left join products as pr on p.product_id=pr.id where p.date < '$edate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid  group by p.product_id order by p.product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW purchase_return_view  as select p.product_id,sum(p.quantity) as quantity from purchase_return  as p left join products as pr on p.product_id=pr.id where p.date < '$edate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid group by p.product_id order by p.product_id asc");

        $this->db->query("CREATE OR REPLACE VIEW sales_return_view  as select p.product_id,sum(p.quantity) as quantity from sell_return  as p left join products as pr on p.product_id=pr.id where p.date < '$edate' and  (p.full_package =-1 or p.full_package =1) and pr.category_id=$categoryid group by p.product_id order by p.product_id asc");


        $op = $this->db->query("SELECT p.id,sum((p.opening_quantity+ifnull(pu.quantity,0) + ifnull(sr.quantity,0) - ifnull(s.quantity,0) - ifnull(pur.quantity,0))*ifnull(pup.buyprice,p.opening_price)) as opening from products as p left join sales_view as s on p.id=s.product_id left join  purchase_view as pu on p.id=pu.product_id left join sales_return_view as sr on p.id=sr.product_id left join purchase_return_view as pur on p.id=pur.product_id left join purchase_price_view as pup on p.id=pup.product_id where p.category_id=$categoryid group by p.id")->result();
        

        $data['clossingarray']= array() ;
        $data['clossingstock']= 0;

        //$clossingquery= $this->db->query("select sum((available_quantity*purchase_price)+(empty_cylinder*cylinder_p_p)) as openingstock, category_id from products group by category_id order by category_id asc")->result();
        
        foreach ($op as $key) {
            if($categoryid!=6){
            $data['clossingstock'] +=$key->opening;
            $data['clossingarray'][$key->id]=$key->opening;
            }
        }

        // echo "<pre>";
        // print_r($data['clossingarray']);
        // echo $data['clossingstock'];
        // echo "</pre>";
        // die();

       // for gas closing --------------------
        if($categoryid==6):
            $arrbuypricefullp = $arrbuypriceempty = array();
            foreach ($tempopening as $key) {
                $arropening_quantity[$key->id]=$key->opening_quantity;
                $arropening_price[$key->id]=$key->opening_price;
                $arropening_e_cylinder[$key->id]=$key->opening_e_cylinder;
                $arropening_e_c_price[$key->id]=$key->opening_e_c_price;
            }
            

            //------------------full package ----------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase where date < '$edate' and full_package=1 group by product_id")->result();

            //clossing price each product
            $temp_price = $this->db->query("select product_id,buyprice from purchase WHERE id IN (SELECT MAX(id) AS id FROM purchase where date < '$edate' and full_package=1 group by product_id)")->result();

            if(sizeof($temp_price)>0) foreach ($temp_price as $key) {
                $arrbuypricefullp[$key->product_id]=$key->buyprice;
            }

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
                
            }
        
            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase_return where date < '$edate' and full_package=1 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from daily_sell where date < '$edate' and full_package=1 group by product_id")->result();

            if(sizeof($temp)>0)foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from sell_return where date < '$edate' and full_package=1 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
            }

            //---------------refil---------------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity,avg(buyprice) as buyprice from purchase where date < '$edate' and full_package=0 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }
        
            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase_return where date < '$edate' and full_package=0 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from daily_sell where date < '$edate' and full_package=0 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]-=$key->quantity;
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from sell_return where date < '$edate' and full_package=0 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_quantity[$key->product_id]+=$key->quantity;
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }

            //-------------empty---------------

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase where date < '$edate' and full_package=2 group by product_id")->result();

            //clossing price each product
            $temp_price = $this->db->query("select product_id,buyprice from purchase WHERE id IN (SELECT MAX(id) AS id FROM purchase where date < '$edate' and full_package=2 group by product_id)")->result();

            if(sizeof($temp_price)>0) foreach ($temp_price as $key) {
                $arrbuypriceempty[$key->product_id]=$key->buyprice;
            }

            if(sizeof($temp)>0) foreach ($temp as $key) {
               
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }
        
            $temp = $this->db->query("select product_id,sum(quantity) as quantity from purchase_return where date < '$edate' and full_package=2 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from daily_sell where date < '$edate' and full_package=2 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_e_cylinder[$key->product_id]-=$key->quantity;
            }

            $temp = $this->db->query("select product_id,sum(quantity) as quantity from sell_return where date < '$edate' and full_package=2 group by product_id")->result();

            if(sizeof($temp)>0) foreach ($temp as $key) {
                $arropening_e_cylinder[$key->product_id]+=$key->quantity;
            }

            
            foreach ($tempopening as $key){
                $data['clossingarray'][$key->id] = 0;
                if(array_key_exists($key->id,$arrbuypricefullp))
                $data['clossingarray'][$key->id]+=($arrbuypricefullp[$key->id] * $arropening_quantity[$key->id]);
                else
                $data['clossingarray'][$key->id]+=($arropening_price[$key->id] * $arropening_quantity[$key->id]); 

                if(array_key_exists($key->id,$arrbuypriceempty))
                $data['clossingarray'][$key->id]+=($arrbuypriceempty[$key->id] * $arropening_e_cylinder[$key->id]);
                else
                $data['clossingarray'][$key->id]+=($arropening_e_c_price[$key->id] * $arropening_e_cylinder[$key->id]); 

                $data['clossingstock'] +=$data['clossingarray'][$key->id];
            }
        endif;

        //--------------Closing Stock end
    }
        
        $this->load->view('profitlossbyc', $data);
         else:
             $this->load->view('login', $data);
         endif;
    }



}

?>