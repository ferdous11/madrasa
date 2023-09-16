<aside>
    <style>
        #menu_hide{
            display: none;
        }
        #menu_dispaly{
            display: block;
        }
        #submenu_hide{
            display: none;
        }
        #submenu_dispaly{
            display: block;
        }
    </style>
    <div id="sidebar"  class="nav-collapse ">
        <?php
        ini_set('memory_limit', '1024M');
        $submenu = array();
        $mainmenu = array();
        $role = $this->session->userdata('role');
        $temp = $this->db->get_where("role", array('title' => $role))->row();


        if ($temp):
            $smenu = $temp->menu_link;
            $submenu = explode(',', $smenu);
            // echo '<pre>';
            // print_r($submenu);
            // echo '</pre>';
            $temp2 = $this->db->query("select main_menu from menu where id in (".$temp->menu_id.") group by main_menu")->result();
            foreach ($temp2 as $key) {
                $mainmenu[]=$key->main_menu;
            }

        endif;
        ?>
        
        <ul class="sidebar-menu" id="nav-accordion">
            <li id="<?php echo 'menu_dispaly';?>" class="<?php echo ($activemenu == 'dashboard') ? 'active' : '' ?>">
                <a href="<?php echo site_url('home'); ?>">
                    <i class="fa fa-caret-right"></i>
                    <span>Dashboard</span>
                </a>
            </li> 

            <li id="<?php echo in_array('Master', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'master') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Master</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul style="display: none;" class="sub">                    

                    <li id="<?php echo in_array('master/acgroup', $submenu)?'submenu_dispaly':'submenu_hide'?>"  class="<?php echo ($activesubmenu == 'acgroup') ? 'active' : '' ?>"><a href="<?php echo site_url('master/acgroup'); ?>"><i class="fa fa-caret-right"></i> Account Group</a></li>
                    <li id="<?php echo in_array('master/acledger', $submenu)?'submenu_dispaly':'submenu_hide'?>"  class="<?php echo ($activesubmenu == 'acledgers') ? 'active' : '' ?>"><a href="<?php echo site_url('master/acledger'); ?>"><i class="fa fa-caret-right"></i> Account Ledger</a></li>


                    <li id="<?php echo in_array('product/category', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'category') ? 'active' : '' ?>"><a href="<?php echo site_url('product/category'); ?>"><i class="fa fa-caret-right"></i> Category</a></li>


                    <li id="<?php echo in_array('product/subcategory', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'subcategory') ? 'active' : '' ?>"><a href="<?php echo site_url('product/subcategory'); ?>"><i class="fa fa-caret-right"></i> Sub Category</a></li>
                
                    <!-- <li class="<?php echo ($activesubmenu == 'manufacturer') ? 'active' : '' ?>"><a href="<?php echo site_url('master/manufacturer'); ?>"><i class="fa fa-caret-right"></i> Manufacturer</a></li> -->
                    <li id="<?php echo in_array('master', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'units') ? 'active' : '' ?>"><a href="<?php echo site_url('master'); ?>"><i class="fa fa-caret-right"></i> Units</a></li>                    
                    <li id="<?php echo in_array('product', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'products') ? 'active' : '' ?>"><a href="<?php echo site_url('product'); ?>"><i class="fa fa-caret-right"></i> Products</a></li>
                    <li id="<?php echo in_array('master/dbbackup', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'backup') ? 'active' : '' ?>"><a href="<?php echo site_url('master/dbbackup'); ?>"><i class="fa fa-caret-right"></i> Back Up DB</a></li>
                    <!-- <li class="<?php echo ($activesubmenu == 'pgroup') ? 'active' : '' ?>"><a href="<?php echo site_url('productgroup'); ?>"><i class="fa fa-caret-right"></i> Product Group</a></li> -->
                </ul>
            </li>

            <li id="<?php echo in_array('Transection', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'transection') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Transection</span>
                    <span class="dcjq-icon"></span>
                </a>

                <ul style="display: none;" class="sub"> 
                    
                    <li id="<?php echo in_array('sell', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'sell') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('sell'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Sales</span>
                        </a>
                    </li>
                    
                    <li id="<?php echo in_array('purchase', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'purchase') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('purchase'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Purchase</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('purchase/purchase_return', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'purchase_return') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('purchase/purchase_return'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Purchase Return</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('sellreturn', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'sell_return') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('sellreturn'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Sales Return</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('payments', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'payments') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('payments'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Payment Voucher</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('received', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'received') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('received'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Received Voucher</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('journalentry', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'journalentry') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('journalentry'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Journal Entry</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('contravoucher', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'contravoucher') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('contravoucher'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Contravoucher</span>
                        </a>
                    </li>

                </ul>
            </li>

            <li id="<?php echo in_array('Account Statement', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'accountstatement') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Account Statement</span>
                    <span class="dcjq-icon"></span>
                </a>

                <ul style="display: none;" class="sub"> 

                    <li id="<?php echo in_array('cashbook', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'cashbook') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('cashbook'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Cash Book</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('bankbook', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'bankbook') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('bankbook'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Bank Book</span>
                        </a>
                    </li>                     

                    <li id="<?php echo in_array('trialbalance', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'trialbalance') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('trialbalance'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Trial Balance</span>
                        </a>
                    </li> 


                    <li id="<?php echo in_array('ledgerbalance', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'ledgerbalance') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('ledgerbalance'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Ledger Balance</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('profitloss', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'profitloss') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('profitloss'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Profit Loss</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('balancesheet', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'balancesheet') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('balancesheet'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Balance Sheet</span>
                        </a>
                    </li>
                </ul>

            </li>

            <li id="<?php echo in_array('Employee', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'Employee') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Employee</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul style="display: none;" class="sub"> 

                    <li id="<?php echo in_array('employee/employee_salary', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'employee_salary') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('employee/employee_salary'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Employee Salary</span>                           
                        </a>
                    </li> 

                    <li id="<?php echo in_array('employee/attendance_report', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'attendance_report') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('employee/attendance_report'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Attendance Report</span>                           
                        </a>
                    </li> 

                    <li id="<?php echo in_array('employee/assign_salary', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'employee/assign_salary') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('employee/assign_salary'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Assign Salary</span>                           
                        </a>
                    </li> 
                </ul>

            </li>

            <li id="<?php echo in_array('Reports', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'reports') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Reports</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul style="display: none;" class="sub"> 

                    <li id="<?php echo in_array('reports/sellhistory', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'sellhistory') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/sellhistory'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Sales Summary</span>                           
                        </a>
                    </li> 
                    <li id="<?php echo in_array('reports/selldetails', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'selldetails') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/selldetails'); ?>">
                            <i class="fa fa-caret-right"></i>                           
                            <span>Sales Details</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('purchase/purchasehistory', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'purchasehistory') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('purchase/purchasehistory'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Purchase Summary</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('purchase/purchasedetails', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'purchasedetails') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('purchase/purchasedetails'); ?>">
                            <i class="fa fa-caret-right"></i>                           
                            <span>Purchase Details</span>
                        </a>
                    </li> 



                    <li id="<?php echo in_array('reports/salesreturn', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'purchasereturn') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/purchasereturn'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Purchase Return</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('reports/salesreturn', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'sellreturn') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/salesreturn'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Sales Return</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('reports/payment', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'payment') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/payment'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Payment History</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('reports/received', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'received') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/received'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Received History</span>
                        </a>
                    </li>  

                    <li id="<?php echo in_array('product/summary', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'productsummary') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('product/summary'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Product Summary</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('reports/rawstock', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'rawstock') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/rawstock'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Product Stock</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('reports/fixedstock', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'fixedstock') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/fixedstock'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Fixed Deposit Asset</span>
                        </a>
                    </li> 
                    <li id="<?php echo in_array('reports/supcusbalance', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'supcusbalance') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/supcusbalance'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Balance by Group</span>
                        </a>
                    </li> 
                    <li id="<?php echo in_array('reports/notification', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'notification') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('reports/notification'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Notification</span>
                        </a>
                    </li> 
                </ul>

            </li>

            

            <li id="<?php echo in_array('Cash Flow Budget', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="<?php echo ($activemenu == 'cashbudget') ? 'active' : '' ?>">
                <a href="<?php echo site_url('home/cashbudget'); ?>">
                    <i class="fa fa-caret-right"></i>
                    <span>Cash Flow Budget</span>
                </a>
            </li>

            <li id="<?php echo in_array('Bank Management', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'bankmanagement') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Bank Management</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul style="display: none;" class="sub"> 

                    <li id="<?php echo in_array('bankmanagement/bankgroup', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'bankgroup') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('bankmanagement/bankgroup'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Account Group</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('bankmanagement', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'banklist') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('bankmanagement'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Bank List</span>
                        </a>
                    </li>                    


                    <li id="<?php echo in_array('bankmanagement/transectionhistory', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'transectionhistory') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('bankmanagement/transectionhistory'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Transection History</span>
                        </a>
                    </li>                    
                </ul>
            </li>

            <li id="<?php echo in_array('Production Process', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'production') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Production Process</span>
                    <span class="dcjq-icon"></span>
                </a>

                <ul style="display: none;" class="sub"> 
                    <li id="<?php echo in_array('issueproduct', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'issue') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('issueproduct'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Issue Product</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('receiveproduct', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'receive') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('receiveproduct'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Receive Product</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('production', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'productreport') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('production'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Production Report</span>
                        </a>
                    </li>

                </ul>
            </li>

            <!--<li id="<?php echo in_array('Dashboard', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="<?php echo ($activemenu == 'utilities') ? 'active' : '' ?>">
                <a href="<?php echo site_url('#'); ?>">
                    <i class="fa fa-caret-right"></i>
                    <span>Raw Material Recycling</span>
                </a>
            </li>-->

            <li id="<?php echo in_array('Settings', $mainmenu)?'menu_dispaly':'menu_hide'?>" class="sub-menu dcjq-parent-li">
                <a class="dcjq-parent <?php echo ($activemenu == 'settings') ? 'active' : '' ?>" href="#">
                    <i class="fa fa-caret-right"></i>
                    <span>Settings</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul style="display: none;" class="sub">
                    <li id="<?php echo in_array('home/manageuser', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'manageuser') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('home/manageuser'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>User Management</span>
                        </a>
                    </li>  

                    <li id="<?php echo in_array('home/accountsetings', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'accountsettings') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('home/accountsetings'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Account Settings</span>
                        </a>
                    </li> 

                    <li id="<?php echo in_array('company', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'storesettings') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('company'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Store/Factory Settings</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('rolemanagement', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'rolemanage') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('rolemanagement'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Role Management</span>
                        </a>
                    </li>

                    <li id="<?php echo in_array('aclog', $submenu)?'submenu_dispaly':'submenu_hide'?>" class="<?php echo ($activesubmenu == 'aclog') ? 'active' : '' ?>">
                        <a href="<?php echo site_url('aclog'); ?>">
                            <i class="fa fa-caret-right"></i>
                            <span>Activity Log</span>
                        </a>
                    </li> 
                </ul>
            </li>


            <li>
                <a  href="<?php echo site_url('home/logout'); ?>">
                    <i class="fa fa-caret-right"></i>
                    <span>Log Out</span>
                </a>
            </li>


        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>