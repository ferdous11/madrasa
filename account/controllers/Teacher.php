<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {
	function __construct() {
        parent::__construct();
        $this->load->helper('form', 'file');
        $this->load->helper('url');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->load->library('image_lib');
        $this->load->library('form_validation');
		$this->load->library('encryption');
    }

	public function add_student(){
	  if($this->session->userdata('role')=='teacher'||$this->session->userdata('role')=='admin'):
		$data['activemenu'] = 'master';
        $data['activesubmenu'] = 'student';
        $data['page_title'] = 'Student List';
        $data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
		$data['classes'] = $this->db->query("select * from classes where company_id='$company_id'")->result();
		$data['class_id'] = $data['section_id'] = "";
		$data['student_name']=$data['student_roll']=$data['student_mobile']="";
		$data['sections'] = array();
		$randomkey = time();
		$today = date("Y-m-d H:i:s");
		

		if($this->input->post('submit')=='জমা দিন'){
			
			$data['class_id']=$this->input->post('class_id');
			$data['section_id']=$this->input->post('section_id');
			
			if($data['section_id']>0)
				$data['sections'] = $this->db->query("select * from sections where class_id='".$data['class_id']."' and company_id='$company_id'")->result();

				$this->form_validation->set_rules('student_name', 'Student Name', 'required');
				$this->form_validation->set_rules('student_roll', 'Student Roll', 'required|callback_checkroll',array('checkroll' => 'This Roll Already Exist'));
				// $this->form_validation->set_rules('guardian_mobile', 'Guardian Mobile No', 'required');

			if ($this->form_validation->run() == FALSE)
            {
				$data['student_name']=$this->input->post('student_name');
				$data['student_roll']=$this->input->post('student_roll');
				$data['guardian_mobile']=$this->input->post('guardian_mobile');
				$this->load->view('teacher/create_student',$data);
				return 0;
            }
	        else{
				if ($_FILES["student_image"]['name'] != ''):
                
					$ext = pathinfo($_FILES["student_image"]['name'], PATHINFO_EXTENSION);
					$config['upload_path'] = './assets/image/students';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size'] = '1000';
					// $config['max_width'] = '1024';
					// $config['max_height'] = '800';
					$new_name =time();
					$config['file_name'] = trim($new_name);
					$config['overwrite'] = TRUE;
					$this->load->library('upload', $config);
					
					if ($this->upload->do_upload('student_image')):
						$imagename = $new_name.'.'.$ext;
					else:
						$imagename = 'default.png';
					endif;
				else:
					$imagename = 'default.png';
				endif;

				$dataarray1 = array(
					'accountgroupid'=>7,
					'district'=>59,
					'company_id'=>$this->session->userdata('company_id'),
					'status'=>1,
					'ledgername'=>$this->input->post('student_name'),
					'father_name'=>$this->input->post('father_name'),
					'mobile'=>$this->input->post('guardian_mobile'),
					'image'=>$imagename,
					'address'=>$this->input->post('student_address'),
					'user_id'=>$this->session->userdata('user_id'),
					'date'=>$today,
					'company_id'=>$company_id
				);
				$this->db->insert('accountledger', $dataarray1);
				$ledger_id = $this->db->insert_id();

				$dataarray = array(
					'ledger_id'=>$ledger_id,
					'call_name'=>$this->input->post('call_name'),
					'mother_name'=>$this->input->post('mother_name'),
					'guardian_name'=>$this->input->post('guardian_name'),
					'roll'=>$this->input->post('student_roll'),
					'class_id'=>$this->input->post('class_id'),
					'fee'=>$this->input->post('student_fee'),
					'fee_assign'=>$today,
					'section_id'=>$this->input->post('section_id'),
				);
				
				$this->db->insert('students', $dataarray);
				$student_id = $this->db->insert_id();

				$insertdata=array();
				array_push($insertdata,array(
					'voucherid' => $randomkey, 
					'ledgerid' => $ledger_id,
					'date' => $today,
					'debit' => 50,
					'credit' => 0,
					'vouchertype' => 'Assign Fee',
					'description' => 'ভর্তি ফী',
					'company_id' => $this->session->userdata('company_id'),
					'user_id' => $this->session->userdata('user_id')
				));
				array_push($insertdata,array(
					'voucherid' => $randomkey,
					'ledgerid' => 7,
					'date' => $today,
					'debit' => 0,
					'credit' => 50,
					'vouchertype' => 'Assign Fee',
					'description' => 'ভর্তি ফী',
					'company_id' => $this->session->userdata('company_id'),
					'user_id' => $this->session->userdata('user_id')
				));
				$this->db->insert_batch('ledgerposting', $insertdata);	

				$datalist3 = array(
					'student_id' => $student_id,
					'amount' => 50,
					'casuse' => 'ভর্তি ফী',
					'assign_date' => $today,
					'company_id' => $this->session->userdata('company_id'),
					'user_id' => $this->session->userdata('user_id')
				);
				$this->db->insert('student_fee', $datalist3);

				$data['student_roll'] = $this->db->query("select max(s.roll) as roll from students as s left join accountledger as l on s.ledger_id=l.id where s.class_id='".$data['class_id']."' and l.company_id='$company_id'")->row()->roll;
				$this->session->set_userdata('success', 'নতুন তালেবে এলেম সফলভাবে সংযুক্ত হয়েছে ।');
	        }
		}
		$this->load->view('teacher/create_student',$data);
	  else :
		return show_error('You must be a teacher to view this page.');
	  endif;
	}

	public function editstudent($id=0){
		if($this->session->userdata('role')=='teacher'||$this->session->userdata('role')=='admin'):
			
		  $data['activemenu'] = 'master';
		  $data['activesubmenu'] = 'student';
		  $data['page_title'] = 'Student List';
		  $data['baseurl'] = $this->config->item('base_url');

		  $company_id = $this->session->userdata('company_id');
		  $data['classes'] = $this->db->query("select * from classes where company_id='$company_id'")->result();
		  $data['student'] = $this->db->query("select s.*,l.ledgername as name,l.address as student_address,l.father_name,l.mobile as guardian_mobile,l.image,l.status from students as s left join accountledger as l on s.ledger_id=l.id where s.id='$id' and l.company_id='$company_id'")->row();
		  $this->load->view('teacher/edit_student',$data);
		else :
		  return show_error('You must be a teacher to view this page.');
		endif;
	}

	public function saveeditstudent(){
		$data['activemenu'] = 'master';
		$data['activesubmenu'] = 'student';
		$data['page_title'] = 'Student List';
		$data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');		
	    if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
			

			if($this->input->post('submit')=='submit'):
				$data['class_id']=$this->input->post('class_id');
				$data['section_id']=$this->input->post('section_id');
				
				if($data['section_id']>0)
					$data['sections'] = $this->db->query("select * from sections where class_id='".$data['class_id']."' and company_id='$company_id'")->result();
					$this->form_validation->set_rules('student_name', 'Student Name', 'required');
					$this->form_validation->set_rules('guardian_mobile', 'Guardian Mobile No', 'required');
	
				if ($this->form_validation->run() == FALSE)
				{
					$this->load->view('teacher/create_student',$data);
					return 0;
				}
				else{
					if ($_FILES["student_image"]['name'] != ''):
					
						$ext = pathinfo($_FILES["student_image"]['name'], PATHINFO_EXTENSION);
						$config['upload_path'] = './assets/image/students';
						$config['allowed_types'] = 'gif|jpg|png';
						$config['max_size'] = '1000';
						// $config['max_width'] = '1024';
						// $config['max_height'] = '800';
						$new_name =time();
						$config['file_name'] = trim($new_name);
						$config['overwrite'] = TRUE;
						$this->load->library('upload', $config);
  
						if (file_exists('./assets/image/students/'.$this->input->post('pimage'))&&$this->input->post('pimage')!="default.png") {
						  unlink('./assets/image/students/'.$this->input->post('pimage'));
					  	}
						if ($this->upload->do_upload('student_image')):
							$imagename = $new_name.'.'.$ext;
						else:
							$imagename = 'default.png';
						endif;
					else:
						$imagename = $this->input->post("pimage");
					endif;

					$dataarray = array(
						'status'=>1,
						'ledgername'=>$this->input->post('student_name'),
						'father_name'=>$this->input->post('father_name'),
						'mobile'=>$this->input->post('guardian_mobile'),
						'image'=>$imagename,
						'address'=>$this->input->post('student_address'),
					);
					$this->db->where('id', $this->input->post('ledgerid'));
					$this->db->update('accountledger', $dataarray);
	
					$dataarray = array(
						'call_name'=>$this->input->post('call_name'),
						'mother_name'=>$this->input->post('mother_name'),
						'guardian_name'=>$this->input->post('guardian_name'),
						'class_id'=>$this->input->post('class_id'),
						'section_id'=>$this->input->post('section_id'),
						'fee'=>$this->input->post('student_fee'),
						'update_by'=>$this->session->userdata('user_id'),
						'update_date'=>date("Y-m-d H:i:s")
					);
					$this->db->where('id', $this->input->post('studentid'));
					$this->db->update('students', $dataarray);
					redirect('teacher/students_list/'.$data['class_id'].'/'.$data['section_id'].'/#tr-'.$this->input->post('studentid'));
				}
			endif;
	  else:
		$this->load->view('login', $data);
	  endif;
	}

	public function attendance(){
		$data['activemenu'] = 'master';
		$data['activesubmenu'] = 'student';
		$data['page_title'] = 'Student List';
		$data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
	    if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
			
			$data['classes'] = $this->db->query("select * from classes where company_id='$company_id'")->result();
			if($this->input->post('class_id')){
				$data["class_id"] = $this->input->post('class_id');
	  			$data["section_id"] = $this->input->post('section_id');
	  			$data["class_name"] = $this->db->query("select class_name from classes where id='".$data['class_id']."' and company_id='$company_id'")->row()->class_name;
	  			if(empty($data["section_id"])){
	  				$data["section_name"] = "";
					  $data['section_id'] = 0;
					  $data["studentlist"] = $this->db->query("select s.*,l.ledgername as name,l.address as student_address,l.father_name,l.mobile as guardian_mobile,l.image,l.status from students as s left join accountledger as l on s.ledger_id=l.id where s.class_id=".$data["class_id"]." and l.company_id='$company_id'")->result(); 
					  
				}
				else{
					$data['section_id'] = $this->input->post('section_id');
					$data["section_name"] = $this->db->query("select section_name from sections where id='".$data['section_id']."' and company_id='$company_id'")->row()->section_name;
					$data["studentlist"] = $this->db->query("select s.* from students as s left join accoutnledger as l on s.ledger_id=l.id where s.class_id='".$data["class_id"]."' and l.company_id='$company_id'")->result();

				}
	  			$this->load->view('teacher/attendance',$data);
			}
			else{
				$data['class_id'] = "";
				$data['section_id'] = "";
				$this->load->view('teacher/attendance',$data);
			}
	  else :
		$this->load->view('login', $data);

	  endif;
	}

	public function saveattendance(){
		if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):	
	  	$id=$this->input->post('roll[]');
		$attendance_id=$this->input->post('attendance_id[]');
		if(empty($attendance_id)){
			$attendance_id = array();
		}
		$att=$this->input->post('attendance[]');
		$class_name=$this->input->post('class_name');
		$company_id = $this->session->userdata('company_id');
		$insertdata = array();
		$maxid=count($id);
		if(count($attendance_id)>0){
			for($i=0;$i<$maxid;$i++){
				array_push($insertdata,array(
	                'id' => $attendance_id[$i],
	                'attendance' => $att[$i],
	                'update_date' => date("Y-m-d"),
	                'update_by' => $this->session->userdata('user_id')
	        	));
			}
			$this->db->update_batch('attendance', $insertdata, 'id');

			$this->session->set_userdata('success', 'তালেবে এলেম হাজিরা </strong>'.$class_name.'</strong> শ্রেণীর সফল ভাবে সংশোধন হয়েছে ।');
		}
		else{
			for($i=0;$i<$maxid;$i++){
				array_push($insertdata,array(
					'student_id' => $id[$i],
					'attendance' => $att[$i],
					'insert_date' => date("Y-m-d"),
					'insert_by' => $this->session->userdata('user_id'),
					'company_id'=>$company_id
				));
			}
			$this->db->insert_batch('attendance', $insertdata);
			$this->session->set_userdata('success', 'তালেবে এলেম হাজিরা <strong>'.$class_name.'</strong> শ্রেণীর সফল ভাবে সম্পন্ন হয়েছে ।');
		}
		return $this->attendance();     
	  else :
		return show_error('You must be a teacher to view this page.');
	  endif;
	}

	public function students_list($classid=2,$sectionid=0){
		$data['activemenu'] = 'report';
		$data['activesubmenu'] = 'studentlist';
		$data['page_title'] = 'Student List';
		$data['baseurl'] = $this->config->item('base_url');
		if($this->session->userdata('role')=='teacher'||$this->session->userdata('role')=='admin'):
		$company_id = $this->session->userdata('company_id');
		$data['classes'] = $this->db->query("select * from classes where company_id='$company_id'")->result();
		$data["class_id"]=$data["section_id"] = 0;
		$data["studentlist"] = array();
		if(!empty($this->input->post('class_id')||$classid>0)){
			if($classid>0){
				$data["class_id"] = $classid;
				$data["section_id"] = $sectionid;
			}
			else{
			$data["class_id"] = $this->input->post('class_id');
			$data["section_id"] = $this->input->post('section_id');
			}
  			$data['sections'] = $this->db->query("select * from sections where class_id='".$data['class_id']."' and company_id='$company_id'")->result();
  			if(empty($this->input->post('section_id'))||$this->input->post('section_id')=="")
  				$data["section_id"] = 0;
  			$data["studentlist"] = $this->db->query("select s.*,(select sum(amount)-sum(paid) from student_fee where student_id=s.id and step=0) as due,l.ledgername as name,l.address as student_address,l.father_name,l.mobile as guardian_mobile,l.image,l.status from students as s left join accountledger as l on s.ledger_id=l.id  where s.class_id=".$data["class_id"]." and l.company_id='$company_id'")->result(); 
		}
		$this->load->view('teacher/students_list',$data);

	  else :
		return show_error('You must be a teacher to view this page.');
	  endif;	
	}

	function attendance_report($class_id){
		$data['activemenu'] = 'report';
		$data['activesubmenu'] = 'studentlist';
		$data['page_title'] = 'Student List';
		$data['baseurl'] = $this->config->item('base_url');
		
		if($this->session->userdata('role')=='teacher'||$this->session->userdata('role')=='admin'):
			$edate = $data['edate'] = date('Y-m-d');
			$sdate = $data['sdate'] = date('Y-m-01');
			
			$data['students'] = $this->db->query("select s.id,s.roll,l.ledgername from students as s left join accountledger as l on s.ledger_id=l.id where s.class_id='$class_id'")->result();
			
			$data['dates'] = $this->db->query("select insert_date from attendance where student_id='".$data['students'][0]->id."' and insert_date between '$sdate' and '$edate'")->result();
			
			$this->load->view('teacher/attendance_report',$data);

		else :
			return show_error('You must be a teacher to view this page.');
		endif;	
	}

	//--------------------sales product---------------------

	function sales(){
			$data['baseurl'] = $this->config->item('base_url');
			if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
				$nettotal_price = 0;
		
				$company_id = $this->session->userdata('company_id');
				$user_id = $this->session->userdata('user_id');
				$customer_id = $this->input->post('ledger_id');
				$invoiceid = time();
				$price = str_replace( ',', '', $this->input->post('price'));
				$quantity = $this->input->post('quantity');
				$product_id = $this->input->post('product_id');
				$paid = str_replace( ',', '', $this->input->post('paid'));
				$insertdata=array();
				$today = date('Y-m-d H:i:s');
				
				$ledger = $this->db->query("select * from accountledger where id='$customer_id'")->row();
	
				$message = $ledger->ledgername."\n";
				
					$getpdetails = $this->db->query("select p.*,u.name as unitname from products as p left join product_unit as u on p.unit_id=u.id where p.id = '$product_id'")->row();
					$tquantity= $quantity;
					$this->db->query("update products set available_quantity = (available_quantity-".$quantity.") where id = '$product_id'");
					$purchase = $this->db->query("select id,a_quantity,buyprice from purchase where product_id='$product_id' and a_quantity>0 order by id asc")->result();
					$devcomment = array();
					$profit=0;
					 
					foreach ($purchase as $pu){
						if($pu->a_quantity>=$tquantity){
							$this->db->query("update purchase set a_quantity ='".($pu->a_quantity-$tquantity)."' where id='$pu->id'");
							$profit+=(($price-$pu->buyprice) * $tquantity);
							$devcomment[$pu->id] = $tquantity;
							$tquantity =0;
							break;
						}
						else {
							$this->db->query("update purchase set a_quantity =0 where id='$pu->id'");
							$profit+=(($price-$pu->buyprice) * $pu->a_quantity);
							$devcomment[$pu->id] = $pu->a_quantity;
							$tquantity -= $pu->a_quantity;
						}
					}
	
				
					if($getpdetails->warning_quantity<$getpdetails->available_quantity && $getpdetails->warning_quantity>=$getpdetails->available_quantity-$quantity)
					$this->session->set_userdata('notification',$this->session->userdata('notification')+1);
					
	
					$datalist = array(
						'invoice_id' => $invoiceid,
						'product_id' => $product_id,
						'customer_id'=> $customer_id,
						'sellprice' =>$price,
						'profit' => $profit,
						'quantity' => $quantity,
						'paid' => $paid,
						'date' => $today,
						'devcomment' => json_encode($devcomment),
						'company_id' => $company_id
					);
					
					$this->db->insert('daily_sell', $datalist);
					$INSERT_ID = $this->db->insert_id();
	
					$message = $message.$getpdetails->product_name." ".number_format($quantity).$getpdetails->unitname." ".($price*$quantity)."৳\n";
				
				$d_ledgerid = 2;
				array_push($insertdata,array(
					'voucherid' => $invoiceid,
					'ledgerid' => $d_ledgerid,
					'date' => $today,
					'vouchertype' => 'sales',
					'debit' => '0',
					'credit' => ($price*$quantity),
					'description' => "Inv-". sprintf("%06d", $INSERT_ID),
					'user_id'=>$user_id,
					'company_id' => $this->session->userdata('company_id')
				));
				
				array_push($insertdata,array(
					'voucherid' => $invoiceid,
					'ledgerid' => $customer_id,
					'date' => $today,
					'vouchertype' => 'sales',
					'debit' =>($price*$quantity),
					'credit' => '0',
					'user_id'=>$user_id,
					'description' => "Inv-". sprintf("%06d", $INSERT_ID),
					'company_id' => $this->session->userdata('company_id')
				));

				if($paid!=0){
					array_push($insertdata,array(
						'voucherid' => $invoiceid, 
                        'ledgerid' => $customer_id,
                        'date' => $today,
                        'debit' => 0,
                        'credit' => $paid,
                        'vouchertype' => 'Receive Voucher',
                        'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                        'company_id' => $this->session->userdata('company_id'),
						'user_id' => $this->session->userdata('user_id')
					));
					array_push($insertdata,array(
						'voucherid' => $invoiceid,
                        'ledgerid' => 1,
                        'date' =>$today,
                        'debit' => $paid,
                        'credit' => 0,
                        'vouchertype' => 'Receive Voucher',
                        'description' => "Inv-". sprintf("%06d", $INSERT_ID),
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
					));
				}
				$this->db->insert_batch('ledgerposting', $insertdata);

				$datalist3 = array(
					'voucherid' => $invoiceid,
					'student_id' => $this->input->post('student_id'),
					'amount' => ($price*$quantity),
					'paid' => $paid,
					'paid_date'=>$today,
					'casuse' => $getpdetails->product_name." ".number_format($quantity)." ".$getpdetails->unitname,
					'assign_date' => $today,
					'company_id' => $this->session->userdata('company_id'),
					'user_id' => $this->session->userdata('user_id')
				);
				$this->db->insert('student_fee', $datalist3);

				$message = $message." জমা ".($paid)."৳";
				//smsWhatsappAdmin($message);
				redirect('teacher/receive_fee/'.$this->input->post('student_id'));
				$this->session->set_userdata('success', 'Sales completed successfully.');
				
			else:
				redirect(base_url());
			endif;
	}

	// --------------------student fee----------------------
	function employee_salary(){
        $data['baseurl'] = $this->config->item('base_url');
        $comid = $this->session->userdata('company_id');
        $data['activemenu'] = 'Employee';
        $data['activesubmenu'] = 'employee_salary';
    
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $check_array=array();
            $data['salarylist']  = $this->db->query("select employee.*,accountledger.ledgername,accountledger.description from employee left join accountledger on employee.employee_id = accountledger.id")->result();
            foreach ($data['salarylist'] as $key) {
               $check_array[] = $key->employee_id;
            }

            $data['check_array'] = $check_array;
            $data['employee'] = $this->db->query("select accountledger.id,accountledger.ledgername from accountledger where accountgroupid=18 and status<>0")->result();
            $this->load->view('employee/employee_salary', $data);
            

        else:
            $this->load->view('login', $data);
        endif;
    } 

    function assign_fee(){
		$pmonth = date('M-Y', strtotime('-1 month'));
		//$pmonth = date('M-Y', strtotime($pmonth));
		
        $data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('roll') != 'admin'):
            $students = $this->db->query("select s.* from students as s left join accountledger as l on s.ledger_id=l.id where l.status=1 and l.company_id='$company_id'")->result();
            $month = date('Y-m');
			
            $todate = date('Y-m-d H:i:s');
            $randomkey = time(); $i=0;
			$insertdata=array();
			$datalist3=array();
			$updateArray=array();
            foreach ($students as $key) {
                $pamonth = date('Y-m',strtotime($key->fee_assign));
				
                if($pamonth<$month) 
                { 
                    $i++; 
					$rkey=$randomkey.$i;
					array_push($insertdata,array(
						'voucherid' => $rkey, 
                        'ledgerid' => $key->ledger_id,
                        'date' => $todate,
                        'debit' => $key->fee,
                        'credit' => 0,
                        'vouchertype' => 'Assign Fee',
                        'description' => $pmonth,
                        'company_id' => $this->session->userdata('company_id'),
						'user_id' => $this->session->userdata('user_id')
					));
					array_push($insertdata,array(
						'voucherid' => $rkey,
                        'ledgerid' => 7,
                        'date' => $todate,
                        'debit' => 0,
                        'credit' => $key->fee,
                        'vouchertype' => 'Assign Fee',
                        'description' => $pmonth,
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
					));
					
					$updateArray[] = array(
						'id'=>$key->id,
						'fee_assign' => $todate
					);
					
					array_push($datalist3,array(
						'voucherid' => $rkey,
                        'student_id' => $key->id,
                        'amount' => $key->fee,
                        'casuse' => 'বেতন '.$pmonth,
                        'assign_date' => $todate,
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
                    ));
                    
                }  
            }

			$this->db->insert_batch('ledgerposting', $insertdata);
			$this->db->insert_batch('student_fee', $datalist3);
			$this->db->update_batch('students',$updateArray, 'id');

            $this->session->set_userdata('success',$i.' তালেবে এলেমের বেতন নির্ধারণ করা হয়েছে ।');
            redirect(base_url());
        else:
			$this->session->set_userdata('failed',' শুধু অ্যাডমিন প্রবেশ করতে পারবে ।');
            $this->load->view('login', $data);
        endif;
    }

	function assign_fees(){
		$data['activemenu'] = 'report';
		$data['activesubmenu'] = 'studentlist';
		$data['page_title'] = 'Student List';
		$data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $company_id != ''):
			if($this->input->post('submit')=='submit'):
				$student_id = $this->input->post('student_id');
				$ledger_id = $this->input->post('ledger_id');
				$year = $this->input->post('year');
				$month = $this->input->post('month');
				$cause = $this->input->post('cause');
				$amount = $this->input->post('amount');
				$todate = date('Y-m-d H:i:s');
				$randomkey = time();
				$pmonth = date('M-Y',strtotime($year.'-'.$month));
				
				$insertdata=array();
					array_push($insertdata,array(
						'voucherid' => $randomkey, 
                        'ledgerid' => $ledger_id,
                        'date' => $todate,
                        'debit' => $amount,
                        'credit' => 0,
                        'vouchertype' => 'Assign Fee',
                        'description' => $cause.$pmonth,
                        'company_id' => $this->session->userdata('company_id'),
						'user_id' => $this->session->userdata('user_id')
					));
					array_push($insertdata,array(
						'voucherid' => $randomkey,
                        'ledgerid' => 7,
                        'date' => $todate,
                        'debit' => 0,
                        'credit' => $amount,
                        'vouchertype' => 'Assign Fee',
                        'description' => $cause.$pmonth,
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
					));
					$this->db->insert_batch('ledgerposting', $insertdata);

                    $this->db->query("Update students set fee_assign='$todate' where id='$student_id'");
					
					
					$datalist3 = array(
						'voucherid' => $randomkey,
                        'student_id' => $student_id,
                        'amount' => $amount,
                        'casuse' => $cause.$pmonth,
                        'assign_date' => $todate,
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
                    );
                    $this->db->insert('student_fee', $datalist3);
					redirect('teacher/receive_fee/'.$student_id);
				
			else:
			$this->load->view('teacher/assign_fees',$data);
			endif;
		else:
            $this->load->view('login', $data);
        endif;	
	}

	function allassign_fees(){
		$data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('role') == 'admin'):
			$class_id = $this->input->post('class_id');
            $students = $this->db->query("select s.* from students as s left join accountledger as l on s.ledger_id=l.id where l.status=1 and l.company_id='$company_id' and s.class_id='$class_id'")->result();
			$year = $this->input->post('year');
			$month = $this->input->post('month');
			$cause = $this->input->post('cause');
			$amount = $this->input->post('amount');
			$todate = date('Y-m-d H:i:s');
			$randomkey = time();
			$insertdata=array();
			$datalist3=array();
			$updateArray=array();
			$pmonth = date('M-Y',strtotime($year.'-'.$month));
			$i=0;//count total student for assign fee
			foreach ($students as $key) {
				$i++; 
				$rkey=$randomkey.$i;
					array_push($insertdata,array(
						'voucherid' => $rkey, 
                        'ledgerid' => $key->ledger_id,
                        'date' => $todate,
                        'debit' => $amount,
                        'credit' => 0,
                        'vouchertype' => 'Assign Fee',
                        'description' => $cause.$pmonth,
                        'company_id' => $this->session->userdata('company_id'),
						'user_id' => $this->session->userdata('user_id')
					));
					array_push($insertdata,array(
						'voucherid' => $rkey,
                        'ledgerid' => 7,
                        'date' => $todate,
                        'debit' => 0,
                        'credit' => $amount,
                        'vouchertype' => 'Assign Fee',
                        'description' => $cause.$pmonth,
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
					));
					
					$updateArray[] = array(
						'id'=>$key->id,
						'fee_assign' => $todate
					);
					
					$datalist3[] = array(
						'voucherid' => $rkey,
                        'student_id' => $key->id,
                        'amount' => $amount,
                        'casuse' => $cause.$pmonth,
                        'assign_date' => $todate,
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
                    );
                    
			}
			$this->db->insert_batch('ledgerposting', $insertdata);
			$this->db->insert_batch('student_fee', $datalist3);
			$this->db->update_batch('students',$updateArray, 'id');

			$this->session->set_userdata('success',$i.' তালেবে এলেমের ফি নির্ধারণ করা হয়েছে ।');
			redirect('teacher/students_list/'.$class_id);
		else:
			$this->session->set_userdata('failed',' শুধু অ্যাডমিন প্রবেশ করতে পারবে ।');
            $this->load->view('login', $data);
        endif;
	}

	function receive_fee($student_id){
		$data['activemenu'] = 'report';
		$data['activesubmenu'] = 'studentlist';
		$data['page_title'] = 'Student List';
		$student = $this->db->query("Select s.call_name,s.class_id,s.section_id,s.ledger_id,l.mobile from students as s left join accountledger as l on s.ledger_id=l.id where s.id='".$student_id."'")->row();
		$data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $company_id != ''):
			if($this->input->post('submit')=='submit'):
				
				$assign_fee=$this->input->post('assign_fee[]');
				$paidamount=$this->input->post('paidamount[]');
				$ppaidamount=$this->input->post('ppaidamount[]');
				$totalamount=$this->input->post('totalmount');
				$casuse=$this->input->post('casuse[]');
				
				$student_fee_id=$this->input->post('student_fee_id[]');
				$maxid=count($student_fee_id);
				
				$message = $student->call_name.'\r\n';
				$randomkey = time();
				$todate = date("Y-m-d H:i:s");
				$updateArray = array();
				$insertdata=array();
				$tdue = 0;
				for($i=0;$i<$maxid;$i++){
					$rkey=$randomkey.$i;
					//এসএমএস বডি 
					$message =$message.$casuse[$i].' '.$assign_fee[$i].'৳ জমা '.($paidamount[$i] + $ppaidamount[$i]).'৳';
					//$message =$message.$casuse[$i].' ধার্য '.$assign_fee[$i].'৳ জমা '.($paidamount[$i] + $ppaidamount[$i]).'৳';
					$due = $assign_fee[$i]-($paidamount[$i]+$ppaidamount[$i]);
					if($due>0){
						$message =$message.' বাকি '.$due.'৳\r\n';$tdue+=$due;}
					else
						$message =$message.'\r\n';

					if($paidamount[$i]==0)
					continue;
					
					array_push($insertdata,array(
						'voucherid' => $rkey, 
                        'ledgerid' => $student->ledger_id,
                        'date' => $todate,
                        'debit' => 0,
                        'credit' => $paidamount[$i],
                        'vouchertype' => 'Receive Fee',
                        'description' => $casuse[$i],
                        'company_id' => $this->session->userdata('company_id'),
						'user_id' => $this->session->userdata('user_id')
					));
					array_push($insertdata,array(
						'voucherid' => $rkey,
                        'ledgerid' => 1,
                        'date' => $todate,
                        'debit' => $paidamount[$i],
                        'credit' => 0,
                        'vouchertype' => 'Receive Fee',
                        'description' => $casuse[$i],
                        'company_id' => $this->session->userdata('company_id'),
                        'user_id' => $this->session->userdata('user_id')
					));
					
					$updateArray[] = array(
						'id'=>$student_fee_id[$i],
						'paid_date' => $todate,
						'paid' => ($ppaidamount[$i]+$paidamount[$i]),
						'rec_v' => $rkey,
					);
				}
				$this->db->insert_batch('ledgerposting', $insertdata);
				$this->db->update_batch('student_fee',$updateArray, 'id');
				if($tdue>0)
				$message =$message.'মোট বাকি '.$tdue.'৳\r\n';
				$sms = (mb_strlen($message, "UTF-16BE")/70);
				$rechr = ($sms - floor($sms))*70;
				if($rechr>40 || $rechr<8)
					$message =$message.'ফরহাদুজ্জামান নূরানী মাদ্রাসা';
				elseif($rechr<18)
					$message =$message.'ফ.নূ.মা';
				else
					$message =$message.'ফ.নূ.মাদ্রাসা';

				// send message to admin by whats app 
				//smsWhatsappAdmin($message);
				
				$number = $student->mobile;
				//call sms api
				$response = smsOO($number,$message);
				//store sms
				$arrayData = json_decode($response, true);
				//$arrayData = 0;
				if(is_array($arrayData)){	
					$smsdata = array(
						'number'=> $number,
						'sms_text' => $message,
						'date' => date("Y-m-d H:i:s"),
						'status' => $arrayData['response_code'],
						'message_id' => $arrayData['message_id'],
						'success_message' => $arrayData['success_message'],
						'error_message' => $arrayData['error_message'],
						'sms_no' => ceil(mb_strlen($message, "UTF-16BE")/70)
					);
					$this->db->insert('smslog', $smsdata);
				}
				$this->session->set_userdata('success', 'তালেবে এলেমের '.$totalamount.' টাকা সফল ভাবে জমা হয়েছে ।');
				redirect('teacher/students_list/'.$student->class_id.'/'.$student->section_id.'/#tr-'.$student_id);
                  
			else:
			$data['assignfee'] = $this->db->query("select * from student_fee where student_id='$student_id' and amount<>paid and step=0")->result();

			$data['assignfeepaid'] = $this->db->query("select * from student_fee where student_id='$student_id' and (amount=paid or step=1)")->result();

			$data['student'] = $this->db->query("select s.*,l.ledgername as name,l.address as student_address,l.father_name,l.mobile as guardian_mobile,l.image,l.status from students as s left join accountledger as l on s.ledger_id=l.id where s.id='$student_id' and l.company_id='$company_id'")->row();
			
			$this->load->view('teacher/receive_fee',$data);
			endif;

		else:
            $this->load->view('login', $data);
        endif;
	}

	function edit_sfee(){
		$str = "ফ.নূ.মা";
		$sms = (mb_strlen($str, "UTF-16BE")/70);
		echo mb_strlen($str, "UTF-16BE");
		$rechr = ($sms - floor($sms))*70;
		//echo $rechr;
		$sms = ceil($sms);
	}
	
	function delete_sfee($fee_id){
		$id = base64_decode(urldecode($fee_id));
		$temp = $this->db->query("select a.student_id,a.voucherid,s.ledger_id from student_fee as a left join students as s on a.student_id=s.id where a.id='$id'")->row();
		$this->db->query("delete from student_fee where id='$id'");
		$this->db->query("delete from ledgerposting where voucherid='".$temp->voucherid."' and ledgerid='".$temp->ledger_id."'");
		$this->db->query("delete from ledgerposting where voucherid='".$temp->voucherid."' and ledgerid='7' limit 1");
		$this->session->set_userdata('success', 'ধার্য ফি সফল ভাবে মুছে ফেলা হয়েছে ।');
		redirect('teacher/receive_fee/'.$temp->student_id);
	}

	function waiver_sfee($fee_id){
		$id = base64_decode(urldecode($fee_id));
		$today = date('Y-m-d H:i:s');
		$temp = $this->db->query("select a.student_id,a.voucherid,s.ledger_id from student_fee as a left join students as s on a.student_id=s.id where a.id='$id'")->row();
		$this->db->query("update student_fee set step=1,paid_date='$today' where id='$id'");
		$this->session->set_userdata('success', 'ধার্য ফি সফল ভাবে মওকুফ করা হয়েছে ।');
		redirect('teacher/receive_fee/'.$temp->student_id);
	}

	function edit_allfee(){

	}
	function delete_allfee(){

	}

	function report(){
		$data['activemenu'] = 'report';
		$data['activesubmenu'] = 'studentlist';
		$data['page_title'] = 'Student List';
		$sdate = date('Y-m-01 00:00:00');
		$edate = date('Y-m-t 23:59:59');
		
		$data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $company_id != ''):
			$user_id = $this->session->userdata("user_id");
			$data['notpaid'] = $this->db->query("select l.credit,l.description,l.date,l.ledgerid,a.ledgername,a.address,a.mobile,c.class_name,s.roll from ledgerposting as l left join accountledger as a on l.ledgerid=a.id left join students as s on l.ledgerid=s.ledger_id left join classes as c on s.class_id=c.id where l.vouchertype = 'Receive Fee' and l.ledgerid<>1 and l.user_id='$user_id' and l.randomkey='0000-00-00 00:00:00'")->result();

			$data['paid'] = $this->db->query("select l.credit,l.description,l.date,l.ledgerid,a.ledgername,a.address,a.mobile,c.class_name,s.roll from ledgerposting as l left join accountledger as a on l.ledgerid=a.id left join students as s on l.ledgerid=s.ledger_id left join classes as c on s.class_id=c.id where l.vouchertype = 'Receive Fee' and l.ledgerid<>1 and l.user_id='$user_id' and l.date between '$sdate' and '$edate'  and l.randomkey<>'0000-00-00 00:00:00'")->result();
			
			$this->load->view('teacher/report', $data);
		else:
            $this->load->view('login', $data);
        endif;
	}

	//--------------------------
	public function smstoadmin($amouunt){
		$company_id = $this->session->userdata('company_id');
		$user_id = $this->session->userdata('user_id');
		$data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $company_id != ''):
			$message = $this->session->set_userdata('fullname')." ".$amouunt." টাকা দিয়েছে ?"."\n".$data['baseurl']."teacher/radmin/yes/".$user_id;
			//smsWhatsappAdmin($message);
			redirect('home');

		else:
			$this->load->view('login', $data);

		endif;
	}

	public function radmin($ans,$user_id){
		if($ans=='yes'){
			$tdate = date("Y-m-d H:i:s");
			$this->db->query("update ledgerposting set randomkey='$tdate' where randomkey='0000-00-00 00:00:00' and user_id='$user_id' and vouchertype='Receive Fee'");
		}
	}

	//------------- Ajax call -----------------//

	public function checkroll(){
		$roll = $this->input->post('student_roll');
		$class_id = $this->input->post('class_id');
		$section_id = $this->input->post('section_id');
		if($section_id==""||$section_id==null)
			$section_id = 0;
		
		$array = array('roll' => $roll, 'class_id' => $class_id, 'section_id' => $section_id);
		$this->db->where($array);
	    $query = $this->db->get('students');
	    
	    if ($query->num_rows() > 0){
	        return FALSE;
	    }
	    else{
	        return TRUE;
	    }
	}

	public function getsection(){
		$id = $this->input->post('id');
		$section = $this->ion_auth_model->getsection($id);
		echo json_encode($section);
	}

	public function updatestudent(){
		$mobile = str_replace(' 880','+880',$this->input->post('guardian_mobile'));
		$data = array(
			'ledgername'=>$this->input->post('name'),
			'mobile'=>$mobile,
        );
		$this->db->where('id', $this->input->post('ledger_id'));
		$this->db->update('accountledger', $data);

		$data = array(
        	'call_name' => $this->input->post('call_name'),
        	'roll' => $this->input->post('roll'),
        	'guardian_name' => $this->input->post('guardian_name'),
        	'update_date' => date("Y-m-d"),
	        'update_by' => $this->session->userdata('user_id')
        );
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('students', $data);
	}

	public function getmaxroll(){
		$id = $this->input->post('id');
		$company_id = $this->session->userdata('company_id');
		$mroll = $this->db->query("select max(s.roll) as roll from students as s left join accountledger as l on s.ledger_id=l.id where s.class_id='$id' and l.company_id='$company_id'")->row()->roll;
		echo json_encode($mroll+1);
	}
	
}