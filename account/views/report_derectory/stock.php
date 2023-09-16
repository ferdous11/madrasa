<?php include __DIR__ . '/../topheader.php'; ?>
<?php include __DIR__ . '/../menu.php'; ?>
<!-- // deff from jibon store -->
<!--sidebar end-->

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <?php if ($this->session->userdata('success')) : ?>
                        <div class="alert alert-block alert-success fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                            <strong>Congratulation!</strong> <?php
                                                                echo $this->session->userdata('success');
                                                                $this->session->unset_userdata('success');
                                                                ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->session->userdata('failed')) : ?>
                        <div class="alert alert-block alert-danger fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                            <strong>Oops!</strong> <?php
                                                    echo $this->session->userdata('failed');
                                                    $this->session->unset_userdata('failed');
                                                    ?>
                        </div>
                    <?php endif; ?>
                    <div class="panel-body">
                        <?php if ($this->session->userdata('fcategory') == 'true') : ?>
                            <form class="form-inline" role="form" action="<?php echo site_url('reports/rawstock'); ?>" method="post" enctype="multipart/form-data">
                                <?php if ($this->session->userdata('fcategory') == 'true') : ?>
                                    <div class="form-group">

                                        <label for="category_id">Category</label>
                                        <select tabindex="1" name="category_id" id="category_id" class="form-control selectpicker" data-live-search="true" onchange="getSubCatrgory(this.value)" required>
                                            <option value="">Select Category</option>
                                            <option <?php echo $category_id == -1 ? 'selected ' : ''; ?> value="-1">All</option>
                                            <?php

                                            if (sizeof($allcategory) > 0) : $i = 0;
                                                foreach ($allcategory as $cate) :
                                            ?>
                                                    <option <?php if ($category_id == $cate->id) echo ' selected '; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                <?php else : ?>
                                    <input type="hidden" name="category_id" value="1">
                                <?php endif; ?>
                                <?php if ($this->session->userdata('fsubcategory') == 'true') : ?>

                                    <div class="form-group">
                                        <label for="sub_category">Sub Category</label>
                                        <select tabindex="2" name="sub_category" id="sub_category" class="form-control selectpicker" data-live-search="true" onchange="getProduct(this.value)" required="">
                                            <option value="">Select Sub Category</option>
                                            <option <?php echo $sub_category == -1 ? 'selected ' : ''; ?> value="-1">All</option>
                                            <?php

                                            if (sizeof($subCategory) > 0) :
                                                foreach ($subCategory as $cate) :
                                            ?>
                                                    <option <?php echo $sub_category == $cate->id ? 'selected ' : ''; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                <?php else : ?>
                                    <input type="hidden" name="sub_category" value="1">
                                <?php endif; ?>

                                <!-- <div class="form-group">
                                    <input type="checkbox" name="qgt" id="qgt" value="qgt"> Quantity Greater Than<br>
                                    <input  type="number" class="form-control " name="qmax" id="qmax" value='<?php if (isset($qmax)) echo ($qmax);
                                                                                                                else echo ""; ?>'>   
                                </div>
                                <div class="form-group">
                                    
                                    <input type="checkbox" name="qlt" id="qlt" value="qlt" > Quantity Less Than<br>
                                    <input  type="number" class="form-control " name="qmin" id="qmin" value='<?php if (isset($qmin)) echo ($qmin);
                                                                                                                else echo ""; ?>'> 
                                </div>
 -->
                                <div class="form-group">
                                    <button style="margin-top: 16px;" type="submit" class="btn btn-primary">Submit</button>

                                </div>

                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row form" id="printarea">
            <div class="col-lg-12">

                <div class="panel">

                    <div class="panel-heading">Available Stock</div>
                    <div class="panel-body">
                        <a href="<?php echo site_url('reports/exports_data/' . $sub_category . '/' . $category_id); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary btn-md">Export </button></a>

                        <button onclick="printStock()" style="float: right;" class="btn btn-primary btn-md">Print </button>
                        <table class="display table table-bordered table-striped " id="suppliertableid">

                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <?php if ($this->session->userdata('fcategory') == 'true') : ?>
                                        <th>Category</th>
                                    <?php endif;
                                    if ($this->session->userdata('fsubcategory') == 'true') : ?>
                                        <th>Sub Category</th>
                                    <?php endif; ?>
                                    <th>Unit</th>
                                    <th>Available Quantity</th>
                                    <th>Unit Price</th>

                                    <th>Total Price</th>
                                </tr>
                            </thead>

                            <tbody id="productprint">
                                <?php
                                $i = 1;
                                $totalsell =$tqty= 0;

                                if (sizeof($product) > 0) :
                                    foreach ($product as $prodata) :
                                        if ($prodata->available_quantity == 0 && $prodata->empty_cylinder == 0)
                                            continue;
                                        $tqty+=$prodata->available_quantity;
                                ?>
                                        <tr>
                                            <td><?php echo $prodata->product_id; ?></td>
                                            <td style="text-align: left;"><a target="_blank" href="<?php echo site_url('reports/singlelist/' . $prodata->id); ?>"><?php echo $prodata->product_name; ?></a></td>
                                            <?php if ($this->session->userdata('fcategory') == 'true') : ?>
                                                <td><?php echo $prodata->category_name; ?></td>
                                            <?php endif;
                                            if ($this->session->userdata('fsubcategory') == 'true') : ?>
                                                <td><?php echo $prodata->sub_category; ?></td>
                                            <?php endif; ?>
                                            
                                            <td><?php echo $prodata->unit_name ; ?></td>
                                            <td style="text-align: right;">
                                                <?php echo ($prodata->category_id == 6) ? "Full=" . number_format($prodata->available_quantity, 2) . " Empty=" . number_format($prodata->empty_cylinder, 2) : number_format($prodata->available_quantity, 2); ?>
                                            </td>

                                            <td style="text-align: right;"><?php echo (number_format($prodata->purchase_price, 2)); ?></td>

                                            <td style="text-align: right;"><?php echo ($prodata->category_id == 6) ? (($prodata->available_quantity * $prodata->purchase_price) + ($prodata->empty_cylinder * $prodata->cylinder_p_p)) : ($prodata->available_quantity * $prodata->purchase_price); ?></td>

                                        </tr>
                                <?php
                                        if ($prodata->category_id == 6)
                                            $totalsell = $totalsell + ($prodata->available_quantity * $prodata->purchase_price) + ($prodata->empty_cylinder * $prodata->cylinder_p_p);
                                        else
                                            $totalsell = $totalsell + ($prodata->available_quantity * $prodata->purchase_price);
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    
                                    <td colspan="5" style="text-align:right"><b>Total:</b></td>
                                    <td style="text-align:right"><b><?php echo (number_format($tqty, 2)) ?></b></td>
                                    <td style="text-align:right"><b></b></td>
                                    <td style="text-align:right"><b><?php echo (number_format($totalsell, 2)) ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

            </div>

        </div>
        <!-- page end-->
    </section>
