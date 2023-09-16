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
                        Account Ledger<a href="<?php echo site_url('master/export_acledger/'); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a>
                        <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
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
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addsupplier"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;New Account Ledger</button></a></span>
                            <table style="width: 100%" class="display table table-bordered table-striped dataTable" id="suppliertableid">

                                <thead>
                                    <tr>                                                              
                                        <th>ID</th> 
                                        <th>Account Group</th>                                      
                                        <th>Ledger Name</th>
                                        <th>Father Name</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>District</th>
                                        <th>Opening Balance</th>   
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <colgroup>
                                    <col span="1" style="width: 2%;">
                                    <col span="1" style="width: 6%;">
                                    <col span="1" style="width: 17%;">
                                    <col span="1" style="width: 13%;">
                                    <col span="1" style="width: 8%;">
                                    <col span="1" style="width: 26%;">
                                    <col span="1" style="width: 7%;">
                                    <col span="1" style="width: 7%;">
                                    <col span="1" style="width: 4%;">
                                    <col span="1" style="width: 10%;">
                                </colgroup>
                                
                                <tbody id="allledger">
                                    <?php
                                    $i = 1;
                                    $payble = 0;
                                    $netbalance = 0;
                                    if (sizeof($acledger) > 0):
                                        foreach ($acledger as $supplier):
                                            ?>
                                            <tr> 
                                                <td><?php echo ($supplier->id); ?></td>  
                                                <td><?php echo ($supplier->groupname); ?></td>  
                                                <td><?php echo $supplier->ledgername; ?></td>         
                                                <td><?php echo $supplier->father_name; ?></td>         
                                                <td><?php echo $supplier->mobile; ?></td> 
                                                <td><?php echo $supplier->address; ?></td>
                                                <td><?php echo $supplier->district_name; ?></td>
                                                <td ><b><?php echo ($supplier->openingbalance); ?></b></td>  
                                                
                                                <td><?php if($supplier->status==0) echo "<label class=' col-lg-11 label label-danger' >Inactive</label>"; else if($supplier->status==1) echo "<label class='col-lg-11 label label-success' >Active</label>"; ?></td>

                                                <td><?php if($this->session->userdata('role')=='admin'):?>
                                                    <a style="margin-right: 2px;" class="col-lg-4 label label-warning" href="<?php echo site_url('master/showledger/'. $supplier->id); ?>">Edit</a>&nbsp;
                                                    <?php if($supplier->status==0) echo "<a  onclick='changeStatus(".$supplier->id.")' class='col-lg-7 label label-success'  href=''>Active</a>"; else if($supplier->status==1) echo "<a onclick='changeStatus(".$supplier->id.")' class='col-lg-7 label label-danger'  href=''>Inactive</a>"; ?>
                                                    <!-- <a href="<?php echo site_url('master/deleteledger/' . $supplier->id); ?>" onclick="return confirm('Are you sure want to delete this ledger !!')"><i class="fa fa-trash-o"></i></a> -->
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
                </section>
            </div>          
        </div>

        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addsupplier" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Ledger</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/addledger'); ?>" method="post" enctype="multipart/form-data">

                            <div id="ledger_name" class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Ledger Name</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="ledger_name" maxlength="50" required="">
                                </div>
                            </div>
                            <div hidden id="proprietor_name" class="form-group">
                                <label for="proprietor_name" class="col-lg-4 col-sm-4 control-label">Proprietor Name</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="proprietor_name" maxlength="25" >
                                </div>
                            </div>
                            <div hidden id="father_name" class="form-group">
                                <label for="father_name" class="col-lg-4 col-sm-4 control-label">Father's Name</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="father_name" maxlength="25">
                                </div>
                            </div>

                            <div   class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Account Group</label>
                                <div class="col-lg-7">
                                    <select class="form-control"  name="accountgroup"  required="" onchange="checkacgroup()" id="accountgroup">
                                        <?php
                                        $acgroup = $this->db->get('accountgroup')->result();
                                        if (sizeof($acgroup) > 0):
                                            foreach ($acgroup as $acg):
                                                ?>
                                                <option value="<?php echo $acg->id; ?>"><?php echo $acg->name ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span style="color: red" id="error_acgroup"></span>
                                </div>
                            </div> 
                            <!-- nimnata iron and steel  start-->

                            <div hidden id='districtlist' class="form-group">
                                <label for="district" class="col-lg-4 col-sm-4 control-label">District</label>
                                <div class="col-lg-7">
                                    <select  data-live-search="true" class="form-control selectpicker"  name="district" id="district" required="">
                                        <?php
                                        $this->db->order_by("name", "asc");
                                        $districts = $this->db->get('districts')->result();
                                        if (sizeof($districts) > 0):
                                            foreach ($districts as $district):
                                                ?>
                                                <option <?php echo $district->id==59?"selected":"";?> value="<?php echo $district->id; ?>"><?php echo $district->name ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span style="color: red" id="error_acgroup"></span>
                                </div>
                            </div>

                            <!-- nimnata iron and steel  end-->
                            <div class="form-group">
                                    <label for="opbalance" class="col-lg-4 col-sm-4 control-label">Opening Balance</label>
                                    <div class="col-lg-4">
                                        <input type="text"  class="form-control" name="opbalance" id="opbalance" value="0">
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control" name="baltype">
                                            <option value="credit">Credit</option>
                                            <option value="debit" selected="">Debit</option>
                                        </select>
                                    </div>
                            </div>

                            <div hidden id="mobile" class="form-group">
                                <label for="mobile" class="col-lg-4 col-sm-4 control-label">Mobile</label>
                                <div class="col-lg-7">
                                    <input type="text" maxlength="25" class="form-control"  name="mobile" >
                                </div>
                            </div>

                            <div hidden  id="ledgerid" class="form-group">
                                <label for="ledgerid" class="col-lg-4 col-sm-4 control-label">Ledger Id</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="ledgerid" maxlength="50">
                                </div>
                            </div> 

                            <div hidden id="address" class="form-group">
                                <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                <div class="col-lg-7">
                                    <textarea class="form-control" name="address"  maxlength="50"></textarea>
                                </div>
                            </div> 

                            <div class="form-group">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <input type="hidden" name="fromname" value="ledger"/>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input type="submit" class="btn btn-primary" id="fsubmit" value="Submit" onclick="return checkacgroup()"/>&nbsp;&nbsp;
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
                [100, 200, 500,  -1],
                [100, 200, 500, "All"]
            ],
            iDisplayLength: 100
        });
        $("#foradvanced").hide();
    });
