<?php
##########################################################################
#  Herbology 
#  Account Class
#
#  Matthew Bryan
#  Oct 29, 2017
##########################################################################

require_once('class.account_sql.inc.php');

class account
{

    var $dO;   //data object
    
    public function __construct()
    {
        $this->dO = new account_sql;
    }

    function account()
    {
        self::__construct();
    }

    function checkLogin($login, $pass)
    {
        return $this->dO->checkLogin($login, $pass);
    }

}
