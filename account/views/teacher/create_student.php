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
        <div style="text-align: center;" class="row col-sm-12">
            <h1 class="col-md-5 col-sm-11 col-md-push-3">Add Student</h1>
        </div>
        <div class="row">
            <form id="sform" method="post" action="<?php echo site_url('teacher/add_student'); ?>"  enctype="multipart/form-data">
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group">
                        <label for="class_id">শ্রেণী</label>
                        <select require class="form-control selectpicker" id="class_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="class_id" onchange="getmaxroll(this.value)">
                            <option></option>
                            <?php foreach ($classes as $class):?>
                            <option <?php echo ($class->id==$class_id)?"selected":"";?> value="<?php echo $class->id?>"><?php echo $class->class_name?></option>
                            <?php endforeach;?>
                        </select>
                       
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_name">নাম</label>
                        <input require class="form-control" type="text" value="<?php echo $student_name; ?>" tabindex="3" name="student_name" id="student_name">
                        <label id="student_nameHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_name'); ?></label>
                        
                    </div> 
                </div> 
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="call_name">ডাক নাম</label>
                        <input require class="form-control" type="text" value="<?php echo $student_name; ?>" tabindex="4" name="call_name" id="call_name">
                        <label id="call_nameHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('call_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="father_name">বাবার নাম</label>
                        <input require class="form-control" type="text" value="<?php echo $student_name; ?>" tabindex="5" name="father_name" id="father_name">
                        <label id="father_nameHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('father_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="mother_name">মায়ের নাম</label>
                        <input class="form-control" type="text" value="<?php echo $student_name; ?>" tabindex="6" name="mother_name" id="mother_name">
                        <label id="mother_nameHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('mother_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label require for="guardian_name">অভিভাবকের নাম</label>
                        <input class="form-control" type="text" value="<?php echo $student_name; ?>" tabindex="7" name="guardian_name" id="guardian_name">
                        <label id="guardian_nameHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('guardian_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="guardian_mobile">অভিভাবকের মোবাইল নং</label>
                        <input tabindex="8" class="form-control" type="text" value="<?php echo $student_mobile; ?>" name="guardian_mobile" id="guardian_mobile">
                        <label  id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('guardian_mobile'); ?></label>
                    </div>    
                </div> 
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_roll">রোল</label>
                        <input aria-describedby="rolllHelp" class="form-control" readonly tabindex="9" value="<?php if($student_roll!=null) echo ($student_roll+1); ?>" type="number" step="1" name="student_roll" id="student_roll">
                        <label id="student_rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_roll'); ?></label>
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_fee">মাসিক বেতন</label>
                        <input aria-describedby="feeHelp" class="form-control" tabindex="10" value="0" type="number" step="1" name="student_fee" id="student_fee">
                        <label id="student_feeHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_fee'); ?></label>
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_mobile">ঠিকানা</label>
                        <textarea require tabindex="11" class="form-control" name="student_address" id="student_address" cols="30" rows="10"></textarea>
                        <label  id="student_addressHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_address'); ?>
                        </label>
                    </div>    
                </div> 
                <div class="col-sm-10 col-md-6 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_image">ছবি</label>
                        <input aria-describedby="student_imageHelp" class="form-control" tabindex="12"  type="file" name="student_image" id="student_image">
                        [Recommended max H: 600px & W: 600px S:1MB]
                        <label id="student_imageHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_image'); ?></label>
                    </div> 
                </div>
                <div class="row col-sm-11 col-md-8 col-md-push-4"> 
                <div class="col-md-2">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                
                <input style="height: 50px;font-size: 20px;" tabindex="4" name="submit" type="submit" class="form-control btn btn-sm btn-primary" value="জমা দিন" id="sbutton"/>   
                 
                    <!-- <button tabindex="13" value="submit" name="submit" type="submit" id="sbutton" class="btn btn-primary">জমা দিন</button> -->
                </div>
                </div>    
            </form>
        </div>
        <div class="row col-md-5 col-md-push-2" style="margin-top: 30px;">
            <?php echo anchor("teacher/index/",'Attendance') ;?>
        </div>
    </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script type="text/javascript">
    function getmaxroll(id){
        var postdata = 'id=' + id + '&& <?php echo $this->security->get_csrf_token_name(); ?>=' + '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("teacher/getmaxroll");?>',
            data: postdata,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#student_roll").val(jsonObject);
            }
        });
    }
</script>
