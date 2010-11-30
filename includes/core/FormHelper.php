<?php
class FormHelper {
	public static function createSelect($label, $name, $options, $selected='', $default='', $input_parameters='') {
		$string = '';
		$selected = ($selected=='')?$default:$selected;
		$string .= "<label for=\"$name\"/>$label</label>";
		$string .= "<select name=\"$name\" id=\"$estudios\" $input_parameters>";
		foreach($options as $value=>$descr){
			if ($value!=$selected) {
				$string .="<option value=\"$value\">$descr</option>";
			} else {
				$string .="<option value=\"$value\" selected='selected'>$descr</option>";
			}
		}
		$string .= "</select>";
		return $string;
	}
	public static function createRadio($label, $name, $options, $checked='', $default='') {
		$string = '';
		$checked = ($checked=='')?$default:$checked;
		$string .= "<label for=\"$name\"/>$label</label>";
		foreach($options as $value=>$descr){
			if ($value!=$checked) {
				$string .="<input type=\"radio\" name=\"$name\" value=\"$value\"/>$descr ";
			} else {
				$string .="<input type=\"radio\" name=\"$name\" value=\"$value\" checked/>$descr ";
			}
		}
		$string .= "</select>";
		return $string;
	}
}