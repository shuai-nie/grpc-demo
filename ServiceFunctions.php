<?php

class ServiceFunctions
{
    public static function getDisplayName($first_name, $last_name) {
        $name = '';
        $name .= strtoupper(substr($first_name, 0, 1));
        $name .= ' '.ucfirst($last_name);
        return $name;
    }

    public static function countWords($paragraph) {
        $words = preg_split('/[.,!?;]+/', $paragraph);
        return count($words);
    }
}