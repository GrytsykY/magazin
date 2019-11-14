<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\AttrsFilterAddRequest;
use App\Http\Requests\GroupFilterAddRequest;
use App\Models\Admin\AttributeGroup;
use App\Models\Admin\AttributeValue;
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

    public function editGroup(GroupFilterAddRequest $request, $id)
    {
        if (empty($id)){
            return back()->withErrors(['msg' => "Запись [{$id}] не найдена"]);
        }
        if ($request->isMethod('post')){
            $group = AttributeGroup::find($id);
            $group->title = $request->title;
            $group->save();

            if ($group){
                return redirect('/admin/filter/group-filter')
                    ->with(['success' => "Успешно сохранено"]);
            }else{
                return back()
                    ->withErrors(['msg' => "Ошибка изменения"])
                    ->withInput();
            }
        }else{
            if ($request->isMethod('get')){
                $group = $this->filterGroupRepository->getInfoProduct($id);
                return view('blog.admin.filter.group-edit',compact('group'));
            }
        }
    }

    public function deleteGroup($id)
    {
        if (empty($id)){
            return back()->withErrors(['msg'=>"Запись [{$id}] не найдена"]);
        }

        $count = $this->filterAttrsRepository->getCountFilterAttrsById($id);
        if ($count){
            return back()->withErrors(['msg'=> "Удаление не возможно в группе есть атрибуты"]);
        }
        $delete = $this->filterGroupRepository->deleteGroupFilter($id);
        if ($delete){
            return back()->with(['success'=>"Удалено"]);
        }else{
            back()->withErrors(['msg'=>"Ошибка удаления"]);
        }
    }

    public function attributeFilter()
    {
        $attrs = $this->filterAttrsRepository->getAllAttrsFilter();
        $count = $this->filterGroupRepository->getCountGroupFilter();

        MetaTag::setTags(['title' => 'Новая группа фильтров']);
        return view('blog.admin.filter.attribute',compact('attrs','count'));
    }

    public function attributeAdd(AttrsFilterAddRequest $request)
    {
        if ($request->isMethod('post')){
            $uniqName = $this->filterAttrsRepository->checkUnique($request->value);
            if ($uniqName){
                return redirect('/admin/filter/attrs-add')
                    ->withErrors(['msg' => "Такое название фильтра уже есть"])
                    ->withInput();
            }
            $data = $request->input();
            $attr = (new AttributeValue())->create($data);
            $attr->save();

            if ($attr){
                return redirect('/admin/filter/attrs-add')
                    ->with(['success' => "Добавлена новый фильтр"]);
            }else{
                return back()
                    ->withErrors(['msg' => "Ошибка создания фильтра"])
                    ->withInput();
            }

        }else{
            if ($request->isMethod('get')){
                $group = $this->filterGroupRepository->getAllGroupsFilter();
                MetaTag::setTags(['title'=>'Новый атрибут для фильтра']);
                return view('blog.admin.filter.attrs-add',compact('group'));
            }
        }
    }

    public function attributeEdit(AttrsFilterAddRequest $request, $id)
    {
        if (empty($id)){
            return back()->withErrors(['msg'=>"Запись [{$id}] не найдена"]);
        }
        if ($request->isMethod('post')){
            $attr = AttributeValue::find($id);
            $attr->value = $request->value;
            $attr->attr_group_id = $request->attr_group_id;
            $attr->save();

            if ($attr){
                return redirect('/admin/filter/attributes-filter')
                    ->with(['success' => "Успешно изменено"]);
            }else{
                return back()
                    ->withErrors(['msg' => "Ошибка изменения"])
                    ->withInput();
            }
        }else{
            if ($request->isMethod('get')){
                $attr = $this->filterAttrsRepository->getInfoProduct($id);
                $group = $this->filterGroupRepository->getAllGroupsFilter();

                MetaTag::setTags(['title'=>'Редактирование фильтра']);
                return view('blog.admin.filter.attrs-edit',compact('group','attr'));
            }
        }
    }

    public function attributeDelete($id)
    {
        if (empty($id)){
            return back()->withErrors(['msg'=>"Запись [{$id}] не найдена"]);
        }

        $delete = $this->filterAttrsRepository->deleteAttrFilter($id);

        if ($delete){
            return back()->with(['success'=>"Удалено"]);
        }else{
            back()->withErrors(['msg'=>"Ошибка удаления"]);
        }
    }
}
