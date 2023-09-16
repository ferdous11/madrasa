<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('form', 'file');
        $this->load->helper('url');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->load->helper('common');
        $this->load->helper('csv');
        $this->load->library('image_lib');
        $this->load->library('form_validation');
    }
    function attendance_report(){
        /* 
           1. Rat 12 tar mordhe finger na dile count korbe na. 
           2. start time ar age finger dile start time theke count korbe.
           3. end time ar porer 10 minute count korbe na.
        */
        
        $data['baseurl'] = $this->config->item('base_url');
        $comid = $this->session->userdata('company_id');
        $sdate = date("Y-m-d") . ' 00:00:00';
        $edate = date("Y-m-d") . ' 23:59:59';
        $recordarray = array();
        $in = $out = $totaltime = 0;
        $firstin = $lastout = "";
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['sdate'] = date("Y-m-d");
            $data['edate'] = date("Y-m-d");
            $data['activemenu'] = 'Employee';
            $data['activesubmenu'] = 'attendance_report';
            $data['page_title'] = 'Attendance Report';
            $employee_id=$data['employee_id'] = 'all';

            if($this->input->post('sdate')){
                $data['sdate'] = $this->input->post('sdate');
                $data['edate'] = $this->input->post('edate');
                $sdate = $this->input->post('sdate') . ' 00:00:00';
                $edate = $this->input->post('edate') . ' 23:59:59';
                $employee_id=$data['employee_id'] = $this->input->post('employee');
            }

            $data['employeelist'] = $this->db->query("select l.ledgername,l.id,l.address,l.description,d.name as district_name from accountledger as l left join districts as d on l.district=d.id right join employee as e on l.id=e.employee_id where l.accountgroupid=18 and l.status<>0")->result();
            
            if($employee_id=='all'){
                $data['attendancedata'] = $this->db->query("select e.*,l.ledgername,l.description from employee as e left join accountledger as l on e.employee_id=l.id where l.accountgroupid=18 and l.status<>0")->result();
                $begin = new DateTime($sdate);
                $end = new DateTime($edate);

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                $i=1;
                foreach ($data['attendancedata'] as $key) {
                    $data['arraydate'][$i] = $data['sdate']." To ".$data['edate'];
                    $data['arrayname'][$i] = $key->ledgername;
                    $data['arraytime'][$i] =$data['arraysalary'][$i]= $data['array_in'][$i]=$data['array_out'][$i]= 0;
                    foreach ($period as $dt) {
                        $firsttime= $secondtime =0;
                        $j=1;
                        $attendancedata = $this->db->query("select datestamp,date from employee_attendance where date between '".$dt->format("Y-m-d 00:00:00")."' and '".$dt->format("Y-m-d 23:59:59")."' and employee_id='$key->description' ORDER BY date")->result();
                        foreach($attendancedata as $record){
                            
                            $endtime = date('Y-m-d',$record->datestamp)." ".$key->end;
                            $starttime = date('Y-m-d',$record->datestamp)." ".$key->start;

                            if(date('Y-m-d H:i:s',$attendancedata[0]->datestamp)>$endtime){
                                break;//jodi keu oi din working timer pore fingur dey.
                            }

                            if($j==1){
                                $firsttime  =  $record->datestamp;
                                $secondtime  = 0;
                                $lastouta = date('H:i:s',$record->datestamp);
                                $intime = date('Y-m-d H:i:s',$record->datestamp);
                                
                                if($starttime > $intime){
                                    $firsttime  =  strtotime($starttime);
                                }
                                ++$data['array_in'][$i];
                                $j=2;continue;
                            }
                            if($j==2){
                                $secondtime  =  $record->datestamp;
                                ++$data['array_out'][$i];
                                $outtime = date('Y-m-d H:i:s',$record->datestamp);
                                
                                $leasetime = strtotime($endtime) + 600;//end time ar por 10 minute count korbena;
                                $leasetime = date('Y-m-d H:i:s',$leasetime);

                                if($outtime>$endtime && $outtime<$leasetime){
                                    $secondtime  = strtotime($endtime);
                                }
                                else if($outtime>$endtime && $outtime>$leasetime){
                                    $secondtime  -= 600;//end time ar por 10 minute count korbena;
                                }
                                $data['arraytime'][$i]+=($secondtime-$firsttime);
                                $j=1;
                            }
                        }
                        
                    } 
                    if($key->type=="Hourly")
                        $data['arraysalary'][$i] = ($key->salary/3600)* $data['arraytime'][$i];
                       
                    $i++;
                }
                
                    
            }

            else{
                
                $employee_data = $this->db->query("select e.*,l.ledgername from employee as e left join accountledger as l on e.employee_id=l.id where l.description='$employee_id'")->row();
                
                $begin = new DateTime($sdate);
                $end = new DateTime($edate);

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                $i=1;
                foreach ($period as $dt) {
                    $data['arraydate'][$i] = $dt->format("Y-m-d");
                    $data['arrayname'][$i] = $employee_data->ledgername;
                    $data['arraytime'][$i] = $data['arraysalary'][$i] = $firsttime= $secondtime =$data['array_in'][$i]=$data['array_out'][$i]= 0;
                    $j=1;
                    $attendancedata = $this->db->query("select datestamp,date from employee_attendance where date between '".$dt->format("Y-m-d 00:00:00")."' and '".$dt->format("Y-m-d 23:59:59")."' and employee_id='$employee_id' ORDER BY date")->result();
                    foreach($attendancedata as $record){
                        
                        $endtime = date('Y-m-d',$record->datestamp)." ".$employee_data->end;
                        $starttime = date('Y-m-d',$record->datestamp)." ".$employee_data->start;

                        if(date('Y-m-d H:i:s',$attendancedata[0]->datestamp)>$endtime){
                            break;//jodi keu oi din working timer pore fingur dey.
                        }

                        if($j==1){
                            $firsttime  =  $record->datestamp;
                            $secondtime  = 0;
                            $lastouta = date('H:i:s',$record->datestamp);
                            $intime = date('Y-m-d H:i:s',$record->datestamp);
                            
                            if($starttime > $intime){
                                $firsttime  =  strtotime($starttime);
                            }
                            ++$data['array_in'][$i];
                            $j=2;continue;
                        }
                        if($j==2){
                            $secondtime  =  $record->datestamp;
                            ++$data['array_out'][$i];
                            $outtime = date('Y-m-d H:i:s',$record->datestamp);
                            
                            $leasetime = strtotime($endtime) + 600;
                            $leasetime = date('Y-m-d H:i:s',$leasetime);

                            if($outtime>$endtime && $outtime<$leasetime){
                                $secondtime  = strtotime($endtime);
                            }
                            else if($outtime>$endtime && $outtime>$leasetime){
                                $secondtime  -= 600;
                            }
                            $data['arraytime'][$i]+=($secondtime-$firsttime);
                            
                            $j=1;
                        }
                    }
                    if($employee_data->type=="Hourly")
                        $data['arraysalary'][$i] = ($employee_data->salary/3600)* $data['arraytime'][$i];
                    $i++;
                } 
                
            }
             $this->load->view('employee/attendance_report', $data);

        else:
            $this->load->view('login', $data);
        endif;
    }

    function attendanceUplodCsv(){
        // prothome system date ar data delete hobe pore oi diner data dite hobe;
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != '' && $_FILES['upload_data_file']  )
        {

            $file_name = $_FILES['upload_data_file']['name'];
            $chk_ext = explode(".", $file_name);

            if( (strtolower(end($chk_ext)) !== "csv") ) {
                $this->session->set_userdata('failed', 'Your file type must be in csv format');
                //$this->load->view('admin/upload_product_csv', $data);
                redirect('employee/attendance_report');
                return false;
            }

            
            $asdate = $this->input->post('upload_date')." 00:00:00";
            
            $this->db->query("delete from employee_attendance where date > '$asdate'");
            
            $fname = $_FILES['upload_data_file']['tmp_name'];
            
            $handle = fopen($fname, "r");
            
            $my_data = array();
            while ( ($up_data = fgetcsv($handle,1000,",","\0")) !== FALSE ) {
                $up_data = array_map("utf8_encode", $up_data );
                $my_data[]=$up_data;
            }
            fclose($handle);
 
            $j=1;
            for($i=1;$i<count($my_data);$i++){
                
                if($my_data[$i][0]==null)
                    continue;
                $st = strtotime($my_data[$i][3]);
                if(date('Y-m-d H:i:s',$st)< $asdate)
                    continue;
                $date=strtotime($my_data[$i][3]);
                $employee_id = ltrim($my_data[$i][0], "'");
                $newProduct = array(
                'employee_id' => $employee_id,
                'name' => $my_data[$i][1],
                'date' => date("Y-m-d H:i:s",$date),
                'datestamp' => $st,
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id')
                );
                if($this->db->insert('employee_attendance', $newProduct))
                    $j++;
            }
            if($j!=1)
                $this->session->set_userdata('success', 'Upload csv file successfully. '.($j-1).' new Entry!!');
            else
                $this->session->set_userdata('failed', 'Failed to upload '.$file_name);
            redirect('employee/attendance_report');
        }
        else
            $this->load->view('login', $data);
    }

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

    function addledger() {
        $data['baseurl'] = $this->config->item('base_url');
        $comid = $this->session->userdata('company_id');
       
        
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):

            $datainsert = array(
                'employee_id' => $this->input->post('employee'),
                'salary' => $this->input->post('salary'),
                'type' => $this->input->post('type'),
                'start' => $this->input->post('start').":00",
                'end' => $this->input->post('end').":00",
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'date' => date("Y-m-d H:i:s")
            );
            $this->db->insert('employee', $datainsert);
            $supid = $this->db->insert_id();
            $employee_id=$this->input->post('employee');
            $employee_fid = $this->input->post('employee_id');

            $this->db->query("update accountledger set description='$employee_fid' where id='$employee_id'");
            $this->session->set_userdata('success', 'Employee Salary added successfully');

            redirect('employee/employee_salary');
          
        else:
            $this->load->view('login', $data);
        endif;
    }

    function showledger($id){
        $data['activemenu'] = 'employee';
        $data['activesubmenu'] = 'employee_salary';
        $data['page_title'] = 'Update Employee Salary';
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $data['salarylist']  = $this->db->query("select employee.*,accountledger.ledgername,accountledger.description from employee left join accountledger on employee.employee_id = accountledger.id where accountledger.id='$id'")->row();
            
            $this->load->view('employee/editsalary', $data);
        else:
            $this->load->view('login', $data);
        endif;
    }

    function updatesalary() {
        $data['baseurl'] = $this->config->item('base_url');
        $comid = $this->session->userdata('company_id');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            
            $datainsert = array(
                'salary' => $this->input->post('salary'),
                'type' => $this->input->post('type'),
                'start' => $this->input->post('start'),
                'end' => $this->input->post('end'),
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'date' => date("Y-m-d H:i:s"),
            );
            $this->db->where('id',  $this->input->post('ledgerid'));
            $this->db->update('employee', $datainsert);
            $employee_id=$this->input->post('employee');
            $employee_fid = $this->input->post('employee_id'); 
            
            $this->db->query("update accountledger set description='$employee_fid' where id='$employee_id'");
            $this->session->set_userdata('success', 'Employee Salary updated successfully');

            savelog('Employee Salary updated', 'New account ledger ' . $this->input->post('salary') . ' updated successfully');
            redirect('employee/employee_salary');
        else:
            $this->load->view('login', $data);
        endif;
    }

    function assign_salary(){
        $data['baseurl'] = $this->config->item('base_url');
        if ($this->session->userdata('loggedin') == 'yes' && $this->session->userdata('company_id') != ''):
            $employee = $this->db->query("select e.* from employee as e left join accountledger as l on e.employee_id=l.id where l.status=1")->result();
            $month = date('Y-m');
            $todate = date('Y-m-d H:i:s');
            $randomkey = time(); $i=0;
            foreach ($employee as $key) {
                $sdate = date('Y-m',strtotime($key->assign_salary_month));
                if($sdate<$month) 
                {
                    $i++;
                    $datalist = array(
                        'voucherid' => $randomkey,
                        'ledgerid' => $key->employee_id,
                        'date' => $todate,
                        'debit' => 0,
                        'credit' => $key->salary,
                        'vouchertype' => 'Assign Salary',
                        'description' => $month,
                        'company_id' => $this->session->userdata('company_id')
                    );
                    $this->db->insert('ledgerposting', $datalist);

                    $datalist2 = array(
                        'voucherid' => $randomkey,
                        'ledgerid' => 9,
                        'date' => $todate,
                        'debit' => $key->salary,
                        'credit' => 0,
                        'vouchertype' => 'Assign Salary',
                        'description' => $month,
                        'company_id' => $this->session->userdata('company_id')
                    );
                    $this->db->insert('ledgerposting', $datalist2);

                    $this->db->query("Update employee set assign_salary_month='$todate' where employee_id='$key->employee_id'");
                }   
            }
            $this->session->set_userdata('success', 'Assing Salary '.$i.' Employee');
            redirect('employee/employee_salary');
        else:
            $this->load->view('login', $data);
        endif;
    }

    public function exports_data($sdate1,$edate1,$ledger_id){
        
        $sdate = $sdate1 . ' 00:00:00';
        $edate = $edate1 . ' 23:59:59';
        $employee_id= $ledger_id;

        if($employee_id=='all'){
            $attendancedata = $this->db->query("select e.*,l.ledgername,l.description from employee as e left join accountledger as l on e.employee_id=l.id where l.accountgroupid=18 and l.status<>0")->result();
            $begin = new DateTime($sdate);
            $end = new DateTime($edate);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $i=1;
            foreach ($attendancedata as $key) {
                $arraydate[$i] = $sdate1." To ".$edate1;
                $arrayname[$i] = $key->ledgername;
                $arraytime[$i] =$arraysalary[$i]= $array_in[$i]=$array_out[$i]= 0;
                foreach ($period as $dt) {
                    $firsttime= $secondtime =0;
                    $j=1;
                    $attendancedata = $this->db->query("select datestamp,date from employee_attendance where date between '".$dt->format("Y-m-d 00:00:00")."' and '".$dt->format("Y-m-d 23:59:59")."' and employee_id='$key->description' ORDER BY date")->result();
                    foreach($attendancedata as $record){
                        
                        $endtime = date('Y-m-d',$record->datestamp)." ".$key->end;
                        $starttime = date('Y-m-d',$record->datestamp)." ".$key->start;

                        if(date('Y-m-d H:i:s',$attendancedata[0]->datestamp)>$endtime){
                            break;//jodi keu oi din working timer pore fingur dey.
                        }

                        if($j==1){
                            $firsttime  =  $record->datestamp;
                            $secondtime  = 0;
                            $lastouta = date('H:i:s',$record->datestamp);
                            $intime = date('Y-m-d H:i:s',$record->datestamp);
                            
                            if($starttime > $intime){
                                $firsttime  =  strtotime($starttime);
                            }
                            ++$array_in[$i];
                            $j=2;continue;
                        }
                        if($j==2){
                            $secondtime  =  $record->datestamp;
                            ++$array_out[$i];
                            $outtime = date('Y-m-d H:i:s',$record->datestamp);
                            
                            $leasetime = strtotime($endtime) + 900;//end time ar por 15 minute count korbena;
                            $leasetime = date('Y-m-d H:i:s',$leasetime);

                            if($outtime>$endtime && $outtime<$leasetime){
                                $secondtime  = strtotime($endtime);
                            }
                            else if($outtime>$endtime && $outtime>$leasetime){
                                $secondtime  -= 900;//end time ar por 15 minute count korbena;
                            }
                            $arraytime[$i]+=($secondtime-$firsttime);
                            $j=1;
                        }
                    }
                    
                } 
                if($key->type=="Hourly")
                    $arraysalary[$i] = ($key->salary/3600)* $arraytime[$i];
                   
                $i++;
            }
            
                
        }

        else{
            
            $employee_data = $this->db->query("select e.*,l.ledgername from employee as e left join accountledger as l on e.employee_id=l.id where l.description='$employee_id'")->row();
            
            $begin = new DateTime($sdate);
            $end = new DateTime($edate);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $i=1;
            foreach ($period as $dt) {
                $arraydate[$i] = $dt->format("Y-m-d");
                $arrayname[$i] = $employee_data->ledgername;
                $arraytime[$i] = $arraysalary[$i] = $firsttime= $secondtime =$array_in[$i]=$array_out[$i]= 0;
                $j=1;
                $attendancedata = $this->db->query("select datestamp,date from employee_attendance where date between '".$dt->format("Y-m-d 00:00:00")."' and '".$dt->format("Y-m-d 23:59:59")."' and employee_id='$employee_id' ORDER BY date")->result();
                foreach($attendancedata as $record){
                    
                    $endtime = date('Y-m-d',$record->datestamp)." ".$employee_data->end;
                    $starttime = date('Y-m-d',$record->datestamp)." ".$employee_data->start;

                    if(date('Y-m-d H:i:s',$attendancedata[0]->datestamp)>$endtime){
                        break;//jodi keu oi din working timer pore fingur dey.
                    }

                    if($j==1){
                        $firsttime  =  $record->datestamp;
                        $secondtime  = 0;
                        $lastouta = date('H:i:s',$record->datestamp);
                        $intime = date('Y-m-d H:i:s',$record->datestamp);
                        
                        if($starttime > $intime){
                            $firsttime  =  strtotime($starttime);
                        }
                        ++$array_in[$i];
                        $j=2;continue;
                    }
                    if($j==2){
                        $secondtime  =  $record->datestamp;
                        ++$array_out[$i];
                        $outtime = date('Y-m-d H:i:s',$record->datestamp);
                        
                        $leasetime = strtotime($endtime) + 900;
                        $leasetime = date('Y-m-d H:i:s',$leasetime);

                        if($outtime>$endtime && $outtime<$leasetime){
                            $secondtime  = strtotime($endtime);
                        }
                        else if($outtime>$endtime && $outtime>$leasetime){
                            $secondtime  -= 900;
                        }
                        $arraytime[$i]+=($secondtime-$firsttime);
                        
                        $j=1;
                    }
                }
                if($employee_data->type=="Hourly")
                    $arraysalary[$i] = ($employee_data->salary/3600)* $arraytime[$i];
                $i++;
            } 
            
        }

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Attendance Report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');

        $totaldate = 0;$attent = 0; $totaltk = 0;

        $header = array("Date","Employee Name","In","Out","Working Time(h:m:s)","Salary(Tk.)"); 
        fputcsv($handle, $header);
        

        for($i=1;$i<=array_key_last($arraydate);$i++): 
            if($employee_id!='all') {
                $totaldate++;
                if($array_in[$i]!=0)
                    $attent++;
            }

       

           $arr[0]= $arraydate[$i]; 
           $arr[1]= $arrayname[$i]; 
           $arr[2]= $array_in[$i];
           
           $arr[3]= $array_out[$i];   
           $arr[4]= (int) ($arraytime[$i]/3600).":".(int)(($arraytime[$i]%3600)/60).":".($arraytime[$i]%60);   
           $arr[5]= round ($arraysalary[$i]); 
           $totaltk+=round ($arraysalary[$i]);
           fputcsv($handle, $arr);   
        endfor;
        
        fclose($handle);
        exit;
    }
}