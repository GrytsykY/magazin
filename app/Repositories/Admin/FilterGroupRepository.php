<?php


namespace App\Repositories\Admin;

use App\Models\Admin\AttributeGroup as Model;
use App\Repositories\CoreRepository;

class FilterGroupRepository extends CoreRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAllGroupsFilter()
    {
        $attrs_group = \DB::table('attribute_groups')->get()->all();
        return $attrs_group;
    }
}