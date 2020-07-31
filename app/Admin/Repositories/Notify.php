<?php

namespace App\Admin\Repositories;

use App\Models\Notify as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Notify extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
