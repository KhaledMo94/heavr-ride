<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DistanceScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */

    public function __construct(protected $lat, protected $long)
    {
        $this->lat = $lat;
        $this->long = $long;
    }

    public function apply(Builder $builder, Model $model): void
    {
        if (!$this->lat || !$this->long) {
            return;
        }

        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        $builder->selectRaw("{$model->getTable()}.*, {$haversine} AS distance", [
            $this->lat,
            $this->long,
            $this->lat
        ]);
    }
}
