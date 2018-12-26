<?php

namespace App\Http\Controllers;

use App\Console\Commands\ReportPublish;
use App\Report;
use Illuminate\Http\Request;

/**
 * Class ScheduleController
 * @package App\Http\Controllers
 */
class ReportController extends Controller
{

    /**
     * Remove a report task.
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $id = $request->get('id');
        ReportPublish::find($id)->delete();
        // TODO  how to stop the task
        $this->stop($request);
    }

    /**
     * Add a report task.
     *
     * @param Request $request
     */
    public function add(Request $request)
    {
        $schedule = new Report();
        $schedule->createTime = $request->get('createTime');
        $schedule->taskName = $request->get('taskName');
        $schedule->schedule = $request->get('schedule');
        $schedule->template = $request->get('template');
        $schedule->executeTime = $request->get('executeTime');
        $schedule->notify = $request->get('notify');
        $schedule->save();
    }

    /**
     * Update a report task.
     *
     * @param Request $request
     */
    public function modify(Request $request)
    {
        $id = $request->get('id');
        $schedule = Report::find($id);
        $schedule->createTime = $request->get('createTime');
        $schedule->taskName = $request->get('taskName');
        $schedule->schedule = $request->get('schedule');
        $schedule->template = $request->get('template');
        $schedule->executeTime = $request->get('executeTime');
        $schedule->notify = $request->get('notify');
        $schedule->save();
    }

    /**
     * Start schedule report task.
     *
     * @param Request $request
     */
    public function run(Request $request)
    {
        $scheduler = resolve(\Illuminate\Console\Scheduling\Schedule::class);
        $taskId = $request->get('taskId');
        $taskFreq = $request->get('taskFreq');
        $executeTime = $request->get('executeTime');
        switch ($taskFreq) {
            case 'Daily':
                $scheduler->command(ReportPublish::class, $taskId)->daily()->at($executeTime);
                break;
            case 'Hourly':
                $scheduler->command(ReportPublish::class, $taskId)->hourly()->at($executeTime);
                break;
            case Weekly:
                $scheduler->command(ReportPublish::class, $taskId)->weekly()->at($executeTime);
                break;
            default:
        }

        // TODO how to record task information for stop
    }

    /**
     * @param Request $request
     */
    public function stop(Request $request)
    {
        $taskName = $request->get('taskName');

        // TODO How to kill the corn process by taskName?
    }
}
