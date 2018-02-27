<?php
class Login extends CI_Controller {

        public function index() {

            $this->load->helper('url');
            $this->load->helper('cookie');
            $this->load->library('encryption');
            $this->load->library('session');

            if ($this->input->get('exit') == 'yes') {
                delete_cookie('logged');
                delete_cookie('username');
                redirect(base_url());
            }

            $pass = $this->input->post('pass');
            $name = $this->input->post('name');

            if (!empty($pass) && !empty($name)) {
                $this->load->database();
                $this->load->model('notes');
                if ($row = $this->notes->get_pass_and_name($pass, $name)) {
                    $pass_db = $row->password;
                    $name_db = $row->name;
                    if ($pass == $pass_db && $name == $name_db){
                        set_cookie('logged', TRUE, '3600');
                        $username_cript = $this->encryption->encrypt($name_db);
                        set_cookie('username', $username_cript, '3600');
                        redirect(base_url());
                    }

                } else {
                    $this->session->error = "Неверный пользователь или пароль!";
                    $this->session->mark_as_flash('error');
                    redirect(base_url());
                }

            } else {
                $this->session->error = "Нельзя оставлять пустые поля!";
                $this->session->mark_as_flash('error');
                redirect(base_url());
            }

            $this->load->view('footer');

        }

}