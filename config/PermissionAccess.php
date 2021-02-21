<?php

class PermissionAccess
{
    /* Manage User Permissions */
    public static $ViewUser = "view users";
    public static $AddUser = "add users";
    public static $EditUser = "edit users";
    public static $DeleteUser = "delete users";

    public static function getAccessList(){
        return array_values(get_class_vars(self::class));
    }
}