<?php
    session_start();

    function csrf_token($form = 'default'){
        if(!isset($_SESSION['_csrf']) || !is_array($_SESSION['_csrf'])){
            $_SESSION['_csrf'] = [];
        }

        if(
            empty($_SESSION['_csrf'][$form]['token']) ||
            !is_string($_SESSION['_csrf'][$form]['token']) ||
            strlen($_SESSION['_csrf'][$form]['token']) !== 64
        ){
            $_SESSION['_csrf'][$form] = [
                'token' => bin2hex(random_bytes(32)),
                'created_at' => time()
            ];
        }

        return $_SESSION['_csrf'][$form]['token'];
    }

    function verify_csrf_token($token, $form = 'default', $ttlSeconds = 7200){
        if(
            empty($token) ||
            !isset($_SESSION['_csrf'][$form]['token']) ||
            !is_string($_SESSION['_csrf'][$form]['token'])
        ){
            return false;
        }

        $createdAt = (int)($_SESSION['_csrf'][$form]['created_at'] ?? 0);
        if($createdAt <= 0 || (time() - $createdAt) > $ttlSeconds){
            return false;
        }

        return hash_equals($_SESSION['_csrf'][$form]['token'], (string)$token);
    }

    //flash message helper
    function flash($name = '', $message = '', $class = 'alert alert-success'){
        if(!empty($name)){
            if(!empty($message) && empty($_SESSION[$name])){
                if(!empty($_SESSION[$name])){
                    unset($_SESSION[$name]);
                }

                if(!empty($_SESSION[$name . '_class'])){
                    unset($_SESSION[$name]);
                }

                $_SESSION[$name] = $message;
                $_SESSION[$name . '_class'] = $class;
            }elseif(empty($message) && !empty($_SESSION[$name])){
                $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : ''; 
                echo '<div class="'. $class .'" id="msg-flash">'. $_SESSION[$name]. '</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name . '_class']);
            }
        }
    }

    function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        }else{
            return false;
        }
    }