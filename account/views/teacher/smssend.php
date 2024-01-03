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
            <form id="sform"  method="post" action="<?php echo site_url('teacher/smssend'); ?>"  id="temsellform" enctype="multipart/form-data">
                <div class="row">
                <div class="col-md-6 col-md-push-3">
                    <div class="input-group">
                        <textarea style="text-align: left;" name="smsbody" id="smsbody" cols="30" rows="10"></textarea>
                        <span id="countchr"></span>
                        <select class="selectpicker form-control" id="class_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="class_id">
                                <option></option>
                                <?php foreach ($classes as $class):?>
                                <option <?php echo ($class->id==$class_id)?"selected":"";?> value="<?php echo $class->id?>"><?php echo $class->class_name?></option>

                                <?php endforeach;?>
                        </select>
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('class_id'); ?></label>
                        <span class="input-group-btn">
                            <button style="margin-left: 20px ;" class="btn btn-default" value="submit" id="sbutton" name="submit" type="submit">sms send</button>
                        </span>
                    </div>
                </div>
                </div>  
            </form>
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

  
</script>

