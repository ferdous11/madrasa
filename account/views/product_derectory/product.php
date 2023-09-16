<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
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
                    <?php if ($this->session->userdata('failed')): ?>
                        <div class="alert alert-block alert-danger fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                            <strong>Oops!</strong> <?php
                            echo $this->session->userdata('failed');
                            $this->session->unset_userdata('failed');
                            ?>
                        </div> 
                    <?php endif; ?>
                    <div class="panel-heading">Product List
                    <a href="<?php echo site_url('product/export_product/'); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a>

                        <button onclick="Clickheretoprint()" style="float: right;" class="btn btn-primary btn-md"><i class="fa fa-print"> Print </i>  </button>
                    </div>
                    <div class="panel-body">
                            <?php if($this->session->userdata('fcategory')=='true'|| $this->session->userdata('fsubcategory')=='true'): ?>
                            <form class="form-inline" role="form"action="<?php echo site_url('product'); ?>" method="post" enctype="multipart/form-data">
                                <?php if($this->session->userdata('fcategory')=='true'):?>
                                <div class="form-group">

                                    <label for="supplier">Class</label>
                                        <select name="class_id" id="class" class="form-control selectpicker" data-live-search="true" required>
                                            <?php
                                            if (sizeof($classes) > 0):$i=0;
                                                foreach ($classes as $class):
                                                    ?>
                                                    <option <?php  if($class_id==$class->id) echo ' selected '; ?>  value="<?php echo $class->id; ?>"><?php echo $class->class_name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                </div>
                                <?php endif;?>

                                <div class="form-group">
                                    <button style="margin-top: 16px;" type="submit" class="btn btn-primary">Submit</button>
                                   
                                </div>
                               
                            </form>
                            <?php endif;?>
                              <span style="float: right;"><a  href="<?php echo site_url('product/addproduct_form'); ?>"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Product</button></a></span>
                      
                        <table class="display table table-bordered table-striped" id="suppliertableid">

                            <?php
                            $companyId = $this->session->userdata('company_id');
                            
                            ?>
                            <thead>
                                <tr>   
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Class</th>          
                                    <th>Unit</th>
                                    <th>Purchase price</th>
                                    <th>Sales price</th>
                                    <th>Available Quantity</th>
                                    <th>Warning Quantity</th>
                                    <th>Inserted By</th>
                                    <th>Status</th>
                                    <th>Action&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody id="invoicediv">
                                <?php
                                $i = 1;
                                $totalbuy = 0;
                                $totalsell = 0;

                                if (sizeof($product) > 0):
                                    foreach ($product as $prodata):
                                        if ($prodata->image == ''):
                                            $pimage = 'default.png';
                                        else:
                                            $pimage = $prodata->image;
                                        endif;
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>    
                                            <td><?php echo $prodata->product_name; ?></td>
                                          
                                            <td><?php echo $prodata->class_name; ?></td>
                                            
                                            <td><?php echo $prodata->unit_name; ?></td>                                                  
                                            <td><?php echo number_format($prodata->purchase_price,2); ?></td>                                                  
                                            <td><?php echo number_format(($prodata->sale_price),2); ?></td>                                                  
                                                                                            
                                            <td><?php echo number_format(($prodata->available_quantity),2); ?></td>
                                            <td><?php echo ($prodata->warning_quantity); ?></td>
                                            <td><?php echo ($prodata->fullname); ?></td>                                                  
                                            <td><?php if($prodata->status==0) echo "<label class='label label-danger' >Inactive</label>"; else echo "<label class='label label-success' >Active</label>"; ?></td>                                                  
                                            <td> <?php if($this->session->userdata('role')=='admin'):?>
                                                <a style="margin-right: 2px;" class="col-lg-5 label label-warning"  href="<?php echo site_url('product/editproduct/'.$prodata->id); ?>">Edit</a>&nbsp;
                                                <?php if($prodata->status==0) echo "<a  onclick='changeStatus(".$prodata->id.")' class='col-lg-6 label label-success'  href=''>Active</a>"; else echo "<a onclick='changeStatus(".$prodata->id.")' class='col-lg-6 label label-danger'  href=''>Inactive</a>"; ?>
                                            <?php endif;?>
                                                
                                            </td>
                                        </tr>
                                        <?php
                                        $totalbuy = $totalbuy + $prodata->purchase_price;
                                        $totalsell = $totalsell + $prodata->sale_price;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                   
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<script type="text/javascript" charset="utf-8">

    String.prototype.allReplace = function(obj) {
    var retStr = this;
    for (var x in obj) {
        retStr = retStr.replace(new RegExp(x, 'g'), obj[x]);
    }
    return retStr;
    };

    $(document).ready(function () {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [500, 1000, 2500, 5000, -1],
                [500, 1000, 2500, 5000, "All"]
            ],
            iDisplayLength: 500,
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                //var nun = parseInt(aData[8].allReplace({'০': '0', '১': '1','২': '2', '৩': '3','৪': '4', '৫': '5','৬': '6', '৭': '7','৮': '8', '৯': '9'}));
                var nun = (aData[9]).replace(/,/g, "");;
                var nun = parseInt(nun);
                var warningq = aData[10].replace(/,/g, "");
                var warningq = parseInt(warningq);
                
                if ( nun <= 0 )
                {
                    $('td', nRow).css('background-color', '#ff8080');
                }
                else if ( nun <= warningq )
                {
                    $('td', nRow).css('background-color', '#ffff99');
                }

            }
        });
    });
</script>

<script>

    function changeStatus(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Product/changeStatus'); ?>",
            data: 'product_id=' + acgid,
            success: function (data) {
                 var jsonObject = jQuery.parseJSON(data);
                 console.log(jsonObject);
            }
        });
    }

    function Clickheretoprint()
    {
      
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Products</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:15px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');

        docprint.document.write(comaddress);

        docprint.document.write('<table border="1">');
       
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table></center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }

</script>
<?php include __DIR__ .'/../footer.php'; ?>