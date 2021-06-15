<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Trainings extends CI_Controller
{
    public $id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("training_model");
        $this->load->library('form_validation');
        $this->load->model("training_model");

		// if($this->training_model->isNotLogin()) redirect(site_url('admin/login'));
    }

    public function index()
    {
        $data["trainings"] = $this->training_model->getAll();
        $training = $this->training_model;
        $validation = $this->form_validation;
        $validation->set_rules($training->rules());

        $this->form_validation->set_rules('name', 'name', 'is_unique[trainings.name]');

        if ($validation->run()) {
            $training->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
            redirect(site_url('admin/trainings'));
        } 

        $this->load->view("admin/training/list", $data);
    }

    public function add()
    {
        $training = $this->training_model;
        $validation = $this->form_validation;
        $validation->set_rules($training->rules());

        if ($validation->run()) {
            $training->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $this->load->view("admin/training/new_form");
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('admin/trainings');
       
        $training = $this->training_model;
        $validation = $this->form_validation;
        $validation->set_rules($training->rules());

        if ($validation->run()) {
            $training->update();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $data["trainings"] = $training->getById($id);
        if (!$data["trainings"]) show_404();
        
        $this->load->view("admin/training/edit_form", $data);
    }

    public function delete($id=null)
    {
        if (!isset($id)) show_404();
        
        if ($this->training_model->delete($id)) {
            redirect(site_url('admin/trainings'));
        }
    }
}
