<?php
/**
* Description of Export Model
*
* @author Web Preparations Team
*
* @email webpreparations@gmail.com
*/
if (!defined('BASEPATH'))
exit('No direct script access allowed');
 
 
class Export extends CI_Model {
// get mobiles list
public function mobileList() {
$this->db->select(array('m.id', 'm.model_no', 'm.mobile_name', 'm.company', 'm.price', 'm.mobile_category'));
$this->db->from('mobiles as m');
$query = $this->db->get();
return $query->result_array();
}
}
?>