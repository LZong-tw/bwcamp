<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    public function vcamp() {
        return $this->belongsTo(Camp::class);
    }

    public function batch()
    {
        //foreign key of BatchVbatchXref (batch_id)
        //foreign key of Vbatch (id)
        //local key of Batch (id)
        //local key of BatchVbatchXref (vbatch_id)
        return $this->hasOneThrough(Batch::class, BatchVbatchXref::class, 'vbatch_id', 'id', 'id', 'batch_id');
    }

}
