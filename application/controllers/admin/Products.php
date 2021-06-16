<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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

    public function report(){

        $this->load->model('product_model');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Harga Beli');
        $sheet->setCellValue('D1', 'Harga Jual');
        $sheet->setCellValue('E1', 'Stok');
        
        $product = $this->product_model->getAll();
        $no = 1;
        $x = 2;
        foreach($product as $row)
        {
            $sheet->setCellValue('A'.$x, $no++);
            $sheet->setCellValue('B'.$x, $row->name);
            $sheet->setCellValue('C'.$x, $row->harga_beli);
            $sheet->setCellValue('D'.$x, $row->harga_jual);
            $sheet->setCellValue('E'.$x, $row->stok);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-product';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
