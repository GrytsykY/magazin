<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\AdminProductsRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Repositories\Admin\ProductRepository;
use App\SBlog\Core\BlogApp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MetaTag;

class ProductController extends AdminBaseController
{
    private $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = app(ProductRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 10;
        $getAllProducts = $this->productRepository->getAllProducts($perpage);
        $count = $this->productRepository->getCountProduct();

        MetaTag::setTags(['title' => "Список продуктов"]);
        return view('blog.admin.product.index', compact('getAllProducts', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new Category();

        MetaTag::setTags(['title' => "Создание нового продукта"]);
        return view('blog.admin.product.create', [
            'catigories' => Category::with('children')
                ->where('parent_id', '0')
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
    public function store(AdminProductsRequest $request)
    {
        $data = $request->input();
        $product = (new Product())->create($data);
        $id = $product->id;
        $product->status = $request->status ? '1':'0';
        $product->hit = $request->hit ? '1':'0';
        $product->category_id = $request->parent_id ?? '0';
        $this->productRepository->getImg($product);
        $save = $product->save();
        if ($save){
            $this->productRepository->editFilter($id, $data);
            $this->productRepository->editRelateProduct($id, $data);
            $this->productRepository->saveGallery($id);
            return redirect()
                ->route('blog.admin.products.create',[$product->id])
                ->with(['success' => "Успешно сохранено"]);
        }else{
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])
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
        $product = $this->productRepository->getInfoProduct($id);
        MetaTag::setTags(['title' => "Редактирование продукта № $id"]);
        return view('blog.admin.product.edit', [
            'catigories' => Category::with('children')
                ->where('parent_id', '0')
                ->get(),
            'delimiter' => '-',
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function related(Request $request)
    {
        $q = isset($request->q) ? htmlspecialchars(trim($request->q)) : '';
        $data['items'] = [];
        $products = $this->productRepository->getProducts($q);
        if ($products) {
            $i = 0;
            foreach ($products as $id => $title) {
                $data['items'][$i]['id'] = $title->id;
                $data['items'][$i]['text'] = $title->title;
                $i++;
            }
        };
        echo json_encode($data);
        die;
    }

    public function ajaxImage(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('blog.admin.product.include.image_single_edit');
        }else{
            $validator = \Validator::make($request->all(),
                [
                    'title'=>'image|max:1000',
                ],
                [
                    'file.image'=>'Файл должен быть картинкой (jpeg, phg, gif, or svg)',
                    'file.max'=>'Ошибка! Максимальный размер картинки - 5 мб',
                ]);
            if ($validator->fails()){
                return array(
                    'fail' => true,
                    'errors'=>$validator->errors(),
                );
            }

            $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'uploads/single/';
            $filename = uniqid(). '_'.date('dmYHi').'.'.$extension;
            $request->file('file')->move($dir,$filename);
            $wmax = BlogApp::get_instance()->getProperty('img_width');
            $hmax = BlogApp::get_instance()->getProperty('img_height');
            $this->productRepository->uploadImg($filename,$wmax,$hmax);
            return $filename;
        }

    }

    public function gallery(Request $request)
    {
        //dd("ok->".$request);
        $validator = \Validator::make($request->all(),
            [
                'title'=>'image|max:5000',
            ],
            [
                'file.image'=>'Файл должен быть картинкой (jpeg, phg, gif, or svg)',
                'file.max'=>'Ошибка! Максимальный размер картинки - 5 мб',
            ]);
        if ($validator->fails()){
            return array(
                'fail' => true,
                'errors'=>$validator->errors(),
            );
        }

        if (isset($_GET['upload'])){
            $wmax = BlogApp::get_instance()->getProperty('gallery_width');
            $hmax = BlogApp::get_instance()->getProperty('gallery_height');
            $name = $_POST['name'];
            $this->productRepository->uploadGallery($name,$wmax,$hmax);
        }

    }

    public function deleteImage($filename)
    {
        \File::delete('uploads/single/'.$filename);
    }

    public function deletegallery()
    {
        $id = isset($_POST['id']) ? $_POST['id']:null;
        $src = isset($_POST['src']) ? $_POST['src']:null;
        if (!id || !$src){
            return;
        }
        if (\DB::table("DELETE FROM galleries WHERE product_id = ? AND img = ?",[$id, $src])){
            @unlink("uploads/gallery/$src");
            exit('1');
        }
        return;
    }
}
