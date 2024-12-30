<?php
class Validator {
    private $errors = [];
    
    public function validate($data, $rules) {
        foreach ($rules as $field => $rule) {
            if (!isset($data[$field])) {
                if (strpos($rule, 'required') !== false) {
                    $this->errors[$field] = "{$field}是必填项";
                }
                continue;
            }
            
            $value = $data[$field];
            
            foreach (explode('|', $rule) as $r) {
                if ($r === 'required' && empty($value)) {
                    $this->errors[$field] = "{$field}不能为空";
                }
                
                if (strpos($r, 'min:') === 0) {
                    $min = substr($r, 4);
                    if (strlen($value) < $min) {
                        $this->errors[$field] = "{$field}长度不能小于{$min}";
                    }
                }
                
                if (strpos($r, 'max:') === 0) {
                    $max = substr($r, 4);
                    if (strlen($value) > $max) {
                        $this->errors[$field] = "{$field}长度不能大��{$max}";
                    }
                }
                
                if ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = "请输入有效的邮箱地址";
                }
                
                if ($r === 'numeric' && !is_numeric($value)) {
                    $this->errors[$field] = "{$field}必须是数字";
                }
            }
        }
        
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
} 