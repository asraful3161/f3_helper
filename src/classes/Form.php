<?php
namespace F3;

class Form extends \Prefab{

	public function btnDelete($args=[]){

		$attr=[
			'url'=>'#',
			'class'=>'btn btn-danger btn-sm',
			'icon'=>'fa-trash-o fa-lg',
			'title'=>'Delete'
		];

		$attr=array_merge($attr, $args);

		return "<form method='POST' action='{$attr['url']}' onsubmit=\"return confirm('Press OK! to confirm.')\">
		<input type='hidden' name='_method' value='DELETE'>
		<button type='submit' class='{$attr['class']}'><i class='fa {$attr['icon']}'></i> {$attr['title']}</button>
		</form>";

	}

	public function csrf_field(){
		
		$f3=\Base::instance();
		new \Session(NULL,'CSRF');
		$f3->copy('CSRF','SESSION.csrf');
		return "<input type='hidden' name='csrf_token' value='{$f3->CSRF}'/>";

	}

}