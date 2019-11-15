<?php


namespace App\Repositories\Admin;

use App\Models\Admin\Currency as Model;
use App\Models\Admin\Currency;
use App\Repositories\CoreRepository;

class CurrencyRepository extends CoreRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAllCurrency()
    {
        $currency = $this->startConditions()::all();
        return $currency;
    }

    public function switchBaseCurr()
    {
        $id = \DB::table('currencies')
            ->where('base','=','1')
            ->get()
            ->first();
        if ($id){
            $id = $id->id;
            $new = Currency::find($id);
            $new->base = '0';
            $new->save();
        }else{
            return back()
                ->withErrors(['msg'=>"Ошибка Базовой валюты еще нет"])
                ->withInput();
        }

    }

    public function getInfoCurrency($id)
    {
        $currency = $this->startConditions()
            ->find($id);
        return $currency;
    }

    public function deleteCurr($id)
    {
        $delete = $this->startConditions()->where('id',$id)->forceDelete();
        return $delete;
    }

}