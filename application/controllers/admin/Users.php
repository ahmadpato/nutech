<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{
    public $user_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_model");
        $this->load->library('form_validation');
        $this->load->model("user_model");

		// if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
    }

    public function index()
    {
        $data["users"] = $this->user_model->getAll();
        $user = $this->user_model;
        $validation = $this->form_validation;
        $validation->set_rules($user->rules());

        $this->form_validation->set_rules('fullname', 'fullname', 'is_unique[users.fullname]');

        if ($validation->run()) {
            $user->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
            redirect(site_url('admin/users'));
        } 

        $this->load->view("admin/user/list", $data);
    }

    public function add()
    {
        $user = $this->user_model;
        $validation = $this->form_validation;
        $validation->set_rules($user->rules());

        // $this->form_validation->set_rules('name', 'Name', 'is_unique[users.name]');

        // $this->form_validation->set_rules('image', 'image', 'callback_image_upload'); 

        if ($validation->run()) {
            $user->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $this->load->view("admin/user/new_form");
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('admin/users');
       
        $user = $this->user_model;
        $validation = $this->form_validation;
        $validation->set_rules($user->rules());

        if ($validation->run()) {
            $user->update();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $data["users"] = $user->getById($id);
        if (!$data["users"]) show_404();
        
        $this->load->view("admin/user/edit_form", $data);
    }

    public function delete($id=null)
    {
        if (!isset($id)) show_404();
        
        if ($this->user_model->delete($id)) {
            redirect(site_url('admin/users'));
        }
    }

    function image_upload()
    {   
        $config['upload_path']          = './upload/user/';
        $config['allowed_types']        = 'jpg|png';
        $config['file_name']            = $this->user_id;
        $config['overwrite']            = true;
        $config['max_size']             = 100; // 1MB

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')){
        
        $this->form_validation->set_message('image_upload', $this->upload->display_errors());
        
        return false;
        
        } else{
            $this->upload_data['file_name'] =  $this->upload->data();
            return true;
        }   
    }
}
