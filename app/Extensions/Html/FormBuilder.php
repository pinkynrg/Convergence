<?php namespace Convergence\Extensions\Html;

class FormBuilder extends \Illuminate\Html\FormBuilder
{
	public static function BSGroup() {
		return "<div class='form-group'>";
	}

	public static function BSEndGroup() {
		return "</div>";
	}

	public static function BSFiller() {
		return "<div class='col-lg-2 col-sm-2'></div>";
	}

	public function BSLabel($name, $value, $options = array()) {
		$bootstrap_class = "col-lg-2 col-sm-2 control-label";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		return $this->label($name, $value, $options);
	}

	public function BSText($name, $value = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$text = "<div class='col-lg-3 col-sm-4'>";
		$text .= $this->text($name, $value, $options);
		$text .= "</div>";
		return $text;
	}

	public function BSHidden($name, $value = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$text = "<div class='col-lg-3 col-sm-4'>";
		$text .= $this->hidden($name, $value, $options);
		$text .= "</div>";
		return $text;
	}

	public function BSTextArea($name, $value = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$textarea = "<div class='col-lg-8 col-sm-10'>";
		$textarea .= $this->textarea($name, $value = null, $options);
		$textarea .= "</div>";
		return $textarea;
	}

	public function BSSelect($name, $list = array(), $selected = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;

		if (isset($options['key']) && isset($options['value'])) {
			$temp = $list;
			$list = array();
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

		$select = "<div class='col-lg-3 col-sm-4'>";
		$select .= $this->select($name, $list, $selected, $options);
		$select .= "</div>";

		return $select;
	}

	public function BSSubmit($value = null, $options = array()) {
		$bootstrap_class = "btn btn-default";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		return $this->submit($value, $options);
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