</script>
<?php include 'footer.php'; ?>
<script>
    function ledgertypecheck(typee) {
        if (typee == 'regular') {
            $("#foradvanced").hide();
        }
        if (typee == 'advanced') {
            $("#foradvanced").show();
        }

    }

    function Clickheretoprint()
    {
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
              
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("allledger");
        docprint.document.open();
        docprint.document.write('<html><title>Ledger</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:18px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
   
        docprint.document.write(comaddress);

        docprint.document.write('<hr>');

       
        docprint.document.write('<table border="1" class="display table table-bordered table-striped dataTable">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table></center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }

    function checkacgroup() {
        var acgid = $("#accountgroup").val();
        if(acgid==5||acgid==6 || acgid==8 || acgid==9){
            $( "#accounttype").show();
            $( "#districtlist").show();
            $( "#mobile").show();
            $( "#address").show();
            $("#father_name").show();
        }
        else {
            $("#accounttype").hide();
            $("#districtlist").hide();
            $("#mobile").hide();
            $("#address").hide();
            $("#father_name").hide();
            if(acgid==7)
                $("#ledger_name").hide();
            else
                $("#ledger_name").show();

        }
    }

    function checkacgroup_edit(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = $("#acgroupedit" + id).val() + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('master/checkacgroup'); ?>",
            data: 'accountgroupid=' + acgid,
            success: function (data) {
                if (data == 'yes') {
                    $("#finaleditsub" + id).prop('disabled', false);
                    $("#error_acgroup" + id).text("");
                    return true;
                } else {
                    $("#finaleditsub" + id).prop('disabled', true);
                    $("#error_acgroup" + id).text("This account group already used");
                    return false;
                }
            }
        });
    }
    function changeStatus(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Master/changeStatus'); ?>",
            data: 'ledger_id=' + acgid,
            success: function (data) {
            }
        });
    }
</script>