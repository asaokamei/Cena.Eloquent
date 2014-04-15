<?php

class AllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite( 'all tests' );
        $suite->addTestFile( __DIR__ . '/Tests/Eloquent_BasicTest.php' );
        $suite->addTestFile( __DIR__ . '/Tests/EmaEloquent_basicTest.php' );
        $suite->addTestFile( __DIR__ . '/Tests/Cm_basicTest.php' );
        return $suite;
    }
}