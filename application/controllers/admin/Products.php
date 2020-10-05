<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller
{
    public $product_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("product_model");
        $this->load->library('form_validation');
        $this->load->model("user_model");

		// if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
    }

    public function index()
    {
        $data["products"] = $this->product_model->getAll();
        $product = $this->product_model;
        $validation = $this->form_validation;
        $validation->set_rules($product->rules());

        $this->form_validation->set_rules('name', 'Name', 'is_unique[products.name]');

        $this->form_validation->set_rules('image', 'image', 'callback_image_upload'); 

        if ($validation->run()) {
            $product->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
            redirect(site_url('admin/products'));
        } 

        $this->load->view("admin/product/list", $data);
    }

    public function add()
    {
        $product = $this->product_model;
        $validation = $this->form_validation;
        $validation->set_rules($product->rules());

        // $this->form_validation->set_rules('name', 'Name', 'is_unique[products.name]');

        // $this->form_validation->set_rules('image', 'image', 'callback_image_upload'); 

        if ($validation->run()) {
            $product->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $this->load->view("admin/product/new_form");
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('admin/products');
       
        $product = $this->product_model;
        $validation = $this->form_validation;
        $validation->set_rules($product->rules());

        $this->form_validation->set_rules('image', 'image', 'callback_image_upload');

        if ($validation->run()) {
            $product->update();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $data["product"] = $product->getById($id);
        if (!$data["product"]) show_404();
        
        $this->load->view("admin/product/edit_form", $data);
    }

    public function delete($id=null)
    {
        if (!isset($id)) show_404();
        
        if ($this->product_model->delete($id)) {
            redirect(site_url('admin/products'));
        }
    }

    function image_upload()
    {   
        $config['upload_path']          = './upload/product/';
        $config['allowed_types']        = 'jpg|png';
        $config['file_name']            = $this->product_id;
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
