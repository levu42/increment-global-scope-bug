<?php

namespace App\Models;

use App\Models\Scopes\IgnoreCompletedTasks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'remaining_times'];

    protected static function booted(): void
    {
        static::addGlobalScope(new IgnoreCompletedTasks);
    }

    public function do(): void
    {
        $this->decrement('remaining_times');
    }

    public function undo(): void
    {
        $this->increment('remaining_times');
    }
}
