<?php namespace App\Extensions\Html;

class FormBuilder extends \Illuminate\Html\FormBuilder
{
	public function BSGroup($options = array()) {
		$options['class'] = isset($options['bclass']) ? $options['bclass'] : "";
		return "<div class='form-group ".$options['class']."'>";
	}

	public function BSEndGroup() {
		return "</div>";
	}

	public function BSFile($name, $value, $src) {
		$text = $this->file($name,['id' => $name, 'class' => 'profile_picture_uploader', 'style' => 'display:none']);
		$text .= "<div class='thumbnail profile_picture_upload_link'>";
		$text .= "<img src='$src'>";
		$text .= "</div>";
		return $text;
	}

	public function BSStatic($value, $options = array()) {
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

	public function BSPassword($name, $options = array()) {
		$bootstrap_class = "form-control";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;
		$text = "<div class='".$options['bclass']."'>";
		$text .= $this->password($name, $options);
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
		$textarea .= $this->textarea($name, $value, $options);
		$textarea .= "</div>";
		return $textarea;
	}

	public function BSMultiSelect($name, $list = array(), $options = array()) {
		$options['title'] = isset($options['title']) ? ucfirst($options['title']) : ucwords(str_replace("_"," ",$name));
		$options['class'] = isset($options['class']) ? $options['class'] : "";
		$options['multiple'] = isset($options['multiple']) && $options['multiple'] == false ? "" : "multiple";
		$options['selected'] = isset($options['selected']) && is_array($options['selected']) ? $options['selected'] : [];
		$options['selected_text'] = isset($options['selected_text']) ? $options['selected_text'] : false;
		$options['data-size'] = isset($options['data-size']) ? $options['data-size'] : 'auto';
		$options['search'] = isset($options['search']) ? $options['search'] == true ? "true" : "false" : "true";
		$options['value'] = isset($options['value']) ? $options['value'] : '';
		$options['label'] = isset($options['label']) ? $options['label'] : '';
		$options['selected'] = isset($options['selected']) ? $options['selected'] : array();

		$multi = "<select id='".$name."' name='".$name."' class='selectpicker ".$options['class']."' ".$options['multiple']." title='".$options['title']."'";
		
		if ($options['selected_text']) {
			$multi .= "data-count-selected-text='".$options['selected_text']."' width='100px' data-size='".$options['data-size']."' data-selected-text-format='count>0'";
		}

		$multi .= "data-live-search='".$options['search']."'>";

		foreach ($list as $item) {
			$label = "";

			if (in_array($item->{$options['value']},$options['selected'])) {
				$multi .= "<option selected value=".$item->{$options['value']}.">";
			}
			else {
				$multi .= "<option value=".$item->{$options['value']}.">";
			}

			if (is_array($options['label'])) {
				foreach ($options['label'] as $part) {
					$label .= $part[0] == "!" ? $item->{substr($part,1)} : $part;
				}
			}
			else {
				$label .= $options['label'][0] == "!" ? $item->{substr($options['label'],1)} : $options['label'];
			}

			$multi .= trim($label);
			$multi .= "</option>";
		}
		
		$multi .= "</select>";

		return $multi;
	}

	public function BSSelect($name, $collection = array(), $selected = null, $options = array()) {
		$bootstrap_class = "form-control";
		$options['bclass'] = isset($options['bclass']) ? $options['bclass'] : "";
		$options['class'] = isset($options['class']) ? $options['class']." ".$bootstrap_class : $bootstrap_class;

		$list = array('' => '-');
		
		if (isset($options['key']) && $options['value']) {
			foreach ($collection as $item) {

				$key = $item->{$options['key']};
				$label = "";

				if (!is_array($options['value'])) $options['value'] = [$options['value']];

				foreach ($options['value'] as $part) {
					if ($part[0] == "!") {
						$temp = substr($part,1);
						$exploded = explode(".",$temp);
						for ($i=0; $i<count($exploded); $i++) {
							$value = $i == 0 ? $item->{$exploded[$i]} : $value->{$exploded[$i]};
						}
					}
					else {
						$value = $part;
					}

					$label .= $value;
				}

				$list[$key] = $label;
			}

			unset($options['key']);
			unset($options['value']);
		}
		else {
			$list = $list+$collection;
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

	public function customItem($route, $icon, $label, $show = false) {
		$add['label'] = $label;
		$add['link'] = $route;
		$add['icon'] = "<i class='".$icon."'></i>";
		$add['show'] = $show;
		return $add;
	}

	public function addItem($route, $label = null, $show = false) {
		$add['label'] = is_null($label) ? "Add" : $label;
		$add['link'] = $route;
		$add['icon'] = "<i class='fa fa-plus'></i>";
		$add['show'] = $show;
		return $add;
	}

	public function editItem($route, $label = null, $show = false) {
		$edit['label'] = is_null($label) ? "Edit" : $label;
		$edit['link'] = $route;
		$edit['icon'] = "<i class='fa fa-pencil-square-o'></i>";
		$edit['show'] = $show;
		return $edit;
	}

	public function dropZone() {
		$dz = "<div id='dZUpload' class='dropzone'>";
		$dz .= "<div class='dz-message needsclick'>";
		$dz .= "Drop files here or click to upload.<br>";
		$dz .= "</div>";
		$dz .= "</div>";
		return $dz;
	}
}