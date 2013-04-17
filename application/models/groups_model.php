<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups_model extends MY_Model{
	
	protected $table = 'groups';
	protected $primary_key = 'id';
	protected $columns = array(

		//only validation
		'admin'			=>	array('Admin', 'trim|integer'),
                'name'			=>	array('Name', 'trim|required|alpa'),
		'description'		=>	array('Description', 'trim|required|alpa'),
 		'status'		=>	array('Is Active', 'trim'),	
 		
	);
        
        function get_from_post($cols = NULL)
	{
		$id = $this->input->post('id');
		
		return parent::get_from_post($cols);
	}

	
	function get_count($filter)
	{
		
		$this->db->select('count(*) as num');
                if($this->session->userdata('user')->role_code!='super'){$this->db->where('g.admin',$this->session->userdata('user')->id);}
		$query = $this->db->get('groups g');
		$row =  $query->row();
                
		return $row->num;
	}

        function get_items($filter, $offset, $limit)
	{
            
		$this->set_filter($filter);
                $this->db->from($this->table.' g')->limit($limit, $offset);
                if($this->session->userdata('user')->role_code!='super'){$this->db->where('g.admin',$this->session->userdata('user')->id);}
                $sort_col = element('sort_col', $filter);
		$sort_dir = element('sort_dir', $filter);
		if($sort_col){
			$this->db->order_by($sort_col, $sort_dir);
		}
		$query = $this->db->get();
                
		return $query->result();
	}
        function set_filter($filter)
	{
            
	$status = element('status', $filter, 2);
        
        if($status != 2){
           $this->db->where('g.status',$status);
        }

        if($name = element('name', $filter)){
        	$this->db->where("(g.name LIKE '%$name%')");
        }
	}
        
        function group_list($grp_id){
            $sql = "SELECT id,name,account_group FROM (`accounts` g) WHERE `g`.`is_active` = '1'   AND g.id not in (SELECT doctor_id FROM group_doctors WHERE group_id=$grp_id )";           
            $result['not'] =   $this->db->query($sql);
            
            $sql2 = "SELECT id,name,account_group FROM (`accounts` g) WHERE `g`.`is_active` = '1'   AND g.id in (SELECT doctor_id FROM group_doctors WHERE group_id=$grp_id )";           
            $result['yes'] =   $this->db->query($sql2);
            
            return $result;
        }
        
        function getGroup($Id)
        {
            $this->db->select('*');
             $this->db->where("g.id",$Id);
            $query = $this->db->get('groups g');

            $row =  $query->row();
            return $row;
        }
        
        function assigngroupdoc($gid,$doclist)
        {
            $this->db->where('group_id', $gid);
            $this->db->delete('group_doctors'); 
            foreach($doclist as $s):
                $data=array(
                    'doctor_id'=>$s,
                    'group_id'=>$gid);
                $this->db->insert('group_doctors',$data);
            endforeach;
        }
        
        function get_record($id){
                
                $this->db->select('*');
                $this->db->from($this->table.' g');
                $this->db->where('id', $id);
                $query = $this->db->get();
                
		return $query->result();
        }
        
        function get_group_record($id){
            $this->db->select('a.name,a.account_group');
            $this->db->from($this->table.' g')->join('group_doctors gd', 'g.id=gd.group_id')->join('accounts a', 'a.id=gd.doctor_id');
            $this->db->where('g.id', $id);
            $query = $this->db->get();

            return $query->result();
            
        }
        
        function deleteGroupListRecord($id){
                $this->db->where('group_id', $id);
                $this->db->delete('group_doctors');
            
        }
        
        function getGroupListData($text,$type){
                $type=($type=='all')?'':$this->db->where("account_group",$type);
                
                $this->db->select('id,name,account_group')->from('accounts');
                $this->db->where("name LIKE '%$text%'");
                $query = $this->db->get();
                return $query->result();
        }

	
}