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

                        <a href="<?php echo site_url('reports/exports_notification'); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a> 

                        <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>

                    </div>
                    <div class="panel-body">
                        <table class="display table table-bordered table-striped" id="suppliertableid">
                            <?php
                            $companyId = $this->session->userdata('company_id');
                            
                            ?>
                            <thead>
                                <tr>   
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Product Id</th>
                                    <?php if($this->session->userdata('fcategory')=='true'):?>
                                        <th>Category</th>
                                    <?php endif; if($this->session->userdata('fsubcategory')=='true'):?>
                                        <th>Sub Category</th> 
                                    <?php endif;?>                       

                                    <th>Available Quantity</th>
                                    <th>Warning Quantity</th>
                                    <th>Unit</th>
                                    <th class="hidetoprint">Status</th>
                                    <th class="hidetoprint">Action&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody id="allledger">
                                <?php
                                $i = 1;
                               

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
                                            <td><?php echo $prodata->product_id; ?></td>
                                            <?php if($this->session->userdata('fcategory')=='true'):?>
                                            <td><?php echo $prodata->category_name;?></td>
                                            <?php endif; if($this->session->userdata('fsubcategory')=='true'):?>
                                            <td><?php echo $prodata->sub_category;?></td>
                                            <?php endif;?>  
                                                                                          
                                            <td><?php echo ($prodata->category_id==6)? number_format(($prodata->available_quantity),2)."|".number_format(($prodata->empty_cylinder),2):number_format(($prodata->available_quantity),2); ?></td>
                                            <td><?php echo number_format(($prodata->warning_quantity),2); ?></td>
                                            <td><?php echo $prodata->unit_name; ?></td>
                                                                                              
                                            <td class="hidetoprint"><?php if($prodata->status==0) echo "<label class='label label-danger' >Inactive</label>"; else echo "<label class='label label-success' >Active</label>"; ?></td>                                                  
                                            <td class="hidetoprint"> <?php if($this->session->userdata('role')=='admin'):?>
                                                
                                                <?php if($prodata->status==0) echo "<a  onclick='changeStatus(".$prodata->id.")' class='col-lg-8 label label-success'  href=''>Active</a>"; else echo "<a onclick='changeStatus(".$prodata->id.")' class='col-lg-8 label label-danger'  href=''>Inactive</a>"; ?>
                                            <?php endif;?>
                                                
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
               
                var nun = (aData[5]).replace(/,/g, "");;
                var nun = parseInt(nun);
                var warningq = aData[6].replace(/,/g, "");
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

    function getSubCategory(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Product/getSubCategory'); ?>",
            data: 'catagoryId=' + acgid,
            success: function (data) {
                
                 var jsonObject = jQuery.parseJSON(data);
                 
                $("#sub_category").find('option').remove().end();
                

                $("#sub_category").append($('<option>', {
                        value: '-1' ,
                        text: 'All'
                }));

                $.each( jsonObject, function( r,v) {
                    
                    $("#sub_category").append($('<option>', {
                        value: v.id,
                        text: v.name
                    }));
                });
                 $('.selectpicker').selectpicker('refresh');
            }
        });
    }

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
         
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = mm + '/' + dd + '/' + yyyy;
             
               
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        $(".hidetoprint").hide();
        //console.log(selected_val);
        oTable = document.getElementById("allledger");
        docprint.document.open();
        docprint.document.write('<html><title>Notification</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:18px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        
        docprint.document.write(comaddress);
        docprint.document.write('<h3 style="margin-top:15px;text-align:center;"><u>Out of stock products</u></h3>');
        docprint.document.write('<h3 style="margin-top:-15px;text-align:center;">Date: ' + today + '</h3><hr style="width:700px; margin: -12px 0 -12px 0">');

        docprint.document.write('</p><p style="margin:-10px 0 10px 0px;text-align:center;"> </p>');
        docprint.document.write('<table border="1" class="display table table-bordered table-striped dataTable">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table></center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
        $(".hidetoprint").show();
    }

</script>
<?php include __DIR__ .'/../footer.php'; ?>