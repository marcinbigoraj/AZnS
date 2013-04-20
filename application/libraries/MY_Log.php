<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Log extends CI_Log
{

	protected $_levels	= array('ERROR' => '1', 'INFO' => '2',  'DEBUG' => '3', 'ALL' => '4');
	
	function MY_Log ()
    {
        parent::__construct();
    }

}