<?php

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $created;

    /**
     *
     * @var integer
     */
    public $attribute_id;


	public function getSome(){
		return $this->id . ' '
		. $this->attribute_id . ' '
		. $this->first_name . ' '
			. $this->last_name . ' '
			. $this->created;
	}

}
