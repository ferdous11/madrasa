<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam extends CI_Controller {
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

    public function index(){
		$data['activemenu'] = 'master';
		$data['activesubmenu'] = 'student';
		$data['page_title'] = 'Student List';
		$data['baseurl'] = $this->config->item('base_url');
		$company_id = $this->session->userdata('company_id');
	    if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
			
			$data['classes'] = $this->db->query("select * from classes where company_id='$company_id'")->result();
			$data['exams'] = $this->db->query("select * from exams order by id desc")->result();
			$data['subjects'] = array();
			if($this->input->post('class_id')){
				$class_id =  $data["class_id"] = $this->input->post('class_id');
				$exam_id =  $data['exam_id'] =  $this->input->post('exam_id');
	  			$subject_id = $data["subject_id"] = $this->input->post('subject_id');
				$data['subjects'] = $this->db->query("select * from subjects where class_id='".$data["class_id"]."'")->result();
	  			$data["class_name"] = $this->db->query("select class_name from classes where id='".$data['class_id']."' and company_id='$company_id'")->row()->class_name;
	  		
				$year =   date('Y');
				
				$data["studentlist"] = $this->db->query("select s.id,s.call_name,s.roll,m.mark,m.id as mark_id from students as s right join marks as m on s.id=m.student_id where s.class_id='$class_id'and m.exam_id='$exam_id' and m.subject_id='$subject_id' and m.session='$year'" )->result(); 

				if(empty($data["studentlist"])){
					$data["studentlist"] = $this->db->query("select s.id,s.call_name,s.roll,'0' as mark,'0' as mark_id from students as s where s.class_id=".$data["class_id"] )->result(); 
				}
					  
	  			$this->load->view('exam/exam',$data);
			}
			else{
				$data['class_id'] = "";
				$data['exam_id'] = "";
				$data['subject_id'] = "";
				$this->load->view('exam/exam',$data);
			}    
	  else :
		return show_error('You must be a teacher to view this page.');
	  endif;
	}

	public function saveamarks(){
		if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):	
		$student_id=$this->input->post('student_id[]');
		$marks=$this->input->post('marks[]');
		$mark_id=$this->input->post('mark_id[]');
		$class_id=$this->input->post('class_id');
		$subject_id=$this->input->post('subject_id');
		$exam_id=$this->input->post('exam_id');
		
		$insertdata = array();
		$maxid=count($student_id);
		if($mark_id[0]>0){
			for($i=0;$i<$maxid;$i++){
				array_push($insertdata,array(
	                'id' => $mark_id[$i],
	                'mark' => $marks[$i],
	                'update_by' => $this->session->userdata('user_id')
	        	));
			}
			$this->db->update_batch('marks', $insertdata, 'id');

			$this->session->set_userdata('success', 'তালেবে এলেমদের প্রাপ্ত নম্বর সফল ভাবে সংশোধন হয়েছে ।');
		}
		else{
			for($i=0;$i<$maxid;$i++){
				array_push($insertdata,array(
					'student_id' => $student_id[$i],
					'subject_id ' => $subject_id,
					'exam_id ' => $exam_id,
					'class_id ' => $class_id,
					'session ' => date('Y'),
					'mark' => $marks[$i],
					'update_by' => $this->session->userdata('user_id'),
				));
			}
			$this->db->insert_batch('marks', $insertdata);
			$this->session->set_userdata('success', 'তালেবে এলেমদের প্রাপ্ত নম্বর সফল ভাবে যুক্ত হয়েছে ।');
		}
		return $this->index();     
	  else :
		return show_error('You must be a teacher to view this page.');
	  endif;
	}

	function exam_report($class_id,$exam_id){
		$data['activemenu'] = 'report';
		$data['activesubmenu'] = 'studentlist';
		$data['page_title'] = 'Student List';
		$data['baseurl'] = $this->config->item('base_url');
		
		if($this->session->userdata('role')=='teacher'||$this->session->userdata('role')=='admin'):

			$data['class_name'] = $this->db->query("select class_name from classes where id='$class_id'")->row()->class_name;

			$data['exam_name'] = $this->db->query("select name from exams where id='$exam_id'")->row()->name;

			$data['subjects'] = $this->db->query("select name from subjects where class_id='$class_id' order by id asc")->result();

			$students=$data['students'] = $this->db->query("select s.id,s.roll,l.ledgername from students as s left join accountledger as l on s.ledger_id=l.id where s.class_id='$class_id'")->result();

			foreach($students as $student){
				$mark[$student->id] = $this->db->query("select mark from marks where class_id='$class_id' and exam_id='$exam_id' and student_id='$student->id' order by subject_id asc")->result();

				$result[$student->id] = $this->db->query("select sum(mark) as tmark from marks where class_id='$class_id' and exam_id='$exam_id' and student_id='$student->id'")->row()->tmark;
			}
		
			arsort($result);
			
			$i=1;
			foreach($result as $k=>$v){
				$newRoll[$k] = $i++;
			}
			$data['newRoll'] = $newRoll;
			$data['marks'] = $mark;
			$this->load->view('exam/examreport',$data);

		else :
			return show_error('You must be a teacher to view this page.');
		endif;	
	}

		//------------- Ajax call -----------------//

	

		public function getsubject(){
			$id = $this->input->post('id');
			$subjects = $this->db->query("select name,id from subjects where class_id='$id'")->result();
			echo json_encode($subjects);
		}


}