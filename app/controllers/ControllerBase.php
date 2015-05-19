<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{


	/**
	 * not working in here(
	 */
	public function initialize(){
		echo '<h1>initializing base controller</h1>';
	}

}
