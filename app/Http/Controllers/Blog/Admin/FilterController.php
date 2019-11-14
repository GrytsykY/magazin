<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\GroupFilterAddRequest;
use App\Models\Admin\AttributeGroup;
use App\Repositories\Admin\FilterAttrsRepository;
use App\Repositories\Admin\FilterGroupRepository;
use Illuminate\Http\Request;
use MetaTag;

class FilterController extends AdminBaseController
{

    private $filterGroupRepository;
    private $filterAttrsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->filterGroupRepository = app(FilterGroupRepository::class);
        $this->filterAttrsRepository = app(FilterAttrsRepository::class);
    }


    public function attributeGroup()
    {
        $attrs_group = $this->filterGroupRepository->getAllGroupsFilter();

        MetaTag::setTags(['title' => 'Групы фильтров']);
        return view('blog.admin.filter.attribute-group',compact('attrs_group'));
    }

    public function addGroup(GroupFilterAddRequest $request)
    {
        if ($request->isMethod('post')){
            $data = $request->input();
            $group = (new AttributeGroup())->create($data);
            $group->save();

            if ($group){
                return redirect('/admin/filter/group-add')
                    ->with(['success' => "Добавлена новая группа"]);
            }else{
                return back()
                    ->withErrors(['msg' => "Ошибка создания новой группы"])
                    ->withInput();
            }
        }else{
            if ($request->isMethod('get')){
                MetaTag::setTags(['title' => 'Новая группа фильтров']);
                return view('blog.admin.filter.group-add');
            }
        }
    }
}
