<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<section id="main-content">
    <section class="wrapper">
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
        <div class="align-items-center">
            <form id="form1"  method="post" action="<?php echo site_url('teacher/students_list'); ?>"  id="temsellform" enctype="multipart/form-data">
              
                <div class="col-md-8 col-md-push-2">
                    <div class="input-group">
                    <select class="selectpicker form-control" id="class_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="class_id">
                                    <option></option>
                                    <?php foreach ($classes as $class):?>
                                    <option <?php echo ($class->id==$class_id)?"selected":"";?> value="<?php echo $class->id?>"><?php echo $class->class_name?></option>

                                    <?php endforeach;?>
                            </select>
                            <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('class_id'); ?></label>
                    <span class="input-group-btn">
                        <button style="margin-left: 20px ;" class="btn btn-default" value="submit" name="submit" type="submit">জমা দিন</button>
                    </span>
                    <span class="input-group-btn">
                        <a style="margin-left: 20px ;" class="btn btn-success" href="<?php echo $baseurl;?>teacher/add_student/"><i class="fa fa-plus"></i>&nbsp;নতুন তালেবে এলেম </a>
                    </span>
                    
                    </div>
                </div>
        
            </form>
        </div>

    <div class="row">
        <?php if(is_array($studentlist)&&!empty($studentlist)):if($this->session->userdata('role')=='admin'):?>
            <div class="row col-md-11"><span style="margin-left: 20px;"><a href="#" data-toggle="modal" class="btn btn-success" data-target="#addsupplier"><i class="fa fa-plus"></i>&nbsp;ফি ধার্য করুন</a></div>
            <?php endif;?>
            
            </span>
                <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($studentlist as $student):?>
                            <tr id="tr-<?php echo $student->id;?>">
                              <th>

                                <div class="form-group row">
                                    <label for="roll-<?php echo $student->id;?>" class="col-sm-3 col-form-label">Roll:</label>
                                    <div class="col-sm-9">
                                        <input style="color: red;border: 3px solid green;" type="text"  readonly class="form-control-plaintext" id="roll-<?php echo $student->id;?>" value="<?php echo $student->roll;?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="name-<?php echo $student->id;?>" class="col-sm-3 col-form-label">Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control-plaintext" id="name-<?php echo $student->id;?>" value="<?php echo $student->name;?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="call_name-<?php echo $student->id;?>" class="col-sm-3 col-form-label">Call Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control-plaintext" id="call_name-<?php echo $student->id;?>" value="<?php echo $student->call_name;?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="guardian_name-<?php echo $student->id;?>" class="col-sm-3 col-form-label">Guardian Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control-plaintext" id="guardian_name-<?php echo $student->id;?>" value="<?php echo $student->guardian_name;?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="mobile-<?php echo $student->id;?>" class="col-sm-3 col-form-label">Guardian Mobile:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control-plaintext" id="mobile-<?php echo $student->id;?>" value="<?php echo $student->guardian_mobile;?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="status-<?php echo $student->id;?>" class="col-sm-3 col-form-label">Due:</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control-plaintext" id="due-<?php echo $student->id;?>" value="<?php echo $student->due;?>">
                                    </div>
                                </div>
                              </th>
                             
                              <td class="col-md-1" scope="row">
                                <img src="<?php echo $baseurl.'assets/image/students/'.$student->image;?>" alt="">
                              </td>

                              <td class="col-md-1" scope="row">
                                <div class="form-group">
                                    <a href="tel:<?php echo $student->guardian_mobile;?>"><label class="btn btn-sm btn-success"><i class="fa fa-phone" aria-hidden="true"></i></label></a>

                                    <label id="lbl-<?php echo $student->id;?>"  onclick="updateinfo(<?php echo $student->id.','.$student->ledger_id;?>)" class="btn btn-sm btn-danger">Update  </label>
                                    <?php if($this->session->userdata('role')=='admin'):?>
                                    <a href="<?php echo $baseurl.'teacher/editstudent/'.$student->id;?>"><label class="btn btn-sm btn-success"><i class="fa fa-edit" aria-hidden="true"></i></label></a>
                                    <?php endif;?>
                                
                                </div>
                                <div class="form-group">
                                    <a href="<?php echo $baseurl.'teacher/receive_fee/'.$student->id;?>"><label class="btn btn-sm btn-success"><i class="fa fa-book" aria-hidden="true"></i> রশিদ বই </label></a>
                                </div>
                              </td>
                            </tr>
                        <?php endforeach;?>
                        <input type="hidden" value="<?php echo $student->roll?>" id="lastroll">
                        </tbody>
                </table>
      <?php endif;?>
    </div>

    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addsupplier" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">ফী ধার্য ফর্ম</h4>
                </div>

                <div class="modal-body">

                    <form class="form-horizontal" role="form"action="<?php echo site_url('teacher/allassign_fees'); ?>" id="tform" method="post" enctype="multipart/form-data">

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
                                <input type="number" step="5" max="1000" class="form-control" name="amount" min="10">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-lg-offset-4 col-lg-8">
                                <input type="hidden" name="class_id" value="<?=$class_id;?>"/>
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
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script type="text/javascript">
    var roll=1;
    var lastroll = $("#lastroll").val();


   function updateinfo(sid,ledgerid){
        var name = $("#name-"+sid).val();
        var call_name = $("#call_name-"+sid).val();
        var roll = $("#roll-"+sid).val();
        var guardian_name = $("#guardian_name-"+sid).val();
        var mobile = $("#mobile-"+sid).val();
        
        var postdata = 'id=' + sid +'&&name='+name+'&&call_name='+call_name+'&&roll='+roll+'&&guardian_name='+guardian_name+'&&guardian_mobile='+mobile+'&&ledger_id='+ledgerid;
        console.log(postdata);

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("teacher/updatestudent"); ?>',
            data: postdata,
            success: function (response) {
              $("#lbl-"+sid).hide();   
            }
        });
            
   }


</script>

