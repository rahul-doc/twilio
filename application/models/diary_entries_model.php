<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Diary_entries_model extends MY_Model
{
	protected $table = "diary_entry";
	protected $primary_key = "id";
	protected $columns = array(
		'episode_id'	=> array('EpisodeId', 'trim|required|interger'),		
		'content'	=>	array('Description', 'trim|required')	
	);

	
	function set_filter($filter)
	{
     	return;   
	}

	function get_items ($episode_id, $filter, $offset, $limit)
	{
		$this->set_filter($filter);
        $this->db->from($this->table);
        $this->db->where('episode_id', $episode_id);

		if($limit){
			$this->db->limit($limit, $offset);
		}
		$sort_col = element('sort_col', $filter);
		$sort_dir = element('sort_dir', $filter);
		if($sort_col){
			$this->db->order_by($sort_col, $sort_dir);
		}
		$query = $this->db->get();

		return $query->result();
	}

	function get_count($filter)
	{
		$this->set_filter($filter);
		$this->db->select('count(*) as num');
		$query = $this->db->get($this->table);
		$row =  $query->row();
		return $row->num;
	}


	function get_entry_objects($id)
	{
		$this->db->where('entry_id', $id);
		$query = $this->db->get('diary_entry_obj');
		return $query->result();
	}	

	function save_transcription($obj_id, $transcript, $entry_id)
	{
		$this->db->set('transcript', $transcript)
				->where('id', $obj_id)
				->update('diary_entry_obj');

		//change type of episode to transcript
		$this->db->update('diary_entry', array('type'=>'transcript'), array('id'=>$entry_id));
	}

}


