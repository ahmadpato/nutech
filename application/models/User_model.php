<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $_table = "users";

    public $id;
    public $fullname;
    public $email;
    public $phone_number;
    public $address;

    public function rules()
    {
        return [
            ['field' => 'fullname',
            'label' => 'fullname',
            'rules' => 'required'],

            ['field' => 'email',
            'label' => 'email',
            'rules' => 'required'],

             ['field' => 'phone_number',
            'label' => 'phone_number',
            'rules' => 'required'],

             ['field' => 'address',
            'label' => 'address',
            'rules' => 'required']
            
        ];
    }

    public function getAll()
    {
        return $this->db->get($this->_table)->result();
    }
    
    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["id" => $id])->row();
    }

    public function save()
    {
        $post = $this->input->post();
        $this->id = uniqid();
        $this->fullname = $post["fullname"];
        $this->email = $post["email"];
        $this->phone_number = $post["phone_number"];
        $this->address = $post["address"];
        
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();
        $this->fullname = $post["fullname"];
        $this->email = $post["email"];
        $this->phone_number = $post["phone_number"];
        $this->address = $post["address"];
        
        $this->db->update($this->_table, $this, array('id' => $post['id']));
    }

    public function delete($id)
    {
        return $this->db->delete($this->_table, array("id" => $id));
    }
    
}
