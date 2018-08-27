<?php
namespace F3;
use \F3\Input;
use \F3\Form;

class BS4 extends \Prefab{

	protected $form;

	public function __construct(){

		$this->form=Form::instance();

	}

	public function open(array $args=[], array $moreArgs=[]){
		return $this->form->open($args, $moreArgs);
	}

	public function close(){
		return "</form>";
	}

	public function text($name, $label=NULL, $value=NULL, array $args=[]){

		$attr=[
			'class'=>'form-control',
			'groupClass'=>'form-group',
		];

		$attr=array_merge($attr, $args);

		$error_msg='';

		if(Input::instance()->error()->exists($name)){

			$error_msg=Input::instance()->error()->msg($name);
			$attr['class'].=' is-invalid';

		}

		if(!$label) $label=ucwords(str_replace(['_', '-'], ' ', $name));

		return "<div class='{$attr['groupClass']}'>\n<label for='{$name}'>{$label}</label>\n".$this->form->text($name, $value, $attr)."\n<div class='invalid-feedback'>{$error_msg}</div>\n</div>";

	}

	public function password($name, $label=NULL, $value=NULL, array $args=[]){

		$attr=[
			'class'=>'form-control',
			'groupClass'=>'form-group',
		];

		$attr=array_merge($attr, $args);

		$error_msg='';

		if(Input::instance()->error()->exists($name)){

			$error_msg=Input::instance()->error()->msg($name);
			$attr['class'].=' is-invalid';

		}

		if(!$label) $label=ucwords(str_replace(['_', '-'], ' ', $name));

		return "<div class='{$attr['groupClass']}'>\n<label for='{$name}'>{$label}</label>\n".$this->form->password($name, $value, $attr)."\n<div class='invalid-feedback'>{$error_msg}</div>\n</div>";

	}

	public function email($name, $label=NULL, $value=NULL, array $args=[]){

		$attr=[
			'class'=>'form-control',
			'groupClass'=>'form-group',
		];

		$attr=array_merge($attr, $args);

		$error_msg='';

		if(Input::instance()->error()->exists($name)){

			$error_msg=Input::instance()->error()->msg($name);
			$attr['class'].=' is-invalid';

		}

		if(!$label) $label=ucwords(str_replace(['_', '-'], ' ', $name));

		return "<div class='{$attr['groupClass']}'>\n<label for='{$name}'>{$label}</label>\n".$this->form->email($name, $value, $attr)."\n<div class='invalid-feedback'>{$error_msg}</div>\n</div>";

	}

	/*
	public function radio($name, $value=NULL, array $args=[]){

	}

	public function checkbox($name, $value=NULL, array $args=[]){

	}
	*/

	public function textarea($name, $label=NULL, $value=NULL, array $args=[]){

		$attr=[
			'class'=>'form-control',
			'groupClass'=>'form-group',
		];

		$attr=array_merge($attr, $args);

		$error_msg='';

		if(Input::instance()->error()->exists($name)){

			$error_msg=Input::instance()->error()->msg($name);
			$attr['class'].=' is-invalid';

		}

		if(!$label) $label=ucwords(str_replace(['_', '-'], ' ', $name));

		return "<div class='{$attr['groupClass']}'>\n<label for='{$name}'>{$label}</label>\n".$this->form->textarea($name, $value, $attr)."\n<div class='invalid-feedback'>{$error_msg}</div>\n</div>";

	}

	public function select($name, $label=NULL, array $values=[], $selected=NULL, array $args=[]){

		$filteredName=rtrim($name, '[]');

		$attr=[
			'class'=>'form-control',
			'groupClass'=>'form-group',
		];

		$attr=array_merge($attr, $args);

		$error_msg='';

		if(Input::instance()->error()->exists($filteredName)){

			$error_msg=Input::instance()->error()->msg($filteredName);
			$attr['class'].=' is-invalid';

		}

		if(!$label) $label=ucwords(str_replace(['_', '-'], ' ', $filteredName));

		return "<div class='{$attr['groupClass']}'>\n<label for='{$name}'>{$label}</label>\n".$this->form->select($name, $values, $selected, $attr)."\n<div class='invalid-feedback'>{$error_msg}</div>\n</div>";

	}

	public function button(string $title='Button', array $args=[]){

		$attr=[
			'type'=>'button',
			'class'=>'btn btn-outline-primary btn-sm',
			'icon'=>'fa-dot-circle-o'
		];

		$attr=array_merge($attr, $args);
		if($attr['icon']) $title="<i class='fa {$attr['icon']}'></i> ".$title;
		unset($attr['icon']);

		return $this->form->button($title, $attr);

	}

	public function submit(string $title='Submit', array $args=[]){

		$attr=[
			'type'=>'submit',
			'class'=>'btn btn-primary btn-sm',
			'icon'=>'fa-dot-circle-o'
		];

		$attr=array_merge($attr, $args);
		if($attr['icon']) $title="<i class='fa {$attr['icon']}'></i> ".$title;
		unset($attr['icon']);

		return $this->form->button($title, $attr);

	}

	public function link($title='Link', $url='#', $args=[]){

		$attr=[
			'class'=>'btn btn-outline-info btn-sm',
			'icon'=>'fa-link'
		];

		$attr=array_merge($attr, $args);
		if($attr['icon']) $title="<i class='fa {$attr['icon']}'></i> ".$title;
		unset($attr['icon']);

		return $this->form->link($title, $url, $attr);

	}

	public function btnDelete($url, $pk, $args=[]){
		return $this->form->btnDelete($url, $pk, $args);
	}

}