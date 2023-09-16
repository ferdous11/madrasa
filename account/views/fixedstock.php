<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->   
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <?php if ($this->session->userdata('success')): ?>
                        <div class="alert alert-block alert-success fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                            <strong>Congratulation!</strong> <?php
                            echo $this->session->userdata('success');
                            $this->session->unset_userdata('success');
                            ?>
                        </div> 
                    <?php endif; ?>
                    <div class="panel-heading">Fixed Deposit Asset</div>
                    <div class="panel-body">
                        <span style="float: right"><a href="#" data-toggle="modal" data-target="#addasset"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Asset</button></a></span>
                        <table class="display table table-bordered table-striped" id="example">
                            <?php
                            $comid = $this->session->userdata('company_id');
                            $product = $this->db->query("select * from fixeddeposit where company_id = '$comid' order by id desc")->result();
                            ?>
                            <thead>
                                <tr>   
                                    <th>S.N</th>  
                                    <th>Purchase Date</th> 
                                    <th>Name</th>
                                    <th>Model</th>                                                                
                                    <th>Price</th> 
                                    <th>Quantity</th>    
                                    <th>Total Price</th> 
                                    <th>Depreciation Cost(%)</th> 
                                    <th>Current Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $i = 1;
                                $totalprce = 0;
                                if (sizeof($product) > 0):
                                    foreach ($product as $prodata):
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $prodata->purchase_date; ?></td>
                                            <td><?php echo $prodata->productname; ?></td>                                            
                                            <td><?php echo $prodata->model; ?></td>                                                                                      
                                            <td><?php echo $prodata->buyprice; ?></td>
                                            <td><?php echo $prodata->quantity; ?></td>
                                            <td><?php echo number_format($prodata->quantity * $prodata->buyprice, 2); ?></td>                                             
                                            <td>
                                                <?php
                                                echo $prodata->depreciation_cost;
                                                ?>
                                            </td> 
                                            <td><?php
                                                $dcost = $prodata->depreciation_cost / 100;
                                                $per_daycost = $dcost / 365;
                                                $date1 = new DateTime($prodata->purchase_date);
                                                $newdate1 = $date1->format("Y-m-d");
                                                $newdate2 = date("Y-m-d");
                                                $dateobj1 = date_create($newdate1);
                                                $dateobj2 = date_create($newdate2);
                                                $diff = date_diff($dateobj1, $dateobj2);
                                                $days = $diff->format("%a");
                                                $totalcostfrotoday = $per_daycost * $days * $prodata->quantity;
                                                $totalpriceallqty = $prodata->quantity * $prodata->buyprice;
                                                echo number_format(($totalpriceallqty - $totalcostfrotoday), 2);
                                                ?></td>
                                            <td><a href="#" data-toggle="modal" data-target="#editasset<?php echo $prodata->id; ?>"><i class="fa fa-edit"></i></a>&nbsp;<a href="<?php echo site_url('fixedasset/deleteasset/' . $prodata->id); ?>" onclick="return confirm('Are you sure want to dlete this asset !!')"><i class="fa fa-trash-o"></i></a></td>
                                        </tr>
                                        <?php
                                        $totalprce = $totalprce + ($prodata->quantity * $prodata->buyprice);
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>                                   
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b><?php echo '= ' . number_format($totalprce, 2) ?></b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->



        <?php
        if (sizeof($product) > 0):
            foreach ($product as $cat):
                ?>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editasset<?php echo $cat->id ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Update Asset</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('fixedasset/updateasset'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Asset Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="assetname" id="name" required="" value="<?php echo $cat->productname; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Model</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="assetmodel" id="assetmodel" required="" value="<?php echo $cat->model; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Purchase Date</label>
                                        <div class="col-lg-8">
                                            <input type="datetime" class="form-control" name="sdate" id="sdate" required="" value="<?php echo $cat->purchase_date; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Unit Price</label>
                                        <div class="col-lg-5">
                                            <input type="number" class="form-control" name="unitprice" id="unitprice" required="" value="<?php echo $cat->buyprice; ?>">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" class="form-control" value="BDT" readonly="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Total Quantity</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="quantity" id="quantity" required="" value="<?php echo $cat->quantity; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Depreciation Cost(%)</label>
                                        <div class="col-lg-5">
                                            <input type="number" class="form-control" name="depreciationcosr" id="depreciationcosr" required="" value="<?php echo $cat->depreciation_cost; ?>">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" class="form-control" value="BDT" readonly="">
                                        </div>
                                    </div>




                                    <div class="modal-footer">
                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-8">
                                                <input type="hidden" name="id" value="<?php echo $cat->id ?>"/>
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                <button type="submit" class="btn btn-default">Submit</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
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

        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addasset" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Asset</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('fixedasset/addasset'); ?>" method="post" enctype="multipart/form-data">


                            <div class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Account Group</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="accountgroup" required="">
                                        <?php
                                        $acgroup = $this->db->get('accountgroup')->result();
                                        if (sizeof($acgroup) > 0):
                                            foreach ($acgroup as $acg):
                                                ?>
                                                <option <?php echo ($acg->id == '16') ? 'selected' : ''; ?> value="<?php echo $acg->id; ?>"><?php echo $acg->name ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div> 


                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Asset Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="assetname" id="name" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Model</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="assetmodel" id="assetmodel" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Purchase Date</label>
                                <div class="col-lg-8">
                                    <input type="datetime" class="form-control" name="sdate" id="sdate" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Unit Price</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" name="unitprice" id="unitprice" required="">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" class="form-control" value="BDT" readonly="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Total Quantity</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="quantity" id="quantity" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Depreciation Cost(%)</label>
                                <div class="col-lg-5">
                                    <input type="number" class="form-control" name="depreciationcosr" id="depreciationcosr" required="">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" class="form-control" value="BDT" readonly="">
                                </div>
                            </div>




                            <div class="modal-footer">
                                <div class="form-group">
                                    <div class="col-lg-offset-4 col-lg-8">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-default">Submit</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

    </section>
</section>

<?php include 'footer.php'; ?>
