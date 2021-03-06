<?php
namespace F3;

class Redirect extends \Prefab{

    protected $f3, $flash;

    public function __construct(){

    	$this->f3=\Base::instance();
        $this->flash=\F3\Flash::instance();

    }

    public function to($url){

        $this->f3->reroute(\F3\Url::instance()->to($url));

    }

    public function toUrl($url){

        $this->f3->reroute($url);

    }

    public function toRoute($route){

        $this->f3->reroute($route);

    }

    public function toBack(){

        $this->f3->reroute($this->f3->get('HEADERS.Referer'));

    }

    public function with($key, $value=NULL){

        $this->flash->set($key, $value);
        return $this;

    }

    public function withInput($inputErrors=NULL){

        $this->flash->set('inputs', array_merge($_POST, $_GET));
        if($inputErrors) $this->flash->set('errors', $inputErrors);
        return $this;

    }

    public function withError($errors){

        $this->flash->set('errors', $errors);
        return $this;

    }

    public function WithStatus($status, array $args=[]){

        $attr=[
            'success'=>'Request submitted successfully!.',
            'failed'=>'Sorry!, form submission failed.',
            'name'=>'alert_status'
        ];

        $attr=array_merge($attr, $args);

        if(empty($status)) $this->flash->set($attr['name'], ['danger'=>$attr['failed']]);
        else $this->flash->set($attr['name'], ['success'=>$attr['success']]);
        return $this;

    }

}