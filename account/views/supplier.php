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
                        All Supplier
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
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addsupplier"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Supplier</button></a></span>
                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">
                                <thead>
                                    <tr>                                    
                                        <th>SN</th>                                       
                                        <th>Supplier Name</th>
                                        <th>Mobile</th>                                       
                                        <th>Account Number</th>
                                        <th>Email</th>
                                        <th>Address</th> 
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $payble = 0;
                                    $netbalance = 0;
                                    if (sizeof($supplierdata) > 0):
                                        foreach ($supplierdata as $supplier):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo $supplier->ledgername; ?></td>                                                                                             
                                                <td><?php echo $supplier->mobile; ?></td>
                                                <td><?php echo $supplier->email; ?></td>                                               
                                                <td><?php echo $supplier->accno; ?></td>
                                                <td><?php echo $supplier->address; ?></td>                                                                                                                                        
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#editsupplier<?php echo $supplier->id ?>"><i class="fa fa-edit"></i></a>&nbsp; 
                                                    <a href="<?php echo site_url('master/deleteledger/' . $supplier->id); ?>" onclick="return confirm('Are you sure want to delete this Supplier !!')"><i class="fa fa-trash-o"></i></a>
                                                    <!--<a href="<?php echo site_url('purchase/paymenthistory/' . $supplier->id); ?>"><i class="fa fa-eye"></i></a>-->
                                                </td>
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
        if (sizeof($supplierdata) > 0):
            foreach ($supplierdata as $supp):
                ?>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editsupplier<?php echo $supp->id; ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Update Supplier Information</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('master/updateledger'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Supplier Name</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" name="ledgername" id="ledgername" value="<?php echo $supp->ledgername; ?>" required="">
                                        </div>
                                    </div>  


                                    <div class="form-group">
                                        <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Account Number</label>
                                        <div class="col-lg-4">                                    
                                            <input type="text" class="form-control" maxlength="16" name="bankacc" id="bankacc" value="<?php echo $supp->accno; ?>">
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
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $supp->mobile; ?>" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-lg-4 col-sm-4 control-label">Email</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $supp->email; ?>" required="">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                        <div class="col-lg-7">
                                            <textarea type="text" class="form-control" name="address" id="address" required=""><?php echo $supp->address; ?></textarea>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-7">
                                            <input type="hidden" name="fromname" value="supplier"/>
                                            <input type="hidden" name="updateid" value="<?php echo $supp->id; ?>"/>
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
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addsupplier" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Supplier</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/addledger'); ?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Account Group</label>
                                <div class="col-lg-7">
                                    <select class="form-control" name="accountgroup" required="">
                                        <?php
                                        $acgroup = $this->db->get_where('accountgroup',array('name' => 'supplier'))->result();
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
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Supplier Name</label>
                                <div class="col-lg-7">
                                    <!--<input type="hidden" class="form-control" maxlength="20" name="accountgroup" id="accountgroup" value="202">
                                    <input type="hidden" class="form-control" maxlength="16" name="bankacc" id="bankacc" value="Undefined">-->
                                    <input type="text" class="form-control" name="name" id="name" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Account Number</label>
                                <div class="col-lg-4">                                    
                                    <input type="text" class="form-control" maxlength="16" name="bankacc" id="bankacc">
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
                                <div class="col-lg-7">
                                    <input type="number" class="form-control" maxlength="11" name="mobile" id="mobile" required="">
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
                                <div class="col-lg-7">
                                    <input type="email" class="form-control" name="email" maxlength="50" id="email">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                <div class="col-lg-7">
                                    <textarea class="form-control" name="address" id="address" maxlength="150"></textarea>
                                </div>
                            </div> 

                            <div class="form-group">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <input type="hidden" name="fromname" value="supplier"/>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <button type="submit" class="btn btn-default">Submit</button>&nbsp;&nbsp;
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