</section>

<?php include __DIR__ . '/../footer.php'; ?>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [-1, 250, 350, 500, 1000],
                ["All", 250, 350, 500, 1000]
            ],
            iDisplayLength: -1
        });

        $('#qmax').hide();
        $('#qmin').hide();
    });
</script>

<script>
    $('#qgt').click(function() {
        $("#qmax").toggle(this.checked);
    });
    $('#qlt').click(function() {
        $("#qmin").toggle(this.checked);
    });

    function getSubCatrgory(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('product/getSubCategory'); ?>",
            data: 'catagoryId=' + acgid,
            success: function(data) {

                var jsonObject = jQuery.parseJSON(data);

                $("#sub_category").find('option').remove().end();

                $("#sub_category").append($('<option>', {
                    value: '',
                    text: 'Select Sub Category'
                }));

                $("#sub_category").append($('<option>', {
                    value: '-1',
                    text: 'All'
                }));

                $.each(jsonObject, function(r, v) {

                    $("#sub_category").append($('<option>', {
                        value: v.id,
                        text: v.name
                    }));
                });
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }


    function getCategory(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('home/getCategory'); ?>",
            data: 'ledger_id=' + acgid,
            success: function(data) {

                var jsonObject = jQuery.parseJSON(data);

                $("#supplier").find('option').remove().end();

                $("#supplier").append($('<option>', {
                    value: '',
                    text: '----Select One----'
                }));

                $("#supplier").append($('<option>', {
                    value: '-1',
                    text: 'All'
                }));

                $.each(jsonObject, function(r, v) {

                    $("#supplier").append($('<option>', {
                        value: v.id,
                        text: v.ledgername
                    }));
                });
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
</script>

<script type="text/javascript">
    function printStock() {
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';  

        var totalsell = parseFloat("<?php echo $totalsell; ?>");
        totalsell = totalsell.toFixed(2);
        var tqty = parseFloat("<?php echo $tqty; ?>");
        tqty = tqty.toFixed(2);

        var dateTime = "<?php echo date('F j, Y, g:i a'); ?>";
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1145, height=780, left=25, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;

        oTable = document.getElementById("productprint");
        docprint.document.open();
        docprint.document.write('<html><title>Stock Products</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:14px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<p><span style="margin-top:50px; margin-left:-400px"><img height="50" weidth="150" src="<?php echo $baseurl . "assets/img/logo.png"; ?>"/></span></p>');
        docprint.document.write(comaddress);
        docprint.document.write('<h2 style="margin-left:80px">Stock history</h2>');
        docprint.document.write('<p style="margin:-10px 0 10px 82px"> ' + dateTime + '</p>');
        docprint.document.write('<table  border="1"><thead><tr><th>Id</th><th>Name</th><th>Category</th><th>Sub Category</th><th>Unit</th><th>Available Quantity</th><th>Unit Price</th><th>Total Price</th></tr></thead><tbody>');
        docprint.document.write(oTable.innerHTML);
        docprint.document.write('<tr><th colspan="5" style="text-align: right;">Total:</th><th style="text-align: right;">'+tqty+'</th><th></th><th style="text-align: right;">'+totalsell+'</th>'+'</tr></tbody></table></center>');
        docprint.document.write('</body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
</script>