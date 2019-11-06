<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\Admin\Category;
use App\Repositories\Admin\CategoryReposetory;
use App\Repositories\CoreRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MetaTag;

class CategoryController extends AdminBaseController
{
    private $categoryReposetory;

    public function __construct()
    {
        parent::__construct();
        $this->categoryReposetory = app(CategoryReposetory::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arrMenu = Category::all();
        $menu = $this->categoryReposetory->buildMenu($arrMenu);

        MetaTag::setTags(['title' => 'Список категорий']);
        return view('blog.admin.category.index', ['menu' => $menu]);
    }

    public function mydel()
    {
        $id = $this->categoryReposetory->getRequestId();
        if (!$id){
            return back()->withErrors(['msg'=>'Ошибка с ID']);
        }

        $children = $this->categoryReposetory->checkChildren($id);
        if ($children){
            return back()->withErrors(['msg'=>'Удаление невозможно, в категории есть вложенные категории']);
        }

        $parents = $this->categoryReposetory->checkParentProduct($id);
        if ($parents){
            return back()->withErrors(['msg' => 'Удаление невозможно, в категории есть товары']);
        }

        $delete = $this->categoryReposetory->deleteCategory($id);
        if ($delete){
            return redirect()
                ->route('blog.admin.categories.index')
                ->with(['success'=>"Запись id [$id] удалена"]);
        }else{
            return back()->withErrors(['msg'=>'Ошибка удаления']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Category();
        $categoriList = $this->categoryReposetory->getComboBoxCategory();


        MetaTag::setTags(['title' => 'Создание новой категорий']);
        return view('blog.admin.category.create',[
            'categories' => Category::with('children')
            ->where('parent_id','0')
            ->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryUpdateRequest $request)
    {
        $name = $this->categoryReposetory->checkUniqueName($request->title, $request->parent_id);
        if ($name){
            return back()
                ->withErrors(['msg'=>'Не может быть в одной и той же Категории двух одинаковых.
            Выберите другую Категорию'])
                ->withInput();
        }

        $data = $request->input();
        $item = new Category();
        $item->fill($data)->save();
        if($item){
            return redirect()
                ->route('blog.admin.categories.create',[$item->id])
                ->with(['success' => 'Успешно сохранено']);
        }else{
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput();
        }


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->categoryReposetory->getId($id);
        if (empty($item)){
            abort(404);
        }

        MetaTag::setTags(['title' => "Редактирование категорий № $id"]);
        return view('blog.admin.category.edit',[
            'categories' => Category::with('children')
                ->where('parent_id','0')
                ->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->categoryReposetory->getId($id);
        if (empty($item)){
            return back()
                ->withErrors(['msg'=>"Запись = [{$item->title}] не найдена"])
                ->withInput();
        }

        $data = $request->all();
        $result = $item->update($data);
        if($result){
            return redirect()
                ->route('blog.admin.categories.edit',[$item->id])
                ->with(['success' => 'Успешно сохранено']);
        }else{
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
