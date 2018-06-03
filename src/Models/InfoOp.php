<?php

namespace OpenDominion\Models;

class InfoOp extends AbstractModel
{
    protected $casts = [
        'source_realm_id' => 'int',
        'source_dominion_id' => 'int',
        'target_dominion_id' => 'int',
        'data' => 'array',
    ];

    protected function sourceRealm()
    {
//        return $this->belongsTo(Realm::class);
    }

    protected function sourceDominion()
    {
        return $this->belongsTo(Dominion::class, 'source_dominion_id');
    }

    protected function targetDominion()
    {
        //
    }
}
