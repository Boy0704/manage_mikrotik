<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Device extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Device_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'device/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'device/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'device/index.html';
            $config['first_url'] = base_url() . 'device/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Device_model->total_rows($q);
        $device = $this->Device_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'device_data' => $device,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'judul_page' => 'device/device_list',
            'konten' => 'device/device_list',
        );
        $this->load->view('v_index', $data);
    }

    public function read($id) 
    {
        $row = $this->Device_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_device' => $row->id_device,
		'nama_device' => $row->nama_device,
		'ip' => $row->ip,
		'port' => $row->port,
		'username' => $row->username,
		'password' => $row->password,
	    );
            $this->load->view('device/device_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('device'));
        }
    }

    public function create() 
    {
        $data = array(
            'judul_page' => 'device/device_form',
            'konten' => 'device/device_form',
            'button' => 'Create',
            'action' => site_url('device/create_action'),
	    'id_device' => set_value('id_device'),
	    'nama_device' => set_value('nama_device'),
	    'ip' => set_value('ip'),
	    'port' => set_value('port'),
	    'username' => set_value('username'),
	    'password' => set_value('password'),
	);
        $this->load->view('v_index', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nama_device' => $this->input->post('nama_device',TRUE),
		'ip' => $this->input->post('ip',TRUE),
		'port' => $this->input->post('port',TRUE),
		'username' => $this->input->post('username',TRUE),
		'password' => $this->input->post('password',TRUE),
	    );

            $this->Device_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('device'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Device_model->get_by_id($id);

        if ($row) {
            $data = array(
                'judul_page' => 'device/device_form',
                'konten' => 'device/device_form',
                'button' => 'Update',
                'action' => site_url('device/update_action'),
		'id_device' => set_value('id_device', $row->id_device),
		'nama_device' => set_value('nama_device', $row->nama_device),
		'ip' => set_value('ip', $row->ip),
		'port' => set_value('port', $row->port),
		'username' => set_value('username', $row->username),
		'password' => set_value('password', $row->password),
	    );
            $this->load->view('v_index', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('device'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_device', TRUE));
        } else {
            $data = array(
		'nama_device' => $this->input->post('nama_device',TRUE),
		'ip' => $this->input->post('ip',TRUE),
		'port' => $this->input->post('port',TRUE),
		'username' => $this->input->post('username',TRUE),
		'password' => $this->input->post('password',TRUE),
	    );

            $this->Device_model->update($this->input->post('id_device', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('device'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Device_model->get_by_id($id);

        if ($row) {
            $this->Device_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('device'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('device'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nama_device', 'nama device', 'trim|required');
	$this->form_validation->set_rules('ip', 'ip', 'trim|required');
	$this->form_validation->set_rules('port', 'port', 'trim|required');
	$this->form_validation->set_rules('username', 'username', 'trim|required');
	$this->form_validation->set_rules('password', 'password', 'trim|required');

	$this->form_validation->set_rules('id_device', 'id_device', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Device.php */
/* Location: ./application/controllers/Device.php */
/* Please DO NOT modify this information : */
/* Generated by Boy Kurniawan 2020-08-19 18:46:49 */
/* https://jualkoding.com */