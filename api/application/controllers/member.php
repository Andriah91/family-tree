<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends REST_Controller {

	public function register()
	{
		$this->form_validation->set_rules('firstname', 'firstname', 'required|max_length[100]');
		$this->form_validation->set_rules('lastname', 'lastname', 'required|max_length[100]');
		$this->form_validation->set_rules('nickname', 'nickname', 'max_length[20]');
		$this->form_validation->set_rules('sex', 'sex', 'required|max_length[5]');
		$this->form_validation->set_rules('birthday', 'birthday', 'required|max_length[20]');
		$this->form_validation->set_rules('deathday', 'deathday', 'max_length[20]');
		$this->form_validation->set_rules('profession', 'profession', 'required|max_length[50]');
		$this->form_validation->set_rules('email', 'email', 'valid_email|is_unique[users.email]|max_length[256]');
		$this->form_validation->set_rules('phone', 'phone', 'max_length[20]');
		$this->form_validation->set_rules('address', 'address', 'max_length[200]');
		$this->form_validation->set_rules('story', 'story', 'required');
		$this->form_validation->set_rules('img', 'img', 'max_length[256]');
		$this->form_validation->set_rules('parent', 'parent', 'number');

		return Validation::validate($this, '', '', function($output)
		{
			$firstname = $this->input->post('firstname');
			$lastname = $this->input->post('lastname');
			$nickname = $this->input->post('nickname');
			$sex = $this->input->post('sex');
			$birthday = $this->input->post('birthday');
			$deathday = $this->input->post('deathday');
			$profession = $this->input->post('profession');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$address = $this->input->post('address');
			$story = $this->input->post('story');
			$img = $this->input->post('img');
			$parent = $this->input->post('parent');

			$Family = array (
					'firstname' => $firstname,
					'lastname' => $lastname,
					'nickname' => $nickname,
					'sex' => $sex,
					'birthday' => $birthday,
					'deathday' => $deathday,
					'profession' => $profession,
					'email' => $email,
					'phone' => $phone,
					'address' => $address,
					'story' => $story,
					'picture' => $img
				);
			$this->Members->register($Family, $parent, $category);
			$output['status'] = true;
			return $output;
		});
	}

	public function getName()
    {
        $response = $this->Members->all();
        $c = 0;
        print_r('[');
        
        	foreach ($response->result() as $data) {
        		# code...
        		$c++;
        		print_r('{');
        			print_r('"id" : "'.$data->id.'",');
        			print_r('"fullname" : "'.$data->firstname.' '.$data->lastname.'"');
        		print_r('}');
        		//if($c < $response->num_rows)
        			print_r(',');
        	}
        	print_r('{"id" : "O" , "fullname" : "****Non disponible****" },');
        	print_r('{"id" : "O" , "fullname" : "****Non disponible****" }');
        print_r(']');
    }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */