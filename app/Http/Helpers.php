<?php

if (!function_exists('get_gravatar')) {

    /**
     * @param $email
     * @param int $s
     * @param string $d
     * @param string $r
     * @param false $img
     * @param array $atts
     * @return string
     */
    function get_gravatar($email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

}

if (!function_exists('profile_photo')) {

    /**
     * @param array $name
     * @param string $text_color
     * @param string $bg_color
     * @return string
     */
    function profile_photo(array $name, string $text_color = "7F9CF5", string $bg_color = "EBF4FF")
    {
        $text_color = str_replace('#', '', $text_color);
        $bg_color = str_replace('#', '', $bg_color);
        $name = str_replace(' ', '+', $name);
        return 'https://ui-avatars.com/api/?name=' . $name . '&color=' . $text_color . '&background=' . $bg_color . '';
    }

}

if (!function_exists('permission_check')) {

    /**
     * @param $route
     * @param $action
     * @return bool
     */
    function permission_check($route, $action)
    {
        $user = auth()->user();

        if ($user->role_id === 1) {
            return true;
        } else {
            if ($user->permissions != "null") {
                $permissions = json_decode($user->permissions, true);
            } else {
                $perms = $user->role()->first()->permissions;
                $permissions = json_decode($perms, true);
            }
            if (isset($permissions[$route][$action]) && $permissions[$route][$action] == "true") {
                return true;
            } else {
                return false;
            }
        }

    }

}
