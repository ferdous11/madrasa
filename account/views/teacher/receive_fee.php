<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        বেতন আদায়
                    </header>
                    <div class="panel-body">
                        <?php if ($this->session->userdata('success')): ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>আলহামদুলিল্লাহ,&nbsp;</strong> <?php
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

                        <div class="row">
                            <div class="col-md-12">
                            <a style="float: left;" class="btn btn-danger" href="<?=$baseurl?>teacher/students_list/<?=$student->class_id;?>/#tr-<?=$student->roll;?>"><i class="fa fa-arrow-left"></i> পূর্ববর্তী পেজে ফিরে যান </a>
                                <span style="float: right"><a  class="btn btn-success" href="<?php echo $baseurl;?>teacher/add_student/"><i class="fa fa-plus"></i>&nbsp;নতুন তালেবে এলেম </a></span>
                                 <span style="float: right; margin-right: 20px;"><a href="#" data-toggle="modal" data-target="#addsupplier"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;ফি ধার্য করুন</button></a></span>
                                <span style="float: right; margin-right: 20px;"><a href="#" data-toggle="modal" data-target="#salesproduct"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;বিক্রয় করুন</button></a></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <img src="<?php echo $baseurl.'assets/image/students/'.$student->image;?>" alt="">
                            </div>
                            <div class="col-sm-6 col-md-3 ">
                                <h4>রোলঃ <?php echo $student->roll;?></h4>
                                <h4>নামঃ <?php echo $student->name;?></h4>
                                <h4>বাবার নামঃ <?php echo $student->father_name;?></h4>
                                <h4>মোবাইলঃ <?php echo $student->guardian_mobile;?></h4>
                                <h4>ঠিকানাঃ <?php echo $student->student_address;?></h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form-horizontal" role="form" id="sform" method="post" enctype="multipart/form-data">
                                    <table class="display table table-bordered table-striped dataTable" id="temptable">
                                        <tr>
                                            <th>ক্র.নং.</th>
                                            <th>নির্ধারণের তারিখ</th>
                                            <th>বিবরন</th>
                                            <th>ধার্য ফি</th>
                                            <th>বাকি</th>
                                            <th>জমা</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php $i=1;$total=0; if(!empty($assignfee)): foreach ($assignfee as $key): ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $key->assign_date;?></td>
                                            <td><?php echo $key->casuse;?></td>
                                            <td  style="text-align: right;"><?php echo $key->amount;?></td>
                                            <td  style="text-align: right;"><?php echo ($key->amount - $key->paid);$total+=($key->amount - $key->paid);?></td>
                                            <td style="text-align: right;">
                                                <input style="text-align: right;background-color: greenyellow; font-family: AdorshoLipi;font-size: 30px;" name="paidamount[]" step="1" id="up_<?php echo ($i);?>" onchange="tableData(<?php echo ($i++); ?>)"  type="number" max="<?php echo $key->amount-$key->paid;?>" min="0" value="0">
                                                <input type="hidden" name="student_fee_id[]" value="<?php echo $key->id;?>">
                                                <input type="hidden" name="assign_fee[]" value="<?php echo $key->amount;?>">
                                                <input type="hidden" name="casuse[]" value="<?php echo $key->casuse;?>">
                                                <input type="hidden" name="ppaidamount[]" value="<?php echo $key->paid;?>">
                                            </td>
                                            <td>
                                                <?php if($this->session->userdata('role')=='admin'):?>
                                                    <a class="btn btn-warning" href="<?php echo site_url('teacher/waiver_sfee/'.urlencode(base64_encode($key->id))); ?>" onclick="return confirm('আপনি কি <?=$student->name?> এর <?=$key->casuse;?> <?=$key->amount;?> টাকা মওকুফ করতে চাচ্ছেন !!')"> মওকুফ </a>
                                                    
                                                    <a class="btn btn-danger" href="<?php echo site_url('teacher/delete_sfee/'.urlencode(base64_encode($key->id))); ?>" onclick="return confirm('আপনি কি <?=$student->name?> এর <?=$key->casuse;?> <?=$key->amount;?> টাকা মুছে ফেলতে চাচ্ছেন !!')"><i class="fa fa-trash-o"> মুছেফেলুন </i></a>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <?php endforeach;endif;?>
                                        <tr>
                                            <td colspan="4" style="text-align: right;">মোটঃ</td>
                                            <td style="text-align: right;">
                                                <label style="text-align: right;color: orange;font-family: AdorshoLipi;font-size: 30px;padding-right: 20px;" >=<?=$total;?></label>
                                            </td>
                                            <td style="text-align: right;">
                                                <label id="stotal" style="text-align: right;background-color: orange; font-family: AdorshoLipi;font-size: 30px;padding-right: 20px;" >=0</label>
                                                <input  type="hidden" name="totalamount" id="totalmount" value="0">
                                            </td>
                                        </tr>
                                    </table>                                  
                                    
                                    <div class="row col-sm-6 col-sm-push-5" style="margin-top: 20px;">
                                        <a class="btn btn-danger" href="<?=$baseurl?>teacher/students_list/<?=$student->class_id;?>/#tr-<?=$student->roll;?>"><i class="fa fa-times"></i> বাতিল করুন </a>  

                                        <button id="sbutton" type="submit" value="submit" name="submit" onclick="return confirm('আপনি কি <?=$student->name?> এর অ্যাকাউন্টে টাকা জমা করতে চাচ্ছেন !!')" class="btn btn-success">ফি জমা করুন</button> 
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addsupplier" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">ফী ধার্য ফর্ম</h4>
                                </div>

                                <div class="modal-body">

                                    <form class="form-horizontal" role="form"action="<?php echo site_url('teacher/assign_fees'); ?>" id="tform" method="post" enctype="multipart/form-data">

                                        <div class="form-group">
                                            <label for="year" class="col-lg-4 col-sm-4 control-label">সাল</label>
                                            <div class="col-lg-7">
                                                <input type="number" step="1" class="form-control" name="year" min="2023" max="2100" id="year" required="" value="<?php echo date('Y');?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-lg-4 col-sm-4 control-label">মাস</label>
                                            <div class="col-lg-7">
                                            <select class="form-control"  name="month"  required >
                                                <?php
                                                $curmonth = date("F", strtotime('-1 month'));
                                                for($i = 1 ; $i <= 12; $i++)
                                                {
                                                
                                                    $allmonth = date("F",mktime(0,0,0,$i,1,date("Y")))
                                                    ?>
                                                    <option value="<?php $num_padded = sprintf("%02d", $i);
                                                    echo $num_padded;?>" <?php
                                                    if($curmonth==$allmonth)
                                                    {
                                                        echo 'selected';
                                                    }
                                                    ?> 
                                                    >
                                                    <?php
                                                    echo date("F",mktime(0,0,0,$i,1,date("Y")));
                                                    //Close tag inside loop
                                                    ?>
                                                    </option>
                                                    <?php
                                                } ?>
                                            </select>

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="cause" class="col-lg-4 col-sm-4 control-label"> বিবরণ </label>
                                            <div class="col-lg-7">
                                            <select class="form-control" id="cause"  name="cause"  required >
                                                
                                                <option value="হাদিয়া "> হাদিয়া </option>
                                                <option value="পরীক্ষার ফি "> পরীক্ষার ফি </option>
                                                <option value="ভর্তি ফি "> ভর্তি ফি </option>
                                                <option value="জরিমানা "> জরিমানা </option>

                                                    
                                            </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="amount" class="col-lg-4 col-sm-4 control-label"> টাকা </label>
                                            <div class="col-lg-7">
                                                <input type="number" step="5" max="1000" class="form-control" name="amount" value="200" min="10">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-8">
                                                <input type="hidden" name="student_id" value="<?=$student->id;?>"/>
                                                <input type="hidden" name="ledger_id" value="<?=$student->ledger_id;?>"/>
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                <input type="submit" class="btn btn-primary" id="tbutton" name="submit"  value="submit" onclick="return checkacgroup()"/>&nbsp;&nbsp;
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="salesproduct" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">বিক্রয় ফর্ম</h4>
                                </div>

                                <div class="modal-body">

                                    <form class="form-horizontal" role="form"action="<?php echo site_url('teacher/sales'); ?>" id="tform" method="post" enctype="multipart/form-data">

                                        <div class="form-group">
                                            <label for="cause" class="col-lg-4 col-sm-4 control-label"> পণ্য </label>
                                            <div class="col-lg-7">
                                            <select class="form-control" id="product_id"  name="product_id"  required onchange="getPrice(this.value)">
                                                <?php $products = $this->db->query("select p.*,u.name as unit_name from products as p left join product_unit as u on p.unit_id=u.id where p.class_id='$student->class_id' or p.class_id='1' order by p.id")->result(); echo"<option> ---- </option>"; foreach($products as $p):?>
                                                <option value="<?=$p->id;?>"> <?=$p->product_name;?> </option>
                                                <?php endforeach;?>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity" class="col-lg-4 col-sm-4 control-label"> পরিমান </label>
                                            <div class="col-sm-3">
                                                <input type="number" step="1" max="5" min="1" class="form-control" name="quantity" id="quantity" value="1">
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" id="unit" readonly class="form-control" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="amount" class="col-lg-4 col-sm-4 control-label"> দাম </label>
                                            <div class="col-lg-7">
                                                <input type="number" step="1" class="form-control" name="price" id="price" value="">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="amount" class="col-lg-4 col-sm-4 control-label"> নগত জমা</label>
                                            <div class="col-lg-7">
                                                <input type="number" step="1" max="1000" class="form-control" value="0" name="paid" id="paid">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-8">
                                                <input type="hidden" name="student_id" value="<?=$student->id;?>"/>
                                                <input type="hidden" name="ledger_id" value="<?=$student->ledger_id;?>"/>
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                <input type="submit" class="btn btn-primary" id="tbutton" name="submit"  value="submit" onclick="return checkacgroup()"/>&nbsp;&nbsp;
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        জমাক্রিত টাকার পরিমান ও বিবরন
                    </header>
                    <div class="panel-body">
                        <table class="display table table-bordered table-striped dataTable">
                            <tr>
                                <th>ক্র.নং.</th>
                                <th>নির্ধারণের তারিখ</th>
                                <th>জমা দানের তারিখ</th>
                                <th>বিবরন</th>
                                <th>ধার্য</th>
                                <th>জমা</th>
                            </tr>
                            <?php $i=1;$ptotal=$ppaid=0; if(!empty($assignfeepaid)): foreach ($assignfeepaid as $key): ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                <td><?php echo $key->assign_date;?></td>
                                <td><?php echo $key->paid_date;?></td>
                                <td><?php echo $key->casuse;?></td>
                                <td style="text-align: right;"><?php echo $key->amount;$ptotal+=$key->amount;?></td>
                                <td style="text-align: right;"><?php echo $key->paid;$ppaid+=$key->paid;?></td>
                            </tr>
                            <?php endforeach;endif;?>

                            <tr>
                                <td colspan="4" style="text-align: right;">মোটঃ</td>
                                
                                <td style="text-align: right;">
                                    <label style="text-align: right;" ><b>=<?=$ptotal;?></b></label>
                                </td>
                                <td style="text-align: right;">
                                    <label style="text-align: right;" ><b>=<?=$ppaid;?></b></label>
                                </td>
                            </tr>
                        </table> 
                    </div>  
                </section>                               
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
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
    function tableData(rownumber) {
        var x=1;var totalprice=0;
        for (x; x < document.getElementById("temptable").rows.length - 1; x++) {
            subtotal = parseInt($("#up_" + x).val());
            totalprice = totalprice + subtotal;
        }
        $("#totalmount").val(totalprice);    
        $("#stotal").text('='+totalprice.toLocaleString('en-IN'));    
    }

    function getPrice(product_id){
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'product_id=' + product_id + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("product/getpdetails") ?>',
            data: datastring,
            success: function(response) {
                var jsonObject = jQuery.parseJSON(response);
                var rej = Number(jsonObject.sale_price);
                $("#price").attr('max',rej);
                let temp = rej.toLocaleString('en-IN');
                $("#price").val(temp);
                $("#unit").val(jsonObject.available_quantity + ' ' + jsonObject.unit);
                $("#quantity").attr('max',jsonObject.available_quantity);
            }
        });
    }
</script>