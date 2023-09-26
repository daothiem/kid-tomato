<?php

namespace App\Helper;

class StringHelper
{
    public static function getSelectOption($options ,$selected = '', $firstOption="Vui lòng chọn", $firstSelected = true, $addFirstOption=true){
        $html = '';
        if ($addFirstOption) {
            $html = '<option value="" ' .($firstSelected ? 'selected' : ''). '>'. $firstOption. '</option>';
        }

        foreach ($options as $option) {
            $html .= '<option value="' . $option->id . '"' . ((is_array($selected) ? in_array($option->id, $selected) : $selected == $option->id) ? 'selected' : '') . '>' . $option->title . '</option>';
        }
        return $html;
    }
}
