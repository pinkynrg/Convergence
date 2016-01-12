<?php namespace App\Extensions\Html;

class FormBuilder extends \Illuminate\Html\FormBuilder
{
	public static function BSGroup($options = array()) {
		$options['class'] = isset($options['bclass']) ? $options['bclass'] : "";
		return "<div class='form-group ".$options['class']."'>";
	}

	public static function BSEndGroup() {
		return "</div>";
	}

	public static function BSStatic($value, $options = array()) {
		$bootstrap_class = 'form-control-static';
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$static = "<div class='".$options['bclass']."'>";
		$static .= "<p class='".$bootstrap_class."'>".$value."</p>";
		$static .= "</div>";
		return $static;
	}

	public function BSLabel($name, $value, $options = array()) {
		$bootstrap_class = "control-label";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$options['class'] = isset($options['bclass']) ? $options['bclass']." ".$options['class'] : $options['class'];
		return $this->label($name, $value, $options);
	}

	public function BSText($name, $value = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$text = "<div class='".$options['bclass']."'>";
		$text .= $this->text($name, $value, $options);
		$text .= "</div>";
		return $text;
	}

	public function BSDatePicker($name, $value = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$text = "<div class='".$options['bclass']."'>";
		$text .= "<div class='input-group'>";
		$text .= $this->text($name, $value, $options);
		$text .= "<span class='input-group-addon'><i class='fa fa-calendar'></i></span>";
		$text .= "</div>";
		$text .= "</div>";
		return $text;
	}

	public function BSHidden($name, $value = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "col-xs-12";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$text = "<div class='".$options['bclass']."'>";
		$text .= $this->hidden($name, $value, $options);
		$text .= "</div>";
		return $text;
	}

	public function BSTextArea($name, $value = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$textarea = "<div class='".$options['bclass']."'>";
		$textarea .= $this->textarea($name, $value = null, $options);
		$textarea .= "</div>";
		return $textarea;
	}

	public function BSSelect($name, $list = array(), $selected = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;

		if (isset($options['key']) && isset($options['value'])) {
			$temp = $list;
			$list = array('' => '-');
			$key_path = explode('.',$options['key']);
			$value_path = explode('.',$options['value']);
			foreach ($temp as $elem) {

				$key = $elem;
				$value = $elem;

				for ($i = 0; $i < count($key_path); $i++)  {
					$subkey = $key_path[$i];
					if (isset($key->{$subkey})) {
						$key = $key[$subkey];
					}
					elseif (method_exists($key,$subkey) && ($i == count($key_path)-1)) {
						$key = $key->{$subkey}();
					}
					elseif (is_object($key[$subkey])) {
						$key = $key->{$subkey};
					}
					else {
						$key = null;
					}
				}

				for ($i = 0; $i < count($value_path); $i++)  {
					$subvalue = $value_path[$i];
					if (isset($value->{$subvalue})) {
						$value = $value->{$subvalue};
					}
					elseif (method_exists($value,$subvalue) && ($i == count($value_path)-1)) {
						$value = $value->{$subvalue}();
					}
					elseif (is_object($value->{$subvalue})) {
						$value = $value->{$subvalue};
					}
					else {
						$value = null;
					}
				}

				if (!is_null($key) && !is_null($value))
					$list[$key] = $value;

			}
		}

		$select = "<div class='".$options['bclass']."'>";
		$select .= $this->select($name, $list, $selected, $options);
		$select .= "</div>";

		return $select;
	}

	public function BSSubmit($value = null, $options = array()) {
		$bootstrap_class = "btn btn-default";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$submit = $this->submit($value, $options);
		return $submit;
	}

	public function BSButton($value = null, $options = array()) {
		$bootstrap_class = "btn btn-default";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$button = $this->button($value, $options);
		return $button;
	}

	public function addItem($route, $label = null) {
		$label = is_null($label) ? "Add" : $label;
		$add = "<a href='".$route."' class='action'>";
		$add .= " <i class='fa fa-plus'></i> ".$label;
		$add .= "</a>";
		return $add;
	}

	public function editItem($route, $label = null) {
		$label = is_null($label) ? "Edit" : $label;
		$edit = "<a href='".$route."' class='action'>";
		$edit .= "<i class='fa fa-pencil-square-o'></i> ".$label;
		$edit .= "</a>";
		return $edit;
	}

	public function deleteItem($route_name, $id, $label = null) {
		$label = is_null($label) ? "Remove" : $label;
		$delete = "<a class='action'>";
		$delete .= $this->open(array('method' => 'DELETE', 'route' => array($route_name,$id)));
		$delete .= "<button type='submit' class='nobutton'>";
		$delete .= "<i class='fa fa-trash'></i> ".$label;
 		$delete .= "</button>";
		$delete .= $this->close();
		$delete .= "</a>";
		return $delete;
	}

	public function back($link, $label = null) {
		$label = is_null($label) ? "Back" : $label;
		$back = "<a href='".$link."' class='action'>";
		$back .= "<i class='fa fa-arrow-left'></i> ".$label;
		$back .= "</a>";
		return $back;
	}
}