<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\OrderRepository;
use App\Repositories\Admin\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MetaTag;

class MainController extends AdminBaseController
{
    private $orderRepository;
    private $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = app(OrderRepository::class);
        $this->productRepository = app(ProductRepository::class);
    }

    public function index(){
        $countOrders = MainRepository::getCountOrders();
        $countUsers = MainRepository::getCountUsers();
        $countProducts = MainRepository::getCountProducts();
        $countCategories = MainRepository::getCountCategories();

        $perpage = 4;

        $last_orders = $this->orderRepository->getAllOrders(7);
        $last_products = $this->productRepository->getLastProduct($perpage);

        MetaTag::setTags(['title' => 'Админ панель']);
        return view('blog.admin.main.index', compact('countOrders','countUsers',
            'countProducts','countCategories','last_products','last_orders'));
    }
}
