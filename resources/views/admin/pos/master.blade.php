@extends('layouts.admin.app_pos')
    
    
<?php 

$user_id = Auth::id();



$get_license = DB::table('users')->where('id', $user_id)->first();

$license = '';

/*

if(!is_null($get_license)){
    
    $license = $get_license->license;
    
}

if($license == ''){
?>
    
    @section('page-js-script')

    <script type="text/javascript">
        alert("Sorry!! You Don't Have License to Access This Feature.");
        window.location.replace("{{Request::root()}}/dashboard");
    </script>
    @stop
    
<?php } */


?>



