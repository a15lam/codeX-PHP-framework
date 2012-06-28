<?php
/******************************************************************************
* Copyright (c) 2012 Ariful Islam
* 
* Permission is hereby granted, free of charge, to any person obtaining
* a copy of this software and associated documentation files (the
* "Software"), to deal in the Software without restriction, including
* without limitation the rights to use, copy, modify, merge, publish,
* distribute, sublicense, and/or sell copies of the Software, and to
* permit persons to whom the Software is furnished to do so, subject to
* the following conditions:
* 
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
* LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
* WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*******************************************************************************/
namespace X\engine;

class DB{
    private static $instance = NULL;
    private static $user = DB_USER;
    private static $pass = DB_PASS;
    private static $host = DB_HOST;
    private static $port = DB_PORT;
    
    public static $db = DB_NAME;
    public static $X;
    
    public static function getInstance($dbType='mysql'){
        if(!self::$instance){
            if($dbType=='mongo'){
                self::$instance = new Mongo(self::$X, "mongodb://".DB::$user.":".DB::$pass."@".DB::$host.":".DB::$port);
            }
            else if($dbType=='mysql'){
                self::$instance = new XPDO(self::$X, self::$host, self::$user, self::$pass, self::$db, self::$port);
                self::$instance-> setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
            else{
                throw new \Exception(__METHOD__.": DB Server type - $dbType is not supported.");
            }
        }
        return self::$instance;
    }
    
    private function __construct() {
        ;
    }
    
    private function __clone() {
        ;
    }
}
?>
