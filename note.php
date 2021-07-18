<?php
class Note extends CI_Controller {

    public function edit() {

        $this->load->database();
        $this->load->library('session');
        $this->load->library('encryption');
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->model('notes');

        $data['logged'] = get_cookie('logged');
        $data['username'] = $this->encryption->decrypt(get_cookie('username'));
        $username = $data['username'];
        $row = $this->notes->get_user_id($username);
        $user_id = $row->id;
        $args = func_get_args();
        if (isset($args[0])) $note_id = $args[0];

        /* Saving */
        $save = $this->input->post('save');
        
        if (!empty($save)) {
            $new_text = $this->input->post('text');
            $new_title= $this->input->post('title');
            
            if (!empty($new_text) && !empty($new_title)) {
                if ($note_id != 'new') {
                    $this->notes->update_note($note_id, $new_title, $new_text);
                    $data['error'] = "Запись успешно сохранена";
                } else {
                    $row = $this->notes->get_max_note_id($username);
                    $note_id = $row->max_id + 1;
                    $this->notes->add_note($note_id, $user_id, $new_title, $new_text);
                    redirect(base_url("note/edit/".$note_id));
                }

            } else {
                $data['error'] = "Нельзя оставлять пустые поля";
            }

        }

        /* New note */
        if ($note_id == 'new') {
            $data['note_id'] = $note_id;
            $data['note_title'] = '';
            $data['note_text'] = '';
        }
        /* Usual note */
        else {
            $row = $this->notes->get_note($note_id);
            $data['note_id'] = $row->note_id;
            $data['note_title'] = $row->title;
            $data['note_text'] = $row->text;
        }

        $this->load->view('header', $data);
        $this->load->view('note', $data);
        $this->load->view('footer');
    }

}
