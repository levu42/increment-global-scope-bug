<?php

namespace Tests\Feature;

use App\Models\Scopes\IgnoreCompletedTasks;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BugTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        DB::table('tasks')->truncate();

        $task = Task::create([
            'title' => 'Take 3 diving lessons',
            'remaining_times' => 3,
        ]);

        $task->do();
        $task->do();

        $this->assertEquals($task->remaining_times, 1);

        $task->do();
        $this->assertEquals($task->remaining_times, 0);

        $task->refresh();
        $this->assertEquals($task->remaining_times, 0);


        $otherInstanceOfSameTask = Task::withoutGlobalScope(IgnoreCompletedTasks::class)->findOrFail($task->id);

        $this->assertEquals($otherInstanceOfSameTask->remaining_times, 0);
        $otherInstanceOfSameTask->undo();

        $this->assertEquals($otherInstanceOfSameTask->remaining_times, 1);
        $otherInstanceOfSameTask->refresh();
        $this->assertEquals($otherInstanceOfSameTask->remaining_times, 1);

        $task->refresh();
        $this->assertEquals($task->remaining_times, 1);
    }
}
