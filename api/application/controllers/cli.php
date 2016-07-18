<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CLI extends CI_Controller {

	public function index()
	{
		//$this->install();
	}

	public function install()
	{
		//if (!$this->input->is_cli_request()) return;
		
		$this->load->dbforge();

		// create acl table
		$fields = array(
			'key' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255'
			),
			'value' => array(
				 'type' => 'TEXT'
			)
		);
		$this->dbforge->drop_table('acl');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('key', true);
		$this->dbforge->create_table('acl');

		// create users table
		$fields = array(
			'id' => array(
				 'type' => 'INT',
				 'constraint' => 11, 
				 'null' => false,
				 'auto_increment' => true
			),
			'email' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '50',
				 'null' => false
			),
			'password' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '255',
				 'null' => false
			)
		);
		$this->dbforge->drop_table('users');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('users');
		
		// create families table
		$fields = array(
			'id' => array(
				 'type' => 'INT',
				 'constraint' => 11, 
				 'null' => false,
				 'auto_increment' => true
			),
			'firstname' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '100',
				 'null' => false
			),
			'lastname' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '100',
				 'null' => false
			),
			'nickname' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
				 'null' => true
			),
			'sex' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
				 'null' => false
			),
			'birthday' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
				 'null' => false
			),
			'deathday' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
				 'null' => true
			),
			'profession' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '50',
				 'null' => false
			),
			'email' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '50',
				 'null' => true
			),
			'phone' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '20',
				 'null' => true
			),
			'address' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '256',
				 'null' => true
			),
			'story' => array(
				 'type' => 'TEXT',
				 'null' => false
			),
			'picture' => array(
				 'type' => 'TEXT',
				 'null' => true
			)
		);
		$this->dbforge->drop_table('families');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('families');

		// create relation table
		$fields = array(
			'id' => array(
				 'type' => 'INT',
				 'constraint' => 11, 
				 'null' => false
			),
			'category' => array(
				 'type' => 'VARCHAR',
				 'constraint' => '50',
				 'null' => false
			),
			'level' => array(
				 'type' => 'INT',
				 'constraint' => '11',
				 'null' => false
			),
			'parent' => array(
				 'type' => 'INT',
				 'constraint' => '11',
				 'null' => false
			)
		);
		$this->dbforge->drop_table('relation');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('relation');

		// default resources to protect
		$acl = new ACL();
		$acl->addResource('administrator');
		$acl->addResource('user');
		$acl->addResource('role');
		$acl->addResource('resource');

		// create administrator role and grant all access
		$acl->addRole('administrator');
		$acl->addPermissions('administrator', 'administrator', 'read');
		$acl->addPermissions('administrator', 'user', ['create', 'read', 'update', 'delete']);
		$acl->addPermissions('administrator', 'role', ['create', 'read', 'update', 'delete']);
		$acl->addPermissions('administrator', 'resource', ['create', 'read', 'update', 'delete']);
        
		// custom resources

		// ...
		// ... add your custom resources to protect here
		// ...
        
		if (!defined('PHPUNIT_TEST'))
			echo "installed\r\n";
	}

	public function add($type,$email,$password)
	{
		/*$type="administrator"; $email="foobar@gmail.com"; $password="password123";
		//if (!$this->input->is_cli_request()) return;*/

		if ($type == 'user')
		{
			$this->Users->register($email, $password);
	
			if (!defined('PHPUNIT_TEST'))
				echo "user added\r\n";
		}
		else if ($type == 'administrator')
		{
			$id = $this->Users->register($email, $password);
			$acl = new ACL();
			$acl->addUserRoles($id, 'administrator');

			if (!defined('PHPUNIT_TEST'))
				echo "administrator added\r\n";
		}
	}
	
}

/* End of file cli.php */
/* Location: ./application/controllers/cli.php */