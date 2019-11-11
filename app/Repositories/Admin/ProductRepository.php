<?php


namespace App\Repositories\Admin;


use App\Models\Admin\Product;
use App\Repositories\CoreRepository;
use App\Models\Admin\Product as Model;

class ProductRepository extends CoreRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getLastProduct($perpage)
    {
        $get = $this->startConditions()
            ->orderBy('id', 'desc')
            ->limit($perpage)
            ->paginate($perpage);
        return $get;
    }

    public function getAllProducts($perpage)
    {
        $get_all = $this->startConditions()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.title AS cat')
            ->orderBy(\DB::raw('LENGTH(products.title)', 'products.title'))
            ->limit($perpage)
            ->paginate($perpage);
        return $get_all;
    }

    public function getCountProduct()
    {
        $count = $this->startConditions()
            ->count();
        return $count;
    }

    public function getProducts($q)
    {
        $products = \DB::table('products')
            ->select('id', 'title')
            ->where('title', 'LIKE', ["%{$q}%"])
            ->limit(8)
            ->get();
        return $products;
    }

    public function uploadImg($name, $wmax, $hmax)
    {
        $uploaddir = 'uploads/single/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $name));
        $uploadfile = $uploaddir . $name;
        \Session::put('single', $name);
        self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext);
    }

    public function uploadGallery($name, $wmax, $hmax)
    {

        $uploaddir = 'uploads/gallery/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name']));
        $new_name = md5(time()) . ".$ext";
        $uploadfile = $uploaddir . $new_name;
        \Session::push('gallery', $new_name);
        if (@move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
            self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext);
            $res = array("file" => $new_name);
            echo json_encode($res);
        }
    }

    public function getImg(Product $product)
    {
        clearstatcache();
        if (!empty(\Session::get('single'))) {
            $name = \Session::get('single');
            $product->img = $name;
            \Session::forget('single');
            return;
        }
        if (empty(\Session::get('single')) && !is_file(WWW . '/uploads/single/' . $product->img)) {
            $product->img = null;
        }
        return;
    }

    public function editFilter($id, $data)
    {
        $filter = \DB::table('attribute_products')
            ->where('product_id', $id)
            ->pluck('attr_id')
            ->toArray();

        /** если убрали фильтры */
        if (empty($data['attrs']) && !empty($filter)) {
            \DB::table('attribute_products')
                ->where('product_id', $id)
                ->delete();
            return;
        }

        /** если добавили фильтры */
        if (empty($filter) && !empty($data['attrs'])) {
            $sql_part = '';
            foreach ($data['attrs'] as $v) {
                $sql_part .= "($v, $id),";
            }

            $sql_part = rtrim($sql_part, ',');
            \DB::insert("insert into attribute_products (attr_id, product_id) VALUES $sql_part");
            return;
        }

        /** если меняем фильтры */
        if (!empty($data['attrs'])) {
            $result = array_diff($filter, $data['attrs']);
            if ($result) {
                \DB::table('attribute_products')
                    ->where('product_id', $id)
                    ->delete();
                $sql_part = '';
                foreach ($data['attrs'] as $v) {
                    $sql_part .= "($v, $id),";
                }
                $sql_part = trim($sql_part, ',');
                \DB::insert("insert into attribute_products (attr_id, product_id) VALUES $sql_part");
                return;
            }
        }
    }

    public function editRelateProduct($id, $data)
    {
        $related_product = \DB::table('related_products')
            ->select('related_id')
            ->where('product_id', $id)
            ->pluck('product_id')
            ->toArray();

        /** если убрал связанные товары */
        if (empty($data['related']) && !empty($related_product)) {
            \DB::table(related_product)
                ->where('product_id')
                ->delete();
            return;
        }

        /** если добавил связанные товары */
        if (empty($related_product) && !empty($data['related'])) {
            $sql_part = '';

            foreach ($data['related'] as $v) {
                $v = (int)$v;
                $sql_part .= "($id, $v),";
            }
            $sql_part = trim($sql_part, ',');
            \DB::insert("insert into related_products (product_id, related_id) VALUES $sql_part");
        }
    }

    public function saveGallery($id)
    {
        if (!empty(\Session::get('gallery'))) {
            $sql_part = '';

            foreach (\Session::get('gallery') as $v) {
                $sql_part .= "('$v',$id),";
            }
            //dd($sql_part);
            $sql_part = trim($sql_part, ',');
            \DB::insert("insert into galleries (img, product_id)VALUES $sql_part");
            \Session::forget('gallery');
        }
    }

    public static function resize($target, $dest, $wmax, $hmax, $ext)
    {
        list($w_orig, $h_orig) = getimagesize($target);
        $ratio = $w_orig / $h_orig;

        if (($w_orig / $h_orig) > $ratio) {
            $wmax = $hmax * $ratio;
        } else {
            $hmax = $wmax / $ratio;
        }

        $img = "";
        switch ($ext) {
            case ("gif"):
                $img = imagecreatefromgif($target);
                break;
            case ("phg"):
                $img = imagecreatefrompng($target);
                break;
            default:
                $img = imagecreatefromjpeg($target);
        }
        $newImg = imagecreatetruecolor($wmax, $hmax);
        if ($ext == "phg") {
            imagesavealpha($newImg, true);
            $transPhg = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
            imagefill($newImg, 0, 0, $transPhg);
        }
        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig);
        switch ($ext) {
            case ("gif"):
                imagegif($newImg, $dest);
                break;
            case ("phg"):
                imagepng($newImg, $dest);
                break;
            default:
                imagejpeg($newImg, $dest);
        }
        imagedestroy($newImg);
    }
}