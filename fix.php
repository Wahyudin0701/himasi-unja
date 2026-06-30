<?php
$file = 'C:/laragon/www/himasi-management/resources/views/super_admin/members/create.blade.php';
$content = file_get_contents($file);
$parts = explode("@extends('layouts.dashboard')", $content);
$fixed = "@extends('layouts.dashboard')" . end($parts);
file_put_contents($file, $fixed);
echo "Fixed!\n";
