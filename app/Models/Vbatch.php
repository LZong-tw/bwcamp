<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Vbatch extends Model
{
    //
    protected $table = 'batchs';

    public $resourceNameInMandarin = '義工梯次資料';

    public $resourceDescriptionInMandarin = '在義工營隊裡的皆是義工梯次，它和主營隊的梯次理論上是一對一';

    public function mainBatch()
    {
        return $this->hasOneThrough(Batch::class, BatchVbatchXref::class, 'vbatch_id', 'id', 'id', 'batch_id');
    }
    public function vcamp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    public function batch(): HasOneThrough
    {
        //vbatch's batch
        return $this->hasOneThrough(Batch::class, BatchVbatchXref::class, 'vbatch_id', 'id', 'id', 'batch_id');
    }

}
