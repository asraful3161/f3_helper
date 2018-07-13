<?php
namespace F3;

use \F3\Std;

class Mail extends \Prefab{

	protected $smtp, $message;

	public function __construct(){

		$f3=\Base::instance();

		$this->smtp=new \SMTP(
		    $f3->get('EMAIL_HOST'),
		    $f3->get('EMAIL_PORT'),
		    $f3->get('EMAIL_SCHEME'),
		    $f3->get('EMAIL_USER'),
		    $f3->get('EMAIL_PASS')
		);

	}

	protected function reset(){

		$this->message=NULL;
		$this->smtp->clear('To');
		$this->smtp->clear('Subject');
		$this->smtp->clear('MIME-Version');
		$this->smtp->clear('Content-Type');

	}

	public function config(Std $args){

		if(empty($args->from)){
			$this->smtp->set('From', \Base::instance()->get('EMAIL_FROM'));
		}else{
			$this->smtp->set('From', $args->from);
		}

		if(isset($args->isHtml) && $args->isHtml){
			$this->smtp->set('MIME-Version', '1.0\r\n');
			$this->smtp->set('Content-Type', 'text/html; charset=UTF-8\r\n');
		}

		$this->smtp->set('To', $args->to);
		$this->smtp->set('Subject', $args->subject);

		$this->message=$args->message;

		return $this;

	}

	public function send(){

		if(empty($this->message)) return rv('Sorry!, mail message is missing.', FALSE);

		$mailStatus=$this->smtp->send($this->message);

		if($mailStatus){

			$this->reset();
			return rv('Success!, mail was send successfully.', TRUE);

		}

		return rv('Sorry!, failed to send email.', FALSE);

	}

}