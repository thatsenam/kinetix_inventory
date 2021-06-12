@extends('layouts.admin.app_pos')


<?php

$user_id = Auth::id();



$get_license = DB::table('users')->where('id', $user_id)->first();

$license = '';




?>



