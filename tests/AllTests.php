<?php

class AllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite( 'all tests' );
        $suite->addTestFile( __DIR__ . '/Tests/Eloquent_BasicTest.php' );
        $suite->addTestFile( __DIR__ . '/Tests/EmaEloquent_BasicTest.php' );
        $suite->addTestFile( __DIR__ . '/Tests/Cm_BasicTest.php' );
        $suite->addTestFile( __DIR__ . '/Tests/Process_BasicTest.php' );
        return $suite;
    }
}