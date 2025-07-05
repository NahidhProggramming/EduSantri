<?php

if (!function_exists('generate_captcha')) {
    function generate_captcha() {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        session(['captcha_answer' => $num1 + $num2]);
        return "$num1 + $num2 = ?";
    }
}

if (!function_exists('validate_captcha')) {
    function validate_captcha($answer) {
        return session('captcha_answer') == $answer;
    }
}
