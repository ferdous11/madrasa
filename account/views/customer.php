<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        All Customer
                    </header>
                    <div class="panel-body">
                        <?php if ($this->session->userdata('success')): ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Congratulation!</strong> <?php
                                echo $this->session->userdata('success');
                                $this->session->unset_userdata('success');
                                ?>
                            </div> 
                        <?php endif; ?>
                        <?php if ($this->session->userdata('failed')): ?>
                            <div class="alert alert-block alert-danger fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Oops!</strong> <?php
                                echo $this->session->userdata('failed');
                                $this->session->unset_userdata('failed');
                                ?>
                            </div> 
                        <?php endif; ?>
                        <div class="form">
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addcustomer"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Customer</button></a></span>
                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">

                                <thead>
                                    <tr>                                    
                                        <th>ID</th>                                       
                                        <th>Customer Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Address</th>                                         
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (sizeof($customerdata) > 0):
                                        foreach ($customerdata as $customer):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo $customer->ledgername; ?></td>                                                                                             
                                                <td><?php echo $customer->mobile; ?></td>
                                                <td><?php echo $customer->email; ?></td>
                                                <td><?php echo $customer->address; ?></td>                                                                                                                                        
                                                <td><a href="#" data-toggle="modal" data-target="#editcustomer<?php echo $customer->id ?>"><i class="fa fa-edit"></i></a>&nbsp; 

                                                    <a href="<?php echo site_url('master/deleteledger/' . $customer->id); ?>" onclick="return confirm('Are you sure want to delete this customer !!')"><i class="fa fa-trash-o"></i></a></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </section>
            </div>          
        </div>


        <?php
        if (sizeof($customerdata) > 0):
            foreach ($customerdata as $cusstomer):
                ?>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editcustomer<?php echo $cusstomer->id; ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Update Customer Information</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('master/updateledger'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Customer Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="ledgername" id="ledgername" value="<?php echo $cusstomer->ledgername; ?>" required="">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Account Number</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="bankacc" id="bankacc" value="<?php echo $cusstomer->accno; ?>" required="">
                                        </div>
                                        <div class="col-lg-3">                                    
                                            <select class="form-control" name="bank_name">                                      
                                                <option value="AB Bank Limited">AB Bank Limited</option>
                                                <option value="Al-Arafah Islami Bank">Al-Arafah Islami Bank</option>
                                                <option value="Bangladesh Commerce Bank Limited">Bangladesh Commerce Bank Limited</option>
                                                <option value="Bank Asia Limited">Bank Asia Limited</option>
                                                <option value="BRAC Bank Limited">BRAC Bank Limited</option>
                                                <option value="City Bank Limited">City Bank Limited</option>
                                                <option value="Dhaka Bank Limited">Dhaka Bank Limited</option>
                                                <option value="Dutch-Bangla Bank Limited">Dutch-Bangla Bank Limited</option>
                                                <option value="Eastern Bank Limited">Eastern Bank Limited</option>
                                                <option value="IFIC Bank Limited">IFIC Bank Limited</option>
                                                <option value="Jamuna Bank Limited">Jamuna Bank Limited</option>
                                                <option value="Meghna Bank Limited">Meghna Bank Limited</option>
                                                <option value="Mercantile Bank Limited">Mercantile Bank Limited</option>
                                                <option value="rerereMidland Bank Limitedre">Midland Bank Limited</option>
                                                <option value="Modhumoti Bank Limited">Modhumoti Bank Limited</option>
                                                <option value="Mutual Trust Bank Limited">Mutual Trust Bank Limited</option>
                                                <option value="National Bank Limited">National Bank Limited</option>
                                                <option value="National Credit & Commerce Bank Limited">National Credit & Commerce Bank Limited</option>
                                                <option value="NRB Bank Limited">NRB Bank Limited</option>
                                                <option value="NRB Commercial Bank Limited">NRB Commercial Bank Limited</option>
                                                <option value="NRB Global Bank Limited">NRB Global Bank Limited</option>
                                                <option value="One Bank Limited">One Bank Limited</option>
                                                <option value="Premier Bank Limited">Premier Bank Limited</option>
                                                <option value="Prime Bank Limited">Prime Bank Limited</option>
                                                <option value="Pubali Bank Limited">Pubali Bank Limited</option>
                                                <option value="South Bangla Agriculture & Commerce Bank Limited">South Bangla Agriculture & Commerce Bank Limited</option>
                                                <option value="Southeast Bank Limited">Southeast Bank Limited</option>
                                                <option value="Standard Bank Limited">Standard Bank Limited</option>
                                                <option value="The Farmers Bank Limited">The Farmers Bank Limited</option>
                                                <option value="Trust Bank Limited">Trust Bank Limited</option>
                                                <option value="United Commercial Bank Limited">United Commercial Bank Limited</option>
                                                <option value="Uttara Bank Limited">Uttara Bank Limited</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile" class="col-lg-4 col-sm-4 control-label">Mobile</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="mobile" id="mobile" maxlength="11" value="<?php echo $cusstomer->mobile; ?>" required="">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label for="email" class="col-lg-4 col-sm-4 control-label">Email</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $cusstomer->email; ?>" required="">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="address" id="address" value="<?php echo $cusstomer->address; ?>" required="">
                                        </div>
                                    </div> 



                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-8">
                                            <input type="hidden" name="fromname" value="customer"/>
                                            <input type="hidden" name="updateid" value="<?php echo $cusstomer->id; ?>"/>
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <button type="submit" class="btn btn-default">Submit</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        endif;
        ?>
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addcustomer" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Customer</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/addledger'); ?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Account Group</label>
                                <div class="col-lg-7">
                                    <select class="form-control" name="accountgroup" required="">
                                        <?php
                                        $acgroup = $this->db->get_where('accountgroup',array('name' => 'customer'))->result();
                                        if (sizeof($acgroup) > 0):
                                            foreach ($acgroup as $acg):
                                                ?>
                                                <option value="<?php echo $acg->id; ?>"><?php echo $acg->name ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div> 
                            
                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Customer Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" maxlength="100" id="name" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Account Number</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" name="bankacc" id="bankacc">
                                </div>
                                <div class="col-lg-3">                                    
                                    <select class="form-control" name="bank_name">                                      
                                        <option value="AB Bank Limited">AB Bank Limited</option>
                                        <option value="Al-Arafah Islami Bank">Al-Arafah Islami Bank</option>
                                        <option value="Bangladesh Commerce Bank Limited">Bangladesh Commerce Bank Limited</option>
                                        <option value="Bank Asia Limited">Bank Asia Limited</option>
                                        <option value="BRAC Bank Limited">BRAC Bank Limited</option>
                                        <option value="City Bank Limited">City Bank Limited</option>
                                        <option value="Dhaka Bank Limited">Dhaka Bank Limited</option>
                                        <option value="Dutch-Bangla Bank Limited">Dutch-Bangla Bank Limited</option>
                                        <option value="Eastern Bank Limited">Eastern Bank Limited</option>
                                        <option value="IFIC Bank Limited">IFIC Bank Limited</option>
                                        <option value="Jamuna Bank Limited">Jamuna Bank Limited</option>
                                        <option value="Meghna Bank Limited">Meghna Bank Limited</option>
                                        <option value="Mercantile Bank Limited">Mercantile Bank Limited</option>
                                        <option value="rerereMidland Bank Limitedre">Midland Bank Limited</option>
                                        <option value="Modhumoti Bank Limited">Modhumoti Bank Limited</option>
                                        <option value="Mutual Trust Bank Limited">Mutual Trust Bank Limited</option>
                                        <option value="National Bank Limited">National Bank Limited</option>
                                        <option value="National Credit & Commerce Bank Limited">National Credit & Commerce Bank Limited</option>
                                        <option value="NRB Bank Limited">NRB Bank Limited</option>
                                        <option value="NRB Commercial Bank Limited">NRB Commercial Bank Limited</option>
                                        <option value="NRB Global Bank Limited">NRB Global Bank Limited</option>
                                        <option value="One Bank Limited">One Bank Limited</option>
                                        <option value="Premier Bank Limited">Premier Bank Limited</option>
                                        <option value="Prime Bank Limited">Prime Bank Limited</option>
                                        <option value="Pubali Bank Limited">Pubali Bank Limited</option>
                                        <option value="South Bangla Agriculture & Commerce Bank Limited">South Bangla Agriculture & Commerce Bank Limited</option>
                                        <option value="Southeast Bank Limited">Southeast Bank Limited</option>
                                        <option value="Standard Bank Limited">Standard Bank Limited</option>
                                        <option value="The Farmers Bank Limited">The Farmers Bank Limited</option>
                                        <option value="Trust Bank Limited">Trust Bank Limited</option>
                                        <option value="United Commercial Bank Limited">United Commercial Bank Limited</option>
                                        <option value="Uttara Bank Limited">Uttara Bank Limited</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="mobile" class="col-lg-4 col-sm-4 control-label">Mobile</label>
                                <div class="col-lg-8">
                                    <!--<input type="hidden" class="form-control" maxlength="20" name="accountgroup" id="accountgroup" value="203">
                                    <input type="hidden" class="form-control" maxlength="16" name="bankacc" id="bankacc" value="undefined">-->
                                    <input type="text" class="form-control" maxlength="11" name="mobile" id="mobile">
                                </div>
                            </div> 


                            <div class="form-group">
                                <label for="opbalance" class="col-lg-4 col-sm-4 control-label">Opening Balance</label>
                                <div class="col-lg-4">
                                    <input type="number" class="form-control" maxlength="11" name="opbalance" id="opbalance" value="0">
                                </div>
                                <div class="col-lg-3">
                                    <select class="form-control" name="baltype">
                                        <option value="credit">Credit</option>
                                        <option value="debit" selected="">Debit</option>
                                    </select>
                                </div>
                            </div> 

                            <div class="form-group">
                                <label for="email" class="col-lg-4 col-sm-4 control-label">Email</label>
                                <div class="col-lg-8">
                                    <input type="email" class="form-control" name="email" maxlength="50" id="email">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="address" id="address" maxlength="150">
                                </div>
                            </div>                            

                            <div class="form-group">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <input type="hidden" name="fromname" value="customer"/>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: 25
        });
    });
</script>
<?php include 'footer.php'; ?>
