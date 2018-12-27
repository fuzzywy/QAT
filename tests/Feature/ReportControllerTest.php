<?php

namespace Tests\Feature;

use App\Report;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testDel()
    {
        $report = factory(Report::class)->make();
        $taskId = $report->taskId();
        $this->post('/report/add', ['taskId' => $taskId]);
        $this->assertDatabaseMissing('report', $report->toArray());
    }

    public function testAdd()
    {

    }

    public function testModify()
    {

    }


    public function testList()
    {

    }

    public function testRun()
    {

    }

    public function testStop()
    {

    }
}
