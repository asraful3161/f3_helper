<?php
namespace F3;
use \F3\Input;
use \F3\Url;

class Form extends \Prefab{

	protected $formModel, $sendArray;

	public function btnDelete($url, $pk, array $args=[]){

		$attr=[
			'class'=>'btn btn-outline-danger btn-sm',
			'icon'=>'fa-trash-o fa-lg',
			'title'=>'Delete'
		];

		$attr=array_merge($attr, $args);

		return "<form method='POST' action='{$url}' onsubmit=\"return confirm('Press OK! to confirm.')\">
		<input type='hidden' name='_method' value='DELETE'><input type='hidden' name='_pk' value='{$pk}'>
		<button type='submit' class='{$attr['class']}'><i class='fa {$attr['icon']}'></i> {$attr['title']}</button>
		</form>";

	}

	public function csrf_field(string $fieldName='_token'){
		
		$f3=\Base::instance();
		new \Session(NULL,'CSRF');
		$f3->copy('CSRF','SESSION.csrf');
		return "<input type='hidden' name='{$fieldName}' value='{$f3->CSRF}'/>";

	}

	protected function modelValue($name){

		if($this->formModel){

			if($this->formModel->isNew()) return NULL;
			else{

				$getter='get'.str_replace(['_', '-'], '', ucwords($name, '_-'));
				if(method_exists($this->formModel, $getter)){

					if(empty($this->sendArray[$name])){
						return $this->formModel->$getter();
					}else{
						return array_keys($this->formModel->$getter()->toKeyValue());
					}

				}

				return NULL;
			}

		}

		return NULL;
	}

	public function open(array $args=[], array $moreArgs=[]){

		$attr=[
			'model'=>NULL,
			'url'=>Url::instance()->current(),
			'method'=>'POST',
			'create'=>NULL,
			'update'=>NULL
		];

		$attr=array_merge($attr, $args);

		$method=strtoupper($attr['method']);
		$methodField='';

		if($attr['model']){

			$this->formModel=$attr['model'];

			if($this->formModel->isNew()){
				if($attr['create']) $attr['url']=$attr['create'];				
			}else{
				$methodField="<input type='hidden' name='_method' value='PUT'>\n<input type='hidden' name='_pk' value='{$this->formModel->getPrimaryKey()}'>";
				if($attr['update']) $attr['url']=$attr['update'];
			}

		}elseif($method=='PUT' || $method=='DELETE'){
			$methodField="<input type='hidden' name='_method' value='{$method}'>";
		}

		$attributes='';

		foreach($moreArgs as $key=>$value) $attributes.=" {$key}='{$value}'";

		return "<form action='{$attr['url']}' method='POST' {$attributes}>\n{$this->csrf_field()}\n{$methodField}";

	}

	public function close(){
		return "</form>";
	}

	public function input($type='text', $name, $value=NULL, array $args=[]){

		$postValue=Input::instance()->old($name);

		if(!$value){

			if($postValue) $value=$postValue;
			else $value=$this->modelValue($name);

		}

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<input type='{$type}' name='{$name}' id='{$name}' value='{$value}' {$attributes}/>";

	}

	public function text($name, $value=NULL, array $args=[]){

		$postValue=Input::instance()->old($name);

		if(!$value){

			if($postValue!==NULL) $value=$postValue;
			else $value=$this->modelValue($name);
			
		}

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<input type='text' name='{$name}' id='{$name}' value='{$value}' {$attributes}/>";

	}

	public function password($name, $value=NULL, array $args=[]){

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<input type='password' name='{$name}' id='{$name}' value='{$value}' {$attributes}/>";

	}

	public function email($name, $value=NULL, array $args=[]){

		$postValue=Input::instance()->old($name);

		if(!$value){

			if($postValue!==NULL) $value=$postValue;
			else $value=$this->modelValue($name);
			
		}

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<input type='email' name='{$name}' id='{$name}' value='{$value}' {$attributes}/>";

	}

	public function radio($name, $value=NULL, array $args=[]){

		$postValue=Input::instance()->old($name);

		$checked='';

		if($postValue!==NULL && $postValue==$value) $checked='checked';
		elseif($this->modelValue($name)==$value) $checked='checked';			

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<input type='radio' name='{$name}' id='{$name}' value='{$value}' {$attributes} {$checked}/>";

	}

	public function checkbox($name, $value=NULL, array $args=[]){

		$sendArray='';

		if(strpos($name, '[]')!==FALSE){
			$sendArray='[]';
			$name=rtrim($name, '[]');
		}

		$postValue=Input::instance()->old($name);

		$checked='';

		if($postValue!==NULL){

			if(is_string($postValue) && $postValue==$value) $checked='checked';
			if(is_array($postValue) && in_array($postValue, $value)) $checked='checked';

		}else{

			$modelValue=$this->modelValue($name);

			if(!is_array($modelValue) && $modelValue==$value){

				$checked='checked';

			}elseif(is_array($modelValue) && in_array($value, $modelValue)){

				$checked='checked';

			}
			
		}

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<input type='checkbox' name='{$name}{$sendArray}' id='{$name}' value='{$value}' {$attributes} {$checked}/>";

	}

	public function textarea($name, $value=NULL, array $args=[]){

		$postValue=Input::instance()->old($name);

		if(!$value){

			if($postValue!==NULL) $value=$postValue;
			else $value=$this->modelValue($name);
			
		}


		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<textarea name='{$name}' id='{$name}' {$attributes}>{$value}</textarea>";

	}

	public function select($name, array $values=[], $selected=NULL, array $args=[]){

		$sendArray='';

		if(strpos($name, '[]')!==FALSE){
			$sendArray='[]';
			$name=rtrim($name, '[]');
			$this->sendArray[$name]=TRUE;
		}

		$str=\F3\StrOps::instance();

		$postSelected=Input::instance()->old($name);

		if(!$selected){

			if($postSelected!==NULL) $selected=$postSelected;
			else $selected=$this->modelValue($name);
			
		}

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";

		$options='';
		foreach($values as $key=>$title){

			$title=$str->get($title)->toTitle();

			if(!is_array($selected) && $key==$selected){
				$options.="<option value='$key' selected>{$title}</option>";
			}elseif(is_array($selected) && in_array($key, $selected)){
				$options.="<option value='$key' selected>{$title}</option>";
			}else $options.="<option value='$key'>{$title}</option>";

		}

		return "<select name='{$name}{$sendArray}' id='{$name}' {$attributes}>\n{$options}\n</select>";

	}

	public function button(string $title='Button', array $args=[]){

		$attr=[
			'type'=>'button'
		];

		$attr=array_merge($attr, $args);

		$attributes='';
		foreach($attr as $key=>$row) $attributes.=" {$key}='{$row}'";

		return "<button {$attributes}>\n{$title}\n</button>";

	}

	public function link(string $title, string $url, array $args=[]){

		$attributes='';
		foreach($args as $key=>$row) $attributes.=" {$key}='{$row}'";
		return "<a href='{$url}' {$attributes}>\n{$title}\n</a>";

	}

}