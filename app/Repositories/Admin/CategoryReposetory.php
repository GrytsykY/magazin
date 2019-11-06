<?php


namespace App\Repositories\Admin;


use App\Repositories\CoreRepository;
use App\Models\Admin\Category as Model;
use Menu as LavMenu;

class CategoryReposetory extends CoreRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function buildMenu($arrMenu)
    {

        $mBuilder = LavMenu::make('MyNav', function ($m) use ($arrMenu) {
            foreach ($arrMenu as $item) {
                if ($item->parent_id == 0) {
                    $m->add($item->title, $item->id)
                        ->id($item->id);
                } else {
                    if ($m->find($item->parent_id)) {
                        $m->find($item->parent_id)
                            ->add($item->title, $item->id)
                            ->id($item->id);
                    }
                }
            }
        });
        return $mBuilder;
    }

    public function checkChildren($id)
    {
        $children = $this->startConditions()
            ->where('parent_id', $id)
            ->count();
        return $children;
    }

    public function checkParentProduct($id)
    {
        $parents = \DB::table('products')
            ->where('category_id', $id)
            ->count();
        return $parents;
    }

    public function deleteCategory($id)
    {
        $delete = $this->startConditions()
            ->find($id)
            ->forceDelete();
        return $delete;
    }

    public function getComboBoxCategory()
    {
        $columns = implode(',', [
            'id',
            'parent_id',
            'title',
            'CONCAT (id, ". ", title) AS combo_title',
        ]);

        $result = $this->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();
        return $result;
    }

    public function checkUniqueName($name, $parent_id)
    {
        $name = $this->startConditions()
            ->where('title','=',$name)
            ->where('parent_id','=',$parent_id)
            ->exists();
        return $name;
    }

}