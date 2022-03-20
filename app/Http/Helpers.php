<?php

use App\Models\Company;
use Carbon\Carbon;

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
            $perms = $user->role()->first()->permissions;
            $permissions = json_decode($perms, true);

            if (isset($permissions[$route][$action]) && $permissions[$route][$action] == true) {
                return true;
            } else {
                return false;
            }
        }

    }

}

if(!function_exists('exchange_rates')){
    /**
     * @param $currency
     * @param $type
     * @return mixed
     */
    function exchange_rates($currency, $type = "satis")
    {
        $URL = json_decode(file_get_contents('https://api.genelpara.com/embed/para-birimleri.json'), true);
        return $URL[$currency][$type];
    }
}

if(!function_exists('currency_rates')){
    /**
     * @param $code
     * @return array
     */
    function currency_rates($code)
    {
        $xmlString = file_get_contents('https://www.tcmb.gov.tr/kurlar/today.xml', true);

        $json = simplexml_load_string($xmlString);
        $json = json_encode($xmlString);
        $array = json_decode($json,true);
        $array = str_replace(array("\n", "\r", "\t"), '', $array);
        $array = trim(str_replace('"', "'", $array));
        $array = simplexml_load_string($array);

        return [
            'USD' => [
                'name' => $array->Currency[0]->Name,
                'selling' => (float)$array->Currency[0]->ForexSelling,
                'buying' => (float)$array->Currency[0]->ForexBuying,
            ],
            'EUR' => [
                'name' => $array->Currency[3]->Name,
                'selling' => (float)$array->Currency[3]->ForexSelling,
                'buying' => (float)$array->Currency[3]->ForexBuying,
            ],
            'GBP' => [
                'name' => $array->Currency[4]->Name,
                'selling' => (float)$array->Currency[4]->ForexSelling,
                'buying' => (float)$array->Currency[4]->ForexBuying,
            ],

        ][$code];
    }
}

if(!function_exists('get_company_info')){
    /**
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    function get_company_info()
    {
        return Company::where('id', session()->get('company_id'))->first();
    }
}

if(!function_exists('toggle_menu')){
    /**
     * @param $gate
     * @return string
     */
    function toggle_menu($gate)
    {
        return Request::is($gate."/*") ? 'true' : 'false';
    }
}

if (!function_exists('dateFormat')){
    /**
     * @param $value
     * @return string
     */
    function dateFormat($value, $format = 'd/m/Y'): string
    {
        return Carbon::parse($value)->format($format);
    }
}

if (!function_exists('phoneFormat')){
    /**
     * @param $value
     * @return string
     */
    function phoneFormat($value): string
    {
        return "0 (".substr($value, 0, 3) . ') ' . substr($value, 3, 3) . ' ' . substr($value, 6, 4);
    }
}

if(!function_exists('defaultImage')){
    /**
     * @param $value
     * @return string
     */
    function defaultImage(): string
    {
        return asset('assets/default.png');
    }
}
