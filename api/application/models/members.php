<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends CI_Model {
    
	public function register($family, $parent, $category)
	{
		$this->db->insert('families', $family);
		$id = $this->db->insert_id();
		if($parent != 0 )
				$this->addTree($parent, $category, $id);
        return $id;
	}

	public function table($params)
	{
		$limit = intval($params->count);
		$offset = intval(($params->page - 1) * $params->count);
		$sorting = get_object_vars($params->sorting);
		$direction = reset($sorting);
		$key = key($sorting);
		if ($limit > 100) return;
		if ($limit < 0) return;
		if ($offset < 0) return;
		if (!in_array($direction, array('asc', 'desc'))) return;
		if (!in_array($key, array('id', 'firstname'))) return;
		$this->db->select('*');
		$this->db->from('families');
		$this->db->order_by($key, $direction);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		if ($query->num_rows() >= 1)
		{
			$results = $query->result();
		}
		$users = array();
		foreach ($results as $result)
		{
			$user = new stdClass();
			$user->id = $result->id;
			$user->email = $result->email;
			$users[] = $user;
		}
		$total = $this->db->count_all('families');
        
		$data = array();
		$data['total'] = $total;
		$data['users'] = $users;
		return $data;
	}	

	public function read($id)
	{
		$user = new stdClass();

		$this->db->select('*');
		$this->db->from('families');
		$this->db->where('id', $id);
		$this->db->limit(1);

		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$result = $query->result();
			$user->id = $result[0]->id;
			$user->email = $result[0]->email;
			$acl = new ACL();
			$user->roles = $acl->userRoles($user->id);
		}

		return $user;
	}

	public function update($user)
	{
		$this->db->select('*');
		$this->db->from('families');
		$this->db->where('id', $user->id);
		$this->db->limit(1);

		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$acl = new ACL();
			$roles = $acl->userRoles($user->id);
			if ($user->roles != $roles)
			{
				$acl->removeUserRoles($user->id, $roles);
				$acl->addUserRoles($user->id, $user->roles);
			}
			$this->db->where('id', $user->id);
			$this->db->update('users', $user);             
            
			$user = $this->read($user->id);
		}

		return $user;
	}	

	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('families');
		$this->db->limit(1);
	}

	public function all()
	{
		$this->db->select('*');
		$this->db->from('families');
		$query = $this->db->get();
		//$result = $query->result();
		return $query;
	}

	public function addTree($parent,$category,$id)
	{
		$resp = $this->getTree($parent);
		$data = array(
			'id' => $id,
			'category' => $category,
			'level' => $rep[0]->level+1,
			'parent' => $parent
		);

		$this->db->insert('relation', $data);
        return $this->db->insert_id();

	}

	public function getTree($id)
	{
		$this->db->select('*');
		$this->db->from('families');
		$this->db->where('id', $id);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
    
}
