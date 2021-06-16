<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

class Comoditys extends CI_Controller
{
    public $id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("comodity_model");
        $this->load->library('form_validation');
        $this->load->model("comodity_model");
    }

    public function index()
    {
        $data["comoditys"] = $this->comodity_model->getAll();
        $comodity = $this->comodity_model;
        $validation = $this->form_validation;
        $validation->set_rules($comodity->rules());

        $this->form_validation->set_rules('jenis', 'jenis', 'is_unique[comoditys.jenis]');

        if ($validation->run()) {
            $comodity->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
            redirect(site_url('admin/comoditys'));
        } 

        $this->load->view("admin/comodity/list", $data);
    }

    public function add()
    {
        $comodity = $this->comodity_model;
        $validation = $this->form_validation;
        $validation->set_rules($comodity->rules());

        if ($validation->run()) {
            $comodity->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $this->load->view("admin/comodity/new_form");
    }

    public function edit($id = null)
    {
        if (!isset($id)) redirect('admin/comoditys');
       
        $comodity = $this->comodity_model;
        $validation = $this->form_validation;
        $validation->set_rules($comodity->rules());

        if ($validation->run()) {
            $comodity->update();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $data["comoditys"] = $comodity->getById($id);
        if (!$data["comoditys"]) show_404();
        
        $this->load->view("admin/comodity/edit_form", $data);
    }

    public function delete($id=null)
    {
        if (!isset($id)) show_404();
        
        if ($this->comodity_model->delete($id)) {
            redirect(site_url('admin/comoditys'));
        }
    }

    public function report(){

        $this->load->model('comodity_model');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Jenis');
        $sheet->setCellValue('C1', 'Luas');
        
        $comodity = $this->comodity_model->getAll();
        $no = 1;
        $x = 2;
        foreach($comodity as $row)
        {
            $sheet->setCellValue('A'.$x, $no++);
            $sheet->setCellValue('B'.$x, $row->jenis);
            $sheet->setCellValue('C'.$x, $row->luas);
            $x++;
        }
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-comodity';
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
