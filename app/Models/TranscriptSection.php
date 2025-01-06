<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranscriptSection extends Model
{
    use HasFactory;

    protected $fillable = ['transcript_id', 'start_time_ms', 'end_time_ms', 'content'];

    public function transcript()
    {
        return $this->belongsTo(Transcript::class);
    }
}
