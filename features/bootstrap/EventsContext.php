<?php

use Behat\Behat\Context\Context;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Xdebug;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

class EventsContext implements Context
{
    /**
     * @var CodeCoverage
     */
    protected static $coverage = null;

    /**
     * @BeforeSuite
     */
    public static function beforeSuite()
    {
        require dirname(dirname(__DIR__)) . "/paths.php";
        static::setupCoverage();
        require dirname(dirname(__DIR__)) . "/app/database.php";
    }

    /**
     * @AfterSuite
     */
    public static function afterSuite()
    {
        static::coverageReport();
    }

    public static function setupCoverage()
    {
        $driver = new Xdebug();
        $filter = new Filter();
        $filter->addDirectoryToWhitelist(ROOT . DS . "app/App");
        static::$coverage = new CodeCoverage($driver, $filter);
        static::$coverage->start("test");
    }

    public static function coverageReport()
    {
        static::$coverage->stop();

        $writer = new Clover;
        $writer->process(static::$coverage, ROOT . DS . "coverage.xml");

        $writer = new Facade();
        $writer->process(static::$coverage, ROOT . DS . "code-coverage-report");
    }
}
