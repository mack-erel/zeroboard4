<?php

if(!function_exists("ereg")){
    function ereg($pattern, $string, &$regs = array()){
        return preg_match("/" . $pattern . "/", $string, $regs);
    }
}

if(!function_exists("eregi")){
    function eregi($pattern, $string, &$regs = array()){
        return preg_match("/" . $pattern . "/i", $string, $regs);
    }
}

if(!function_exists("ereg_replace")){
    function ereg_replace($pattern, $replacement, $string){
        return preg_replace("/".$pattern."/", $replacement, $string);
    }
}

if(!function_exists("eregi_replace")){
    function eregi_replace($pattern, $replacement, $string){
        return preg_replace("/".$pattern."/i", $replacement, $string);
    }
}

if(!function_exists("split")){
    function split($pattern, $string){
        return explode($pattern, $string);
    }
}