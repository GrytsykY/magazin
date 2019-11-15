<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\AdminCurrencyAddRequest;
use App\Models\Admin\Currency;
use App\Repositories\Admin\CurrencyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MetaTag;

class CurrencyController extends AdminBaseController
{
    private $currencyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->currencyRepository = app(CurrencyRepository::class);
    }

    public function index()
    {
        $currency = $this->currencyRepository->getAllCurrency();

        MetaTag::setTags(['title' => "Валюта магазина"]);
        return view('blog.admin.currency.index',compact('currency'));
    }

    public function add(AdminCurrencyAddRequest $request)
    {
        if ($request->isMethod('post')){
            $data = $request->input();
            $currency = (new Currency())->create($data);

            if ($request->base == 'on'){
                $this->currencyRepository->switchBaseCurr();
            }
            $currency->base = $request->base ? '1':'0';
            $currency->save();

            if ($currency){
                return redirect('/admin/currency/add')
                    ->with(['success' => 'Валюта добавлена']);
            }else{
                return back()
                    ->withErrors(['msg' => "Ошибка добавления"])
                    ->withInput();
            }

        }else{
            if ($request->isMethod('get')){
                MetaTag::setTags(['title' => "Добавление валюты"]);
                return view('blog.admin.currency.add');
            }
        }
    }
}
