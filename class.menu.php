<?php

class menu {

    public static $menu_option;

    

    //Fuction to put to the top of the  array
    public static function put_to_array_top(&$parent_arr, $child_arr) {
        $parent_arr[] = $child_arr;
    }

    //Fuction to put to array
    public static function put_to_array(&$parent_arr, $pk, $child_arr, $k = "") {
        foreach ($parent_arr as $id => &$value) :
            $k.="/" . ($id + 1);
            if ($pk == $k):
                $value['child'][] = $child_arr;
            endif;
            menu::put_to_array($value['child'], $pk, $child_arr, $k);
            $k = substr($k, 0, -2);
        endforeach;
    }

    // end of function to put to array
    // Function to display all the array values
    // with indentation
    public static function show_array($arr, $tier = 1, $k = "") {
        foreach ($arr as $key => $value) :
            $k.="/" . ($key + 1);
            menu::display($tier, $k, $value);
            menu::show_array($value['child'], $tier + 1, $k);
            $k = substr($k, 0, -2);
        endforeach;
    }

    public static function display($tier, $key, $value) {
        menu::$menu_option .= "<option value='" . $key . "'>" . menu::indent($tier) . $value[name] . "</option>";
    }
    
    //Function to put indentatioin
    public static function indent($tier, $str = "") {
        for ($i = 1; $i < $tier; $i++):
            $str.="----";
        endfor;
        return $str;
    }
}

?>
