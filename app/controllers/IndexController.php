<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {

		$this->view->disable();

		echo $this->router->getRewriteUri();

		echo '<h1> index </h1>';

		echo $this->request->getURI();

    }


	public function someAction(){
		$this->view->disable();

		echo '<h1> some super action </h1>';
	}

}

