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
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('reports/viewsellhistory'); ?>">

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date From</label>
                                    <input class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="sdate"/>                               
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date To</label>
                                    <input class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="edate"/>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Customer</label>
                                    <?php
                                    $getbuyer = $this->db->query("select id,buyername from daily_sell where stype = 'sell' group by buyername")->result();
                                    ?>
                                    <select class="form-control" name="customer" id="customer">
                                        <option value="all">ALL</option>   
                                        <?php
                                        if (sizeof($getbuyer) > 0):
                                            $customerName = '0';
                                            foreach ($getbuyer as $buyer):
                                                $buyermm = $buyer->buyername;
                                                $customerName = $this->db->query("select * from accountledger where id = '$buyermm'")->row()->ledgername;
                                                ?>
                                                <option value="<?php echo $buyer->buyername; ?>"><?php echo $customerName; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">                           
                                <div class="form-group" style="margin-right: 0px">     
                                    <label class="control-label"><br/></label>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input style="margin-top: 25px;margin-left: 20px" class="btn btn-primary" type="submit" value="Submit"/>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Details Sales History
                    </header>
                    <div class="panel-body">
                        
                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable" id="example">
                                <thead>
                                    <tr> 
                                        <th>Invoice Id</th>
                                        <th>Date</th>  
                                        <th>Party</th>                                       
                                        <th>Total Bill</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $totalbill = 0;

                                    if (sizeof($selldata) > 0):
                                        foreach ($selldata as $sell):
                                            if ($sell->totalsell != ''):
                                                ?>
                                                <tr>
                                                    <td><a href="<?php echo site_url('reports/detailssell/' . $sell->invoice_id); ?>" title="View details of sell"><?php echo $sell->invoice_id; ?></a></td>
                                                    <td><?php echo $sell->date; ?></td>
                                                    <td>
                                                        <?php
                                                        $buyerid = $sell->buyername;
                                                        $allledger = $this->db->get_where('accountledger', array('id' => $buyerid))->row();
                                                        if (sizeof($allledger) > 0):
                                                            echo $allledger->ledgername;
                                                        else:
                                                            echo '';
                                                        endif;
                                                        ?>
                                                    </td> 
                                                    <td><?php echo number_format($sell->totalsell, 2); ?></td>                                               
                                                    <td>
                                                        <a href="<?php echo site_url('sell/printinvoice/' . $sell->invoice_id) ?>" title="Print Sales invoice"><i class="fa fa-print"></i></a>
                                                        <a href="<?php echo site_url('reports/detailssell/' . $sell->invoice_id); ?>" title="View details of Sales"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $totalbill = $totalbill + $sell->totalsell;
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr> 
                                        <th></th>
                                        <th></th>
                                        <th></th>                                       
                                        <th><?php echo '= ' . number_format($totalbill, 2); ?></th>  
                                        <th></th>  
                                        
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </section>
            </div>          
        </div>

        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
