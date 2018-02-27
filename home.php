<?php
class Home extends CI_Controller {

    public function load(){
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('notes');
        $this->load->library('session');
    }

    public function index(){
        $this->load();
        $this->load->library('encryption');
        $this->load->helper('cookie');

        $data['logged'] = get_cookie('logged');
        $data['username'] = $this->encryption->decrypt(get_cookie('username'));
        $username = $data['username'];

        if (!empty($username)) {
            $group_notes = $this->notes->get_notes($username);
            $data['notes'] = $group_notes['notes'];
            $data['rows'] = $group_notes['rows'];
        }

        if (isset($_SESSION['error'])) {
            $data['error'] = $this->session->error;
        }

        $this->load->view('header', $data);
        $this->load->view('home', $data);
        $this->load->view('footer', $data);
    }

    public function delete($note_id){
        $this->load();
        $this->notes->delete($note_id);
        $this->session->error = "Запись удалена";
        $this->session->mark_as_flash('error');
        redirect(base_url());
    }

}