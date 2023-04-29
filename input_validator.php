<?php

// Validate inputs for post title, author, and description.
// return an error array once all checks are done

class InputValidator {
    private $data;
    private $errors = [];
    private static $fields = ['title', 'author', 'body'];

    public function __construct($post_data) {
        $this->data = $post_data;
    }

    public function validateForm() {
        foreach(self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error("$field is not present in data");
                return;
            }
        }
        $this->validateTitle();
        $this->validateAuthor();
        return $this->errors;
    }

    private function validateTitle() {
        $val = trim($this->data['title']);

        if (empty($val)) {
            $this->addError('title', 'Title cannot be empty');
        }
        // TODO: cannot have the same title
    }

    private function validateAuthor() {
        $val = trim($this->data['author']);

        if (empty($val)) {
            $this->addError('author', 'Author cannot be empty');
        }
    }

    private function addError($key, $val) {
        $this->errors[$key] = $val;
    }
}

?>