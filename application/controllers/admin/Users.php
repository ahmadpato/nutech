<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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

    public function report(){

        $this->load->model('user_model');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'No Handphone');
        $sheet->setCellValue('E1', 'Alamat');
        
        $product = $this->user_model->getAll();
        $no = 1;
        $x = 2;
        foreach($product as $row)
        {
            $sheet->setCellValue('A'.$x, $no++);
            $sheet->setCellValue('B'.$x, $row->fullname);
            $sheet->setCellValue('C'.$x, $row->email);
            $sheet->setCellValue('D'.$x, $row->phone_number);
            $sheet->setCellValue('E'.$x, $row->address);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-users';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
