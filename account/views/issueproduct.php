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
                    <div class="panel-body">
                        <div class="clearfix">
                            <form class="tasi-form" method="post" action="<?php echo site_url('issueproduct/viewIssue'); ?>">
                                <div class="form-group">

                                    <div class="col-md-5" style="padding-left: 0">
                                        <div class="input-group input-sm" >
                                            <span class="input-group-addon">From </span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" id="sdate" class="form-control" name="sdate" value="<?php echo $sdate; ?>">
                                            </div>
                                            <span class="input-group-addon">To</span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" id="edate" class="form-control" name="edate" value="<?php echo $edate; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">                                       
                                        <div class="iconic-input right">                                           
                                            <select class="form-control selectpicker" data-live-search="true" name="customer" required=""> 
                                                <option value="">-Select-</option>
                                                <?php
                                                $comid = $this->session->userdata('company_id');
                                                $ledgerlist = $this->db->order_by('id', 'desc')->get_where('accountledger',array('accountgroupid' => '30','company_id'=>$comid))->result();
                                                if (sizeof($ledgerlist) > 0):
                                                    foreach ($ledgerlist as $ledger):
                                                        ?>
                                                        <option <?php echo ($ledger->id == $ledgername) ? 'selected' : ''; ?> value="<?php echo $ledger->id; ?>"><?php echo $ledger->ledgername; ?></option> 
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>                                         
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="col-md-1"><button type="submit" class="btn btn-primary">Submit</button></div>                                        
                                </div>                                         
                            </form>                       
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Product Issue details
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
                                <strong>Congratulation!</strong> <?php
                                echo $this->session->userdata('failed');
                                $this->session->unset_userdata('failed');
                                ?>
                            </div> 
                        <?php endif; ?>


                        <span style="float: right"><a href="<?php echo site_url('issueproduct/create_issue');?>"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Issue</button></a></span>

                        <table class="display table table-bordered table-striped" id="example">

                            <thead>
                                <tr>   
                                    <th>S.N</th>
                                    <th>Date</th> 
                                    <th>Product Name</th>    
                                    <th>Quantity</th> 
                                    <th>Unit</th> 
                                    <th>Unit Price</th> 
                                    <th>Receiver</th>                                     
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $total = 0;
                                $i = 1;
                                $totalqty = 0;
                                if (sizeof($issuedata) > 0):
                                    foreach ($issuedata as $isuepro):
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $isuepro->date; ?></td>
                                            <td><?php
                                                echo $isuepro->product_name;
                                                //echo $this->db->get_where('products', array('id' => $pid))->row()->pname;
                                                ?></td>
                                            <td><?php echo $isuepro->quantity; ?></td>
                                            <td><?php
                                                echo $isuepro->unit;
                                                //echo $this->db->get_where('product_unit', array('id' => $unitid))->row()->name;
                                                ?></td>
                                            <td><?php echo $isuepro->unit_price; ?></td>
                                            <td><?php
                                                $ledgerid = $isuepro->product_receiver;
                                                echo $this->db->get_where('accountledger', array('id' => $ledgerid))->row()->ledgername;
                                                ?></td>
                                            
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#editissuelist<?php echo $isuepro->id; ?>"><i class="fa fa-edit" title="Edit issue list"></i></a>&nbsp;&nbsp;
                                                <a href="<?php echo site_url('issueproduct/deleteissue/' . $isuepro->id); ?>" title="Delete issues product" onclick="return confirm('Are you sure want to delete this issue list!!')"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;
                                            </td>                                                                                                              
                                        </tr>
                                        <?php
                                        $totalqty = $totalqty + $isuepro->quantity;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>  
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><?php echo 'Total: '.$totalqty;?></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </div>   

           
            <?php
            $i = 1;

            if (sizeof($issuedata) > 0):
                foreach ($issuedata as $cost):
                    ?>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editissuelist<?php echo $cost->id; ?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">Update issue</h4>
                                </div>

                                <div class="modal-body">

                                    <form class="form-horizontal" role="form"action="<?php echo site_url('issueproduct/updateissue'); ?>" method="post" enctype="multipart/form-data">


                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Product Name</label>
                                            <div class="col-lg-7">
                                                <select class="form-control" name="productname"> 
                                                    <?php
                                                    $plist = $this->db->order_by('id', 'desc')->get_where('products',array('company_id'=>$comid))->result();
                                                    if (sizeof($plist) > 0):
                                                        foreach ($plist as $productn):
                                                            ?>
                                                            <option <?php echo ($productn->id == $cost->product_name) ? 'selected' : ''; ?> value="<?php echo $productn->id; ?>"><?php echo $productn->pname; ?></option> 
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>                                         
                                                </select>                                       
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Product Receiver</label>
                                            <div class="col-lg-7">
                                                <select class="form-control" name="product_receiver"> 
                                                    <?php
                                                    $comid = $this->session->userdata('company_id');
                                                    $ledgerlist = $this->db->order_by('id', 'desc')->get_where('accountledger',array('company_id'=>$comid))->result();
                                                    if (sizeof($ledgerlist) > 0):
                                                        foreach ($ledgerlist as $ledger):
                                                            ?>
                                                            <option <?php echo ($productn->id == $cost->product_receiver) ? 'selected' : ''; ?> value="<?php echo $ledger->id; ?>"><?php echo $ledger->ledgername; ?></option> 
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>                                         
                                                </select>  
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Total Quantity</label>
                                            <div class="col-lg-4">
                                                <input type="number" class="form-control" name="quantity" id="quantity" required="" value="<?php echo $cost->quantity; ?>">
                                            </div>
                                            <div class="col-lg-3">
                                                <select class="form-control" name="unit"> 
                                                    <?php
                                                    $comid = $this->session->userdata('company_id');
                                                    $punit = $this->db->get_where('product_unit',array('company_id'=>$comid))->result();
                                                    if (sizeof($punit) > 0):
                                                        foreach ($punit as $unitn):
                                                            ?>
                                                            <option <?php echo ($unitn->id == $cost->unit) ? 'selected' : ''; ?> <?php echo ($productn->id == $cost->unit) ? 'selected' : ''; ?> value="<?php echo $unitn->id; ?>"><?php echo $unitn->name; ?></option> 
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>                                         
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Price/Amount</label>
                                            <div class="col-lg-7">
                                                <input type="number" class="form-control" name="price" id="price" required="" value="<?php echo $cost->unit_price; ?>"/>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Note</label>
                                            <div class="col-lg-7">
                                                <textarea class="form-control" name="note" id="note"><?php echo $cost->details; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-7">    
                                                <input type="hidden" name="id" value="<?php echo $cost->id; ?>"/>
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
