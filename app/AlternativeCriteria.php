<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlternativeCriteria extends Model
{
    protected $table = 'alternative_criteria';
    public $timestamps = false;

    public function alternative()
    {
        return $this->belongsTo('App\Package', 'alternative_id');
    }

    public function criteria()
    {
        return $this->belongsTo('App\Criteria', 'criteria_id');
    }
}
