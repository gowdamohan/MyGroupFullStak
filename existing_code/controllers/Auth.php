<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	/**
	 * Redirect if needed, otherwise display the user list
	 */
	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		else if (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}
		else
		{
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}

			$this->_render_page('auth/index', $this->data);
		}
	}

	/**
	 * Log the user in
	 */
	public function login()
	{

		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{				
				//if the login is successful
				//redirect them back to the home page
				// $this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->session->set_flashdata('flashSuccess', $this->ion_auth->messages());
				redirect('dashboard', 'refresh');
			}
			else
			{

				$this->session->set_flashdata('flashError', $this->ion_auth->errors());

				// if the login was un-successful
				// redirect them back to the login page
				// $this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
				

			);

			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
			);
			$this->_render_page('auth/login', $this->data);
		}
	}

	/**
	 * Log the user in
	 */
	public function client_login($groupName)
	{


		$this->data['title'] = $this->lang->line('login_heading');

		// validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool)$this->input->post('remember');

			$result = $this->ion_auth->client_login($this->input->post('identity'), $this->input->post('password'), $remember, $groupName);

			if ($result == 'success') {
				$this->session->set_flashdata('flashSuccess', $this->ion_auth->messages());
				redirect('dashboard', 'refresh');
			}else if($result){
				$userinfo = $this->db->select('u.id, u.group_id, gc.name as group_name, gp.name as userGroup')
				->from('users u')
				->where('u.id',$result)
				->join('group_create gc','u.group_id=gc.id')
				->join('users_groups ug','u.id=ug.user_id')
    			->join('groups gp','ug.group_id=gp.id')
				->get()->row();
				$uri ='0';
				if ($userinfo->userGroup =='client_god') {
					$uri = $userinfo->userGroup;
				}
				redirect('client-form/'.$userinfo->group_name.'/'.$userinfo->group_id.'/'.$userinfo->id.'/'.$uri);
			}else{
				$this->session->set_flashdata('flashError', $this->ion_auth->errors());
				// if the login was un-successful
				// redirect them back to the login page
				// $this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('client-login/'.$groupName, 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			redirect('client-login/'.$groupName, 'refresh'); 
		}
	}

	/**
	 * Log the user out
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		// $this->session->set_flashdata('message', $this->ion_auth->messages());
		$this->session->set_flashdata('flashSuccess', $this->ion_auth->errors());
		redirect('', 'refresh');
	}

	/**
	 * Change password
	 */
	public function change_password($groupname)
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() === FALSE)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id' => 'old',
				'type' => 'password',
				'class'=>'form-control',
				'placeholder'=>'Enter Old Password'
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id' => 'new',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class'=>'form-control',
				'placeholder'=>'Enter New Password'
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				'class'=>'form-control',
				'placeholder'=>'Confirm Password'
			);
			$this->data['user_id'] = array(
				'name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			);

			// render
			$this->_render_page('home/change_password/'.$groupname, $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('home/logout_change_password/'.$groupname);
				// $this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password/'.$groupname, 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if ($this->config->item('identity', 'ion_auth') != 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
			$this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() === FALSE)
		{
			$this->data['type'] = $this->config->item('identity', 'ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ($this->config->item('identity', 'ion_auth') != 'email')
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{

			$identity_column = $this->config->item('identity', 'ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if (empty($identity))
			{

				if ($this->config->item('identity', 'ion_auth') != 'email')
				{
					$this->ion_auth->set_error('forgot_password_identity_not_found');
				}
				else
				{
					$this->ion_auth->set_error('forgot_password_email_not_found');
				}

				$this->session->set_flashdata('flashError', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('flashSuccess', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('flashError', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	/**
	 * Forgot password
	 */
	public function forgot_password_client($groupName){
		$clientLogo = $this->db->select('cd.logo')
		->from('group_create gc')
		->join('create_details cd','gc.id=cd.create_id')
		->where('gc.name',$groupName)
		->get()->row();
		$logo = base_url().'assets/front/logo.png';
		if (!empty($clientLogo)) {
			$logo = base_url().$clientLogo->logo;
		}
		$data['logo'] = $logo;
		$data['groupName'] = $groupName;
		$this->load->view('auth/forgot_password_client',$data);
	}

	/**
	 * Reset password - final step for forgotten password
	 *
	 * @param string|null $code The reset code
	 */
	public function reset_password($code = NULL)
	{

		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() === FALSE)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
					'class'=>'form-control',
					'placeholder'=>'Enter New Password'
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
					'class'=>'form-control',
					'placeholder'=>'Confirm New Password'
				);
				$this->data['user_id'] = array(
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				
				// // do we have a valid request?
				// if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				// {

				// 	// something fishy might be up
				// 	// $this->ion_auth->clear_forgotten_password_code($code);

				// 	show_error($this->lang->line('error_csrf'));

				// }
				// else
				// {
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};
					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));
					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				// }
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	/**
	 * Deactivate the user
	 *
	 * @param int|string|null $id The user ID
	 */
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int)$id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() === FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					return show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

	/**
	 * Create a new user
	 */
	public function create_user()
	{
		$this->data['title'] = $this->lang->line('create_user_heading');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');
		$this->data['identity_column'] = $identity_column;

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
		if ($identity_column !== 'email')
		{
			$this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
		}
		else
		{
			$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
		}
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() === TRUE)
		{
			$email = strtolower($this->input->post('email'));
			$identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'company' => $this->input->post('company'),
				'phone' => $this->input->post('phone'),
			);
		}
		if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data))
		{
			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['identity'] = array(
				'name' => 'identity',
				'id' => 'identity',
				'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['company'] = array(
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $this->form_validation->set_value('company'),
			);
			$this->data['phone'] = array(
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);

			$this->_render_page('auth/create_user', $this->data);
		}
	}
	/**
	* Redirect a user checking if is admin
	*/
	public function redirectUser(){
		if ($this->ion_auth->is_admin()){
			redirect('auth', 'refresh');
		}
		redirect('/', 'refresh');
	}

	/**
	 * Edit a user
	 *
	 * @param int|string $id
	 */
	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups = $this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'trim|required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'company' => $this->input->post('company'),
					'phone' => $this->input->post('phone'),
				);

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}

				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					// Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData))
					{

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp)
						{
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

				// check to see if we are updating the user
				if ($this->ion_auth->update($user->id, $data))
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					$this->redirectUser();

				}
				else
				{
					// redirect them back to the admin page if admin, or to the base url if non admin
					$this->session->set_flashdata('message', $this->ion_auth->errors());
					$this->redirectUser();

				}

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page('auth/edit_user', $this->data);
	}

	/**
	 * Create a new group
	 */
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'trim|required|alpha_dash');

		if ($this->form_validation->run() === TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if ($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_group', $this->data);
		}
	}

	/**
	 * Edit a group
	 *
	 * @param int|string $id
	 */
	public function edit_group($id)
	{
		// bail if no group id given
		if (!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if ($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('auth/edit_group', $this->data);
	}

	/**
	 * @return array A CSRF key-value pair
	 */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce(){
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));

		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')){
			return TRUE;
		}
			return FALSE;
	}

	/**
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	public function _render_page($view, $data = NULL, $returnhtml = FALSE)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data : $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		// This will return html on 3rd argument being true
		if ($returnhtml)
		{
			return $view_html;
		}
	}

	public function forgot_password_user(){
      $this->form_validation->set_rules('email', 'Email Address', 'required');
      if ($this->form_validation->run() == false) {
        //setup the input
        $this->data['email'] = array('name'    => 'email',
                       'id'      => 'email',
                      );
        //set any errors and display the form
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $this->load->view('auth/forgot_password', $this->data);
      }
      else {
        //run the forgotten password method to email an activation code to the user
        $forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

        if ($forgotten) { //if there were no errors
          // $this->session->set_flashdata('message', $this->ion_auth->messages());
      		$this->session->set_flashdata('flashSuccess', 'Password Reset link sent your email. Please check ');
      		redirect("forgot", 'refresh'); //we should display a confirmation page here instead of the login page
        }
        else {
    		$this->session->set_flashdata('flashError', 'Email not exits. Please enter register email id or contact us');
          // $this->session->set_flashdata('message', $this->ion_auth->errors());
          	redirect("forgot", 'refresh');
        }
      }
    }

    public function forgot_user_email(){
		$this->load->view('auth/forgot_password');
    }

    public function forgot_username_password_otp_new(){

    	$emailId  = $_POST['emailId'];
		$this->db->or_where('username',$emailId);
    	$query = $this->db->get('users');
    	
    	if ($query->num_rows() > 0) {

    		$otp = rand(1000,9999);
	        $msg = 'This is your one-time password:'.$otp;

	         	$this->db->where('mobile_number', $query->row()->username);
		    $query1 = $this->db->get('recovery_password');
		    $this->db->reset_query();
		    if ($query1->num_rows() > 0 ) {
		        $forgotData = array (
		            'otp' => $otp
		        );
	      		$this->db->where('mobile_number', $query->row()->username)->update('recovery_password', $forgotData);
		    } else {
		        $forgotData = array(
		        	'mobile_number'=> $query->row()->username,
		        	'otp'=> $otp
		        );
	     		$this->db->insert('recovery_password', $forgotData);
		 	}
		 	$this->_send_otp_regsiter_email($query->row()->username, $msg);
		 	echo $query->row()->username;
    	}else{
    		echo 0;
    	}
    }

    public function register_with_otp_through_email(){

    	$emailId  = $_POST['emailId'];
    	$this->db->where('username',$emailId);
    	$this->db->where('group_id',$_POST['group_id']);
    	$query = $this->db->get('users')->row();
    	if (empty($query)) {
    		$otp = rand(100000,999999);
	        $msg = 'This is your one-time password:'.$otp;

	        $this->db->where('email_id', $emailId);
		    $query1 = $this->db->get('client_register_otp');
		    $this->db->reset_query();
		    if ($query1->num_rows() > 0 ) {
		        $registerData = array (
		            'otp' => $otp
		        );
	      		$this->db->where('email_id', $emailId)->update('client_register_otp', $registerData);
		    } else {
		        $registerData = array(
		        	'email_id'=> $emailId,
		        	'otp'=> $otp
		        );
	     		$this->db->insert('client_register_otp', $registerData);
		 	}

		 	$this->_send_client_otp_regsiter_email($emailId, $msg);
		 	echo $emailId;
    	}else{
    		echo 0;
    	}
    }


    public function _send_client_otp_regsiter_email($email, $msg){
    	$curl = curl_init();
    	curl_setopt_array($curl, array(
	      CURLOPT_URL => 'https://mypolicetv.com/otpmygroup.php',
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => "",
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 30,
	      CURLOPT_USERPWD => '',
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => "POST",
	      CURLOPT_POST => 1,
	      CURLOPT_POSTFIELDS =>'from=noreply@gomygroup.com&to='.$email.'&msg='.$msg.'&subject="Register One Time Password"',
	      CURLOPT_HTTPHEADER => array(
	        "Accept: application/json",
	        "Cache-Control: no-cache",
	        "Content-Type: application/x-www-form-urlencoded",
	        "Postman-Token: 090abdb9-b680-4492-b8b7-db81867b114e"
	      ),
	    ));

	    $response = curl_exec($curl);
	    $err = curl_error($curl);
	    curl_close($curl);
	    if ($err) {
	      // echo "cURL Error #:" . $err;
	      return 0;
	    } else {
	      return 1;
	    }

    	// header('Access-Control-Allow-Origin: *');
     //    header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token');
    	// header("Location: https://mypolicetv.com/testmail.php?from=noreply@gomygroup.com&&to=$email&&msg=$msg&&subject='Register One Time Password'");
    	// return 1;

    }

    public function _send_otp_regsiter_email($email, $msg){
    	
    	$curl = curl_init();
    	curl_setopt_array($curl, array(
	      CURLOPT_URL => 'https://mypolicetv.com/otpmygroup.php',
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => "",
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 30,
	      CURLOPT_USERPWD => '',
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => "POST",
	      CURLOPT_POST => 1,
	      CURLOPT_POSTFIELDS =>'from=noreply@gomygroup.com&to='.$email.'&msg='.$msg.'&subject="Forgot Password One Time Password"',
	      CURLOPT_HTTPHEADER => array(
	        "Accept: application/json",
	        "Cache-Control: no-cache",
	        "Content-Type: application/x-www-form-urlencoded",
	        "Postman-Token: 090abdb9-b680-4492-b8b7-db81867b114e"
	      ),
	    ));

	    $response = curl_exec($curl);
	    $err = curl_error($curl);
	    curl_close($curl);
	    if ($err) {
	      // echo "cURL Error #:" . $err;
	      return 0;
	    } else {
	      return 1;
	    }
	    
    	// $config = $this->config->item('email_config', 'ion_auth');

     //    $this->load->library('email', $config);
  
     //    $this->email->set_newline("\r\n");
     //    $this->email->from($config['smtp_user']);
     //    $this->email->to($email);
     //    $this->email->subject('Forgot One Time Password');
     //    $this->email->message($msg);
     //    if ($this->email->send()) {
     //    	return 1;
     //    } else {
     //      	return 0;
     //    }

    }

    public function verify_otp_mobile_number(){

    	$mobileNumber = $_POST['mobileNumber'];
		$otp = $_POST['otp'];
		$this->db->where('mobile_number',$mobileNumber);
		$this->db->where('otp',$otp);
		$query = $this->db->get('recovery_password');
		if ($query->num_rows() > 0) {
			echo $query->row()->id;
		}else {
			echo 0;
		}
    }

    public function verify_client_otp_mobile_number(){

    	$emailId = $_POST['emailId'];
		$otp = $_POST['otp'];
		$this->db->where('mobile_number',$emailId);
		$this->db->where('otp',$otp);
		$query = $this->db->get('recovery_password');
		if ($query->num_rows() > 0) {
			echo $query->row()->id;
		}else {
			echo 0;
		}
    }

    public function client_register_verify_otp_mobile_number(){
    	$emailId = $_POST['emailId'];
		$otp = $_POST['otp'];
		$this->db->where('email_id',$emailId);
		$this->db->where('otp',$otp);
		$query = $this->db->get('client_register_otp');
		if ($query->num_rows() > 0) {
			echo $query->row()->id;
		}else {
			echo 0;
		}
    }

    public function forgot_reset_password_new(){
		$input = $this->input->post();

		if (empty($input)) {
			redirect('home');
		}
		$data['username'] = $this->db->select('u.id, u.username')
		->from('recovery_password ufc')
		->where('ufc.mobile_number',$input['mobile_number'])
		->join('users u','ufc.mobile_number=u.username')
		->get()->row()->username;
		$data['input'] = $input;
		$this->load->view('auth/forgot_reset_password',$data);

	}

	public function forgot_reset_password_client(){
		$input = $this->input->post();

		if (empty($input)) {
			redirect('forgot-client/'.$inpu['group_name']);
		}
		$data['username'] = $this->db->select('u.id, u.username')
		->from('recovery_password ufc')
		->where('ufc.mobile_number',$input['mobile_number'])
		->join('users u','ufc.mobile_number=u.username')
		->get()->row()->username;
		$data['input'] = $input;
		$this->load->view('auth/forgot_reset_password_client',$data);

	}

	public function reset_password_success(){
		$data['success_data'] = $this->input->post();
		$this->load->view('auth/forgot_username_password_success', $data);
	}

	public function reset_password_success_client(){
		$data['success_data'] = $this->input->post();
		// echo "<pre>"; print_r($data['success_data']); die();
		$this->load->view('auth/forgot_username_password_success_client', $data);
	}

    public function forgot_reset_password(){

    	$input = $this->input->post();

    	$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			
		$this->data['new_password'] = array(
			'name' => 'new',
			'id' => 'new',
			'type' => 'password',
			'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			'class' => 'form-control',
			'placeholder' => 'New Password'
		);
		$this->data['new_password_confirm'] = array(
			'name' => 'new_confirm',
			'id' => 'new_confirm',
			'type' => 'password',
			'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
			'class' => 'form-control',
			'placeholder' => 'Confirm New Password'
		);
		$data['min_password_length'] = $this->data['min_password_length'];
		$data['new_password'] = $this->data['new_password'];
		$data['new_password_confirm'] = $this->data['new_password_confirm'];
		$data['user_name'] = $input['user_name'];
		$this->load->view('auth/reset_password', $data);
    }

    public function forgot_change_password(){
    	
		$this->db->where('mobile_number',$_POST['mobileNumber']);
		$this->db->where('otp',$_POST['otpCode']);
		$query = $this->db->get('recovery_password');
		if ($query->num_rows() > 0) {
			$user_data = array(
				'password' => $_POST['password']
			);
			$this->db->where('username',$_POST['mobileNumber']);
			$user_id = $this->db->get('users')->row()->id;
    		echo $this->ion_auth->update($user_id, $user_data);
		}else{
			echo 0;
		}
    }

    public function success(){
    	$this->load->view('auth/success');
    }

    /**
	 * Change password
	 */
	public function change_password_franchise()
	{

		$identity = $this->session->userdata('identity');
		$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

		if ($change)
		{
			//if the password was successfully changed
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			// redirect('auth/logout/');
			$this->logout();
		}
		else
		{
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect('admin_controller/change_password_head_dashboard/', 'refresh');
		}
	}
}
