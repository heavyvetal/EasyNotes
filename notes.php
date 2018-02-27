<?php
class Notes extends CI_Model{

    /* Home controller */
    public function get_notes($username) {
        $query = $this->db->query("SELECT * FROM notes n INNER JOIN users u ON n.user_id=u.id WHERE u.name='$username'");
        $notes = $query->result_array();
        $rows = $query->num_rows();
        $group_notes = array('notes' => $notes, 'rows' => $rows);
        return $group_notes;
    }

    public function delete($note_id) {
        $this->db->where('note_id', $note_id);
        $this->db->delete('notes');
    }

    /* Note controller */
    public function get_user_id($username) {
        $query = $this->db->query("SELECT id FROM users WHERE name='$username'");
        return $row = $query->row();
    }

    public function get_max_note_id() {
        $query = $this->db->query("SELECT MAX(note_id) AS max_id FROM notes");
        return $row = $query->row();
    }

    public function get_note($note_id) {
        $query = $this->db->query("SELECT * FROM notes WHERE note_id='$note_id'");
        return $row = $query->row();
    }

    public function update_note($note_id, $new_title, $new_text) {
        $this->db->query("UPDATE notes SET text=".$this->db->escape($new_text).", title=".$this->db->escape($new_title)." WHERE note_id=".$this->db->escape($note_id));
    }

    public function add_note($note_id, $user_id, $new_title, $new_text) {
        $this->db->query("INSERT INTO notes SET note_id=".$this->db->escape($note_id).", user_id=".$this->db->escape($user_id).", text=".$this->db->escape($new_text).", title=".$this->db->escape($new_title));
    }

    /* Login controller*/
    public function get_pass_and_name($pass, $name) {
        $query = $this->db->query("SELECT * FROM users WHERE password=".$this->db->escape($pass)." AND name=".$this->db->escape($name));
        return $row = $query->row();
    }
}