<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->                    
        <div class="row">
            <div class="col-lg-5">                

                <section class="panel">
                    <header class="panel-heading">
                        Add Cash Flow Budget
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
                       
                        <div class="form">
                            <form class="cmxform form-horizontal" method="post" action="<?php echo site_url('home/addcashflow'); ?>">
                                <br/>
                                <div class="form-group ">
                                    <label class="control-label col-lg-3">Particular:</label>
                                    <div class="col-lg-5">
                                        <?php
                                        $getgood = $this->db->order_by('id', 'DESC')->get('goods')->result();
                                        ?>
                                        <select class="form-control" name="goodsname" id="goodsname">
                                            <?php
                                            if (sizeof($getgood) > 0):
                                                foreach ($getgood as $gnd):
                                                    ?>
                                                    <option value="<?php echo $gnd->goodsname; ?>"><?php echo $gnd->goodsname; ?></option>      
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <a href="#" data-toggle="modal" data-target="#addnewitam"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add Item</button></a>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-3">Total Amount:</label>
                                    <div class="col-lg-5">
                                        <input class=" form-control" id="amount" name="amount" type="number" required="" placeholder="0.00"/>  
                                        <span style="color: red"><?php echo form_error('fullname'); ?></span>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" style="color: #000;font-weight: bold" class="form-control" value="BDT" readonly="">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-3">Add by:</label>
                                    <div class="col-lg-8">
                                        <input class=" form-control" id="addby" name="addby" type="text" value="<?php echo $this->session->userdata('fullname'); ?>" required=""/>                                          
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-3">Budget Month:</label>
                                    <div class="col-lg-8">
                                        <input class=" form-control" id="sdate" name="budgetmonth" type="text" required="" value="<?php echo $month_b;?>"/>                                          
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-3">Details:</label>
                                    <div class="col-lg-8">
                                        <textarea class=" form-control" id="details" name="details" required=""/></textarea> 
                                        <span style="color: red"><?php echo form_error('address'); ?></span>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-8">                                        
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button class="btn btn-primary" type="submit">Submit</button>&nbsp;&nbsp;
                                        <a href="<?php echo site_url('home');?>"><button class="btn btn-default" type="button">Cancel</button></a>
                                    </div>
                                </div>

                            </form>
                        </div>



                        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addnewitam" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <h4 class="modal-title">Add New Particular</h4>
                                    </div>

                                    <div class="modal-body">

                                        <form class="form-horizontal" role="form"action="<?php echo site_url('home/addgoods'); ?>" method="post" enctype="multipart/form-data">

                                            <div class="form-group">
                                                <label for="name" class="col-lg-4 col-sm-4 control-label">New Particular</label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" name="itemname" id="itemname" placeholder="Item name" required="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-lg-offset-4 col-lg-8">
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

                    </div>
                </section>
            </div>   

            <div class="col-lg-7">

                <div class="panel" style="margin-bottom: 0px">
                    <div class="panel-body" style="padding-bottom: 20px">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('home/viewcashflow'); ?>" style="margin-left: 15px;">

                            <div class="col-lg-4">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date From</label>
                                    <input class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="combodateFrom"/>                               
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date To</label>
                                    <input class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="edate"/>
                                </div>
                            </div> 

                            <div class="col-lg-2">                           
                                <div class="form-group" style="margin-right: 0px">     
                                    <label class="control-label"><br/></label>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input style="margin-top: 25px;margin-left: 20px" class="btn btn-primary" type="submit" value="Submit"/>
                                </div>
                            </div>

                        </form>
                        <br/>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">Details Cost</div>

                    <div class="panel-body">                        

                        <table class="display table table-bordered table-striped" id="example">

                            <thead>
                                <tr>   
                                    <th>S.N</th>
                                    <th>Particular</th>                                    
                                    <th>Amount</th>
                                    <th>Details</th>
                                    <th>Date</th>                              
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                $i = 1;
                                if (sizeof($cashdata) > 0):
                                    foreach ($cashdata as $ucost):
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $ucost->particular; ?></td>
                                            <td><?php echo $ucost->amount; ?></td>
                                            <td><?php echo $ucost->details; ?></td>
                                            <td><?php echo $ucost->for_month; ?></td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#editutility<?php echo $ucost->id; ?>"><i class="fa fa-edit" title="Edit"></i></a>
                                                <a href="<?php echo site_url('home/deletecashflow/' . $ucost->id); ?>" onclick="return confirm('Are you sure want to delete !!')"><i class="fa fa-trash-o" title="Delete" style="color: red"></i></a>
                                            </td>                                                                                                              
                                        </tr>
                                        <?php
                                        $total = $total + $ucost->amount;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>   
                                    <th></th>
                                    <th></th>
                                    <th><?php echo 'Total=' . number_format($total, 2); ?></th>
                                    <th></th>
                                    <th></th>                              
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


            <?php
            $i = 1;
            if (sizeof($cashdata) > 0):
                foreach ($cashdata as $cost):
                    ?>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editutility<?php echo $ucost->id; ?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">Update Cash Flow Budget</h4>
                                </div>
                                <div class="modal-body">

                                    <form class="form-horizontal" role="form"action="<?php echo site_url('home/updatecashflow'); ?>" method="post" enctype="multipart/form-data">

                                        <div class="form-group ">
                                            <label class="control-label col-lg-3">Particular:</label>
                                            <div class="col-lg-6">
                                                <?php
                                                $getgood = $this->db->get('goods')->result();
                                                ?>
                                                <select class="form-control" name="goodsname" id="goodsname">
                                                    <?php
                                                    if (sizeof($getgood) > 0):
                                                        foreach ($getgood as $gnd):
                                                            ?>
                                                            <option <?php echo ($cost->particular == $gnd->goodsname) ? 'selected' : '' ?> value="<?php echo $gnd->goodsname; ?>"><?php echo $gnd->goodsname; ?></option>      
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>                                    
                                        </div>

                                        <div class="form-group ">
                                            <label class="control-label col-lg-3">Total Amount:</label>
                                            <div class="col-lg-4">
                                                <input class=" form-control" id="amount" name="amount" type="text" required="" value="<?php echo $cost->amount; ?>"/>                                          
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="text" style="color: #000;font-weight: bold" class="form-control" value="BDT" readonly="">
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label class="control-label col-lg-3">Add by:</label>
                                            <div class="col-lg-6">
                                                <input class=" form-control" id="addby" name="addby" type="text" required="" value="<?php echo $cost->addby; ?>"/>                                          
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label class="control-label col-lg-3">Budget Month:</label>
                                            <div class="col-lg-8">
                                                <input class=" form-control" id="sdate" name="budgetmonth" type="text" required="" value="<?php echo $cost->for_month; ?>"/>                                          
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label class="control-label col-lg-3">Details:</label>
                                            <div class="col-lg-6">
                                                <textarea class=" form-control" id="details" name="details" required=""/><?php echo $cost->details; ?></textarea>                                         
                                            </div>
                                        </div>                     

                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-7">
                                                <input type="hidden" name="uid" value="<?php echo $cost->id; ?>"/>
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

        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
