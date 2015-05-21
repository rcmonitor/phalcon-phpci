<?php
/**
 * Created by PhpStorm.
 * User: tkorzhikov
 * Date: 19.05.15
 * Time: 16:35
 */

namespace test;


class InitialTest extends \PHPUnit_Framework_TestCase{


	public function setUp(){

	}


	public function testOne(){
		$this->assertEquals(1, 1, 'test failed');
	}


	/**
	 * @group
	 */
	public function testTwo(){
		$this->assertEquals(1, 2, 'test completed');
	}
}