<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<style>
    .panel-body table tr td:first-child{
        text-align: right
    }
    .panel-body table tr td:last-child{
        text-align: left
    }
</style>
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-6">                
                <div class="panel">
                    <div class="panel-heading">Purchase details from User</div>
                    <div class="panel-body">
                        <table class="table table-bordered">

                            <tr>
                                <th style="text-align: right">Name</th>
                                <th style="text-align: left">Description</th>
                            </tr>

                            <tr>
                                <td>Product name</td>
                                <td><?php echo $purcharsedata->product_name; ?></td>
                            </tr>

                            <tr>
                                <td>Purchase from</td>
                                <td>
                                    <?php
                                    $supid = $purcharsedata->supplier_id;
                                    echo $this->db->get_where('suppliers', array('id' => $supid))->row()->name;
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Product model</td>
                                <td><?php echo $purcharsedata->pmodel; ?></td>
                            </tr>

                            <tr>
                                <td>Product quantity</td>
                                <td><?php echo $purcharsedata->quantity; ?></td>
                            </tr>

                            <tr>
                                <td>Unit price</td>
                                <td><?php echo $purcharsedata->buyprice; ?></td>
                            </tr>

                            <tr>
                                <td>Manufacturer</td>
                                <td><?php echo $purcharsedata->manufacturer; ?></td>
                            </tr>

                            <tr>
                                <td>Tax</td>
                                <td><?php echo $purcharsedata->tax; ?></td>
                            </tr>

                            <tr>
                                <td>Shipping cost</td>
                                <td><?php echo $purcharsedata->shippingcost; ?></td>
                            </tr>

                            <tr>
                                <td>Others cost</td>
                                <td><?php echo $purcharsedata->othercost; ?></td>
                            </tr>

                            <tr>
                                <td>Purchase Date</td>
                                <td><?php echo $purcharsedata->date; ?></td>
                            </tr>
                            <tr>
                                <td>Total price</td>
                                <td><strong><?php echo $purcharsedata->total_buyprice+$purcharsedata->shippingcost+$purcharsedata->othercost+$purcharsedata->tax; ?></strong></td>
                            </tr>
                            <tr>
                                <td style="text-align: right"><a href="<?php echo site_url('purchase') ?>" class="btn btn-primary">Complete Purchase</a></td>
                                <td><button type="button" class="btn btn-primary">Print Purchase</button></td>
                            </tr>

                        </table>
                    </div>
                </div>

            </div>          
        </div>


        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
