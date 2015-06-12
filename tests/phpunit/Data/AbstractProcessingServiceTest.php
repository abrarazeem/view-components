<?php

namespace Nayjest\ViewComponents\Test\Data;

use Exception;
use Nayjest\ViewComponents\Data\Operations\FilterOperation;
use Nayjest\ViewComponents\Data\OperationsCollection;
use Nayjest\ViewComponents\Data\ProcessingServices\ArrayProcessingService;
use PHPUnit_Framework_TestCase;

abstract class AbstractProcessingServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var  ArrayProcessingService */
    protected $service;
    protected $data;
    /** @var  OperationsCollection */
    protected $operations;

    protected $totalCount;

    public function setUp()
    {
        throw new Exception('Override me!');
    }

//    public function testGetSource()
//    {
//        self::assertEquals($this->data, $this->manager->getDataSource());
//
//        // do some stuff
//        $op = new FilterControl('id','<=', 3);
//        $this->operations->add($op);
//
//        // test again
//        self::assertEquals($this->data, $this->manager->getDataSource());
//
//    }

    public function testGetProcessedData()
    {
        self::assertEquals(
            $this->totalCount,
            $this->service->count()
        );
    }

    public function testOperations()
    {
        $op = new FilterOperation('id','<=', 3);
        $this->operations->add($op);
        self::assertEquals(3, $this->service->count());
        $this->operations->remove($op);
        self::assertEquals($this->totalCount, $this->service->count());
    }
}