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
            <form id="sform"  method="post" action="<?php echo site_url('exam'); ?>"  id="temsellform" enctype="multipart/form-data">
                <div class="row">
                <div class="col-md-6 col-md-push-3">
                    <div class="input-group">
                        <label for="exam_id" class="col-md-1">পরীক্ষা</label>
                    <select class="selectpicker form-control" id="exam_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="exam_id">
                        <option></option>
                        <?php foreach ($exams as $exam):?>
                        <option <?php echo ($exam->id==$exam_id)?"selected":"";?> value="<?php echo $exam->id?>"><?php echo $exam->name?></option>

                        <?php endforeach;?>
                    </select>
                    <label id="exam_idHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('exam_id'); ?></label>
                    </div>
                    <div class="input-group">
                    <label class="col-md-1" for="class_id">জামাত</label>
                    <select class="selectpicker form-control" id="class_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="class_id" onchange="getsubject(this.value)">
                        <option></option>
                        <?php foreach ($classes as $class):?>
                        <option <?php echo ($class->id==$class_id)?"selected":"";?> value="<?php echo $class->id?>"><?php echo $class->class_name?></option>

                        <?php endforeach;?>
                    </select>
                    <label id="class_idHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('class_id'); ?></label>
                    </div>
                    
                    <div class="input-group">
                    <label class="col-md-1" for="subject_id"> বিষয় </label>
                    <select class="selectpicker form-control" id="subject_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="subject_id" ">
                        <option></option>
                        <?php foreach ( $subjects as $subject):?>
                        <option <?php echo ($subject->id==$subject_id)?"selected":"";?> value="<?php echo $subject->id?>"><?php echo $subject->name?></option>

                        <?php endforeach;?>
                    </select>
                    <label id="subject_idHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('subject_id'); ?></label>
                    </div>
                    <div class="input-group">
                    <span class="input-group-btn">
                        <button style="margin-left: 20px ;" class="btn btn-success" value="submit" id="sbutton" name="submit" type="submit">জমা দিন</button>
                    </span>
                    <?php if($class_id!=""):?>
                        <span class="input-group-btn">
                            <a style="margin-left: 20px ;" class="btn btn-success" href="<?php echo base_url('exam/exam_report/');?><?=$class_id;?>/<?=$exam_id;?>">পরীক্ষা রিপোর্ট </a>
                        </span>
                    <?php endif;?>
                    </div>
                </div>
                
                </div>  
            </form>

        <?php if(isset($studentlist)):?>
            
        <div style="margin-top: 30px;" >
            <form  method="post" action="<?php echo site_url('exam/saveamarks'); ?>"  id="tform">
                <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Roll</th>
                          <th scope="col">Marks</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($studentlist as $student):?>
                            <tr>
                              <th scope="row"><b><?php echo $student->roll;?></b></th>
                              <td scope="row"> 
                                <?=$student->call_name;?>
                                 <input type="hidden" name="student_id[]" value="<?php echo $student->id;?>"> 
                                 <input type="hidden" name="mark_id[]" value="<?php echo $student->mark_id;?>"> 
                              </td>
                              <td><input type="number" value="<?=$student->mark==0?"":$student->mark?>" name="marks[]"></td>
                            </tr>
                        <?php endforeach;?>
                        <input type="hidden" value="<?php echo $student->roll?>" id="lastroll"
                        >
                    </tbody>
                </table>
                <div style="text-align: center;" id="jomadin" class="row">
                    <input type="hidden" name="class_id" value="<?php echo $class_id;?>">
                    <input type="hidden" name="subject_id" value="<?php echo $subject_id;?>">
                    <input type="hidden" name="exam_id" value="<?php echo $exam_id;?>">
                    <button style="margin-bottom: 50px;" type="submit" name="submit" value="submit" id="tbutton" class="btn btn-primary">জমা দিন</button> 
                </div>
            </form>
        </div>
        <?php endif;?>
	</section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script type="text/javascript">
    var roll=1;
    var lastroll = $("#lastroll").val();
   function setattendance(att){
    
    var path = "<?php echo $baseurl; ?>" + "assets/image/students/";
        if(att=="yes"){
            $("#at-"+roll).text('Attend').addClass("btn-success")
        .removeClass("btn-danger");
         $("#"+roll).val('1');
        }
        else{
            $("#at-"+roll).text('Absence').addClass("btn-danger")
        .removeClass("btn-success");
         $("#"+roll).val('0');
        }
        roll=roll+1;
        var imagename = $("#image"+roll).val();
        var name2 = $("#name"+roll).val();
        if(roll<=lastroll){
        $("#counter").text('রোলঃ '+roll);
        console.log(name2);
        $("#studebtname").text('নামঃ '+name2);
        $("#studentimg").attr("src", path + imagename);
        }
        else{
            $("#counter").text('End');
        }
   }

   function changestatus(sroll){
    var path = "<?php echo $baseurl; ?>" + "assets/image/students/";
        if($("#at-"+sroll).text()=='Attend'){

            $("#at-"+sroll).text('Absence').addClass("btn-danger")
            .removeClass("btn-success");
            $("#"+sroll).val('0');
        }
        else
        {
            $("#at-"+sroll).text('Attend').addClass("btn-success")
            .removeClass("btn-danger");
            $("#"+sroll).val('1');
        }
         roll = sroll+1;
         var imagename = $("#image"+roll).val();
         var name = $("#name"+roll).val();
         $("#studebtname").text('নামঃ '+name);
         $("#studentimg").attr("src", path + imagename);
         $("#counter").text('রোলঃ '+roll);
   }

   function getsubject(id){
        var postdata = 'id=' + id;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("exam/getsubject"); ?>',
            data: postdata,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#subject_id").find('option').remove().end();
                $("#subject_id").append($('<option>', {
                        value: '' ,
                        text: 'Select Subject'
                }));
                if(Object.keys(jsonObject).length>0){
                    
                    $.each( jsonObject, function( r,v) {
                        
                        $("#subject_id").append($('<option>', {
                            value: v.id,
                            text: v.name
                        }));
                    });
                    $('.selectpicker').selectpicker('refresh');
                }
                
            }
        });
      }


</script>

