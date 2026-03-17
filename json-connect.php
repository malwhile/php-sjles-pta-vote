<?php

class JsonConnect {
    private $JSON_DIRPATH = "./jsons";
    private $JSON_VOTE_FILENAME = "/vote-%s.json";
    private $MEMBER_CSV = "./membership/membership.csv";
    
    private $json_filepath = "";

    public function __construct($type) {
        if(!is_dir($this->JSON_DIRPATH)) {
            mkdir($this->JSON_DIRPATH);
        }

        if($type == "vote") {
            $this->json_filepath = $this->JSON_DIRPATH . $this->JSON_VOTE_FILENAME;
        }
    }

    public function create_vote_json($vote_name) {
        $vote_json = array(
            "name" => $vote_name,
            "open" => true,
            "member_emails" => array(),
            "member_yes" => 0,
            "member_no" => 0,
            "non_member_emails" => array(),
            "non_member_yes" => 0,
            "non_member_no" => 0,
        );
        
        $vote_filename = sprintf($this->json_filepath, $vote_name);
        
        if(file_exists($vote_filename)) {
            return true;
        }

        file_put_contents($vote_filename, json_encode($vote_json));
    }

    public function get_vote_json($vote_name) {
        $vote_filename = sprintf($this->json_filepath, $vote_name);

        if(!file_exists($vote_filename)) {
            create_vote_json($vote_name);
        } 
        
        $vote_json = json_decode(file_get_contents($vote_filename));

        return $vote_json;
    }

    public function set_vote_json($vote_name, $user_email, $vote) {
        $vote_filename = sprintf($this->json_filepath, $vote_name);
        $vote_json = null;

        if(!file_exists($vote_filename)) {
            return 1;
        }

        $vote_json = json_decode(file_get_contents($vote_filename));

        $is_member = false;
        if( exec('grep --count --ignore-case '.escapeshellarg($user_email).' '.$this->MEMBER_CSV)) {
            $is_member = true;
        }

        $emails = array();
        $yes_vote_count = 0;
        $no_vote_count = 0;

        if($is_member) {
            $emails = $vote_json->member_emails;
            $yes_vote_count = $vote_json->member_yes;
            $no_vote_count = $vote_json->member_no;
        } else {
            $emails = $vote_json->non_member_emails;
            $yes_vote_count = $vote_json->non_member_yes;
            $no_vote_count = $vote_json->non_member_no;
        }

        if(in_array($user_email, $emails)) {
            return $is_member;
        }

        array_push($emails, $user_email);
        if($vote) {
            $yes_vote_count += 1;
        } else {
            $no_vote_count += 1;
        }

        if($is_member) {
            $vote_json->member_emails = $emails;
            $vote_json->member_yes = $yes_vote_count;
            $vote_json->member_no = $no_vote_count;
        } else {
            $vote_json->non_member_emails = $emails;
            $vote_json->non_member_yes = $yes_vote_count;
            $vote_json->non_member_no = $no_vote_count;
        }

        file_put_contents($vote_filename, json_encode($vote_json));

        return $is_member;
    }
}

?>