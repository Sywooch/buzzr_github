<?php

namespace app\controllers;

use Yii;
use app\models\Store;
use app\controllers\BaseController as Controller;
use app\models\Article;
use app\models\Category;
use app\models\Product;
use app\models\User;
use app\models\filters\ArticleFilter;
use app\models\filters\ProductFilter;
use app\models\filters\SearchModel;
use yii\web\UploadedFile;
use app\models\CategoryAttribute;
use app\models\filters\OrdersFilter;
use app\models\Order;
use app\models\UserNotifications;
use app\models\StoreSubCategories;
use app\models\CountVisit;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class StoreController extends Controller
{

    private $store;


    public function actionCatalog($id = null, $code = null)
    {

        Url::remember();

        $store = $this->getStore();

        $treelist = $store->GetCategoryTreeList(true);

        //var_dump($treelist);exit;

        $subcount = 0;
        $parent_id = false;

        foreach ($treelist[0]['subcat'] as $topcat_id) {
            $topcat = $treelist[$topcat_id];
            foreach ($topcat['subcat'] as $midcat_id) {
                $midcat = $treelist[$midcat_id];
                foreach ($midcat['subcat'] as $lastcat_id) {
                    $lastcat = $treelist[$lastcat_id];
                    if ($lastcat['act_cnt'] !== 0) {
                        $subcount++;
                        $parent_id = $lastcat_id;
                    }
                }
            }
        }

        if ($subcount == 1) {
            return $this->actionCatalogproductsall($id, $code, $nocats = true, $parent_id);
        }


        return $this->render('catalog', ['model' => $treelist, 'subcount' => $subcount]);
    }

    public function actionCatalogselect($id = null, $code = null)
    {
        $this->requestEditAccess();
        $store = $this->getStore();
        if ($store->load($_POST)) {
            return $this->redirect(['store/catalogedit', 'code' => $store->slug]);
        }
        return $this->render('catalog-select', ['catalog' => $store->GetCategoryTreeList(true)]);
    }

    public function actionCatalogedit($id = null, $code = null)
    {
        $this->requestEditAccess();
        Url::remember();
        $store = $this->getStore();
        return $this->render('catalog-edit', ['catalog' => $store->GetCategoryTreeList(true)]);
    }

    public function actionCatalogproducts($id = null, $code = null, $parent = null)
    {
        Url::remember();
        $filter = new ProductFilter;

        if ($store = $this->getStore())
            $filter->store_id = $store->id;

        if ($parent)
            $filter->parent = $parent;

        $showfilter = (Product::jointQuery($filter)->count() > 8);

        if($showfilter)
        	$filter->loadFilter($_POST);

        $data = Product::getDataProvider($filter);
        $attributes = CategoryAttribute::listForCategory($parent ? $parent : -1);

        return $this->render('catalog-products', ['catalogDataProvider' => $data, 'filter' => $filter, 'attributes' => $attributes, 'showfilter' => $showfilter]);
    }

    public function actionCatalogproductsall($id = null, $code = null, $nocats = false, $parent_id = false)
    {
        Url::remember();
        $filter = new ProductFilter;

        if ($store = $this->getStore())
            $filter->store_id = $store->id;

        $attributes = CategoryAttribute::listForCategory($parent_id ? $parent_id : -1);

        $showfilter = (Product::jointQuery($filter)->count() > 8);

		if($showfilter)
	        $filter->loadFilter($_POST);

        $data = Product::getDataProvider($filter);
        return $this->render('catalog-products', ['catalogDataProvider' => $data, 'filter' => $filter, 'attributes' => $attributes, 'toCategories' => !$nocats, 'showfilter' => $showfilter]);
    }

    public function actionCatalogproductsedit($id = null, $code = null, $parent = null)
    {
        $this->requestEditAccess();
        Url::remember();
        $filter = new ProductFilter;

        if ($store = $this->getStore())
            $filter->store_id = $store->id;

        if ($parent)
            $filter->parent = $parent;

        $filter->active = null;

        $data = Product::getDataProvider($filter, 20, true);

        return $this->render('catalog-products-edit', ['catalogDataProvider' => $data, 'parent' => $parent]);
    }

    public function actionProductedit($id = null, $code = null, $parent = false, $product_id = 'add', $ajax = 0)
    {
        $this->requestEditAccess();
        $store = $this->getStore();

        if ($product_id == 'add') {
            $product = new Product;
            $product->store_id = $store->id;
            $product->category_type_id = $parent;
            $product->active = 1;
            $product->available = 1;
        } else {
            $product = Product::findById($product_id);
            if (!$parent)
                $parent = $product->category_type_id;
        }

        if (Yii::$app->request->post('save')) {
            if ($product->load($_POST) && $product->validate()) {
                $product->save();
                return $this->goBack();
            }
        }
        if (Yii::$app->request->get('remove')) {
            $product->delete();
            return $this->goBack();
        }


        $attributes = CategoryAttribute::listForCategory($parent);
        $filter = new ProductFilter;

        return $this->render(
            Category::isService($parent) ? '/catalog/service-edit' : '/catalog/product-edit',
            ['product' => $product, 'attributes' => $attributes]);
    }

    public function actionProductimageupload()
    {
        $model = new Product;
        $model->image = UploadedFile::getInstance($model, 'image');
        if ($result = $model->upload()) {
            return JSON_encode($result);
        }

    }

    public function actionMain($id = null, $code = null, $newlimit = 4, $poplimit = 4, $salelimit = 4)
    {
        return $this->doMain($id, $code, 'main', false, $newlimit, $poplimit, $salelimit);
    }

    public function actionMainedit($id = null, $code = null, $newlimit = 4, $poplimit = 4, $salelimit = 4)
    {
        return $this->doMain($id, $code, 'main-edit', true, $newlimit, $poplimit, $salelimit);
    }

    public function doMain($id = null, $code = null, $template = 'main', $edit_mode = false, $newlimit = 4, $poplimit = 4, $salelimit = 4)
    {

        Url::remember();
        $search = new SearchModel;
        $search->load($_POST);

        $filter_sale = new ProductFilter;
        $filter_new = new ProductFilter;
        $filter_popular = new ProductFilter;

        $filter_sale->has_sale = 1;

        $filter_sale->city = $search->city;
        $filter_new->city = $search->city;
        $filter_popular->city = $search->city;

        $filter_sale->edit_mode = $edit_mode;
        $filter_new->edit_mode = $edit_mode;
        $filter_popular->edit_mode = $edit_mode;

        $filter_new->date_min = time() - 30 * 24 * 60 * 60;

        if ($salelimit == 4)
            $filter_sale->limit = $salelimit;

        if ($newlimit == 4)
            $filter_new->limit = $newlimit;

        if ($poplimit == 4)
            $filter_popular->limit = $poplimit;

        $filter_sale->sort_order = ProductFilter::DATE_SORT;
        $filter_new->sort_order = ProductFilter::DATE_SORT;
        $filter_popular->sort_order = ProductFilter::SORT_POPULAR;

        if ($store = $this->getStore()) {
            $filter_sale->store_id = $store->id;
            $filter_new->store_id = $store->id;
            $filter_popular->store_id = $store->id;
        }

        $ids_sale = ArrayHelper::getColumn(Product::jointQuery($filter_sale)->all(), 'id');
        $filter_new->exclude_id = $ids_sale;

        $ids_new = ArrayHelper::getColumn(Product::jointQuery($filter_new)->all(), 'id');
        $filter_popular->exclude_id = array_merge($ids_sale, $ids_new);

        return $this->render($template, [
            'saleDataProvider' => Product::getDataProvider($filter_sale),
            'newDataProvider' => Product::getDataProvider($filter_new),
            'popularDataProvider' => Product::getDataProvider($filter_popular)
        ]);
}

    public function actionAbout($id = null, $code = null)
    {
        Url::remember();
        return $this->render('about');
    }

    public function actionBlog($id = null, $code = null)
    {
        Url::remember();
        $filter = new ArticleFilter();
        $filter->loadFilter($_POST);

        if ($store = $this->getStore())
            $filter->store_id = $store->id;

        return $this->render('blog',
            ['blogDataProvider' => Article::getDataProvider($filter)]
        );
    }

    public function actionAboutedit($id = null, $code = null)
    {
        $this->requestEditAccess();
        Url::remember();

        $store = $this->getStore();
        if ($store) {
            if ($store->load($_POST) && $store->validate()) {
                $store->save();
                return $this->redirect(Url::current(['code' => $store->slug]));
            }
        }

        return $this->render('about-edit');
    }

    public function actionBlogedit($id = null, $code = null)
    {
        $this->requestEditAccess();
        Url::remember();
        $filter = new ArticleFilter();
        $filter->published = null;
        $filter->loadFilter($_POST);

        if ($store = $this->getStore())
            $filter->store_id = $store->id;

        return $this->render('blog-edit',
            ['blogDataProvider' => Article::getDataProvider($filter)]
        );
    }


    public function getStore()
    {

        $store = null;

        if (!Yii::$app->user->getIsGuest())
            $store = Yii::$app->user->identity->mystore;

        if (isset($this->actionParams['code']))
            $store = Store::findOne(['slug' => $this->actionParams['code']]);
        elseif (isset($this->actionParams['id'])){
        	// если там только цифры, то сначала проверяем, не код ли это все-таки, и только потом считаем, что это id
            $store = Store::findOne(['slug' => $this->actionParams['id']]);
            if(!$store)
	            $store = Store::findOne(['id' => $this->actionParams['id']]);
        }

        if (null == $store)
            throw new \yii\web\NotFoundHttpException('Page not found');

        return $store;

    }

    public function actionImageupload()
    {
        $model = new Store;
        $model->image = UploadedFile::getInstance($model, 'image');
        if ($result = $model->upload('-resize "120x45^" -gravity center -crop 120x45+0+0 +repage')) {
            return JSON_encode($result);
        }
        return '';
    }

    public function actionLogoupload()
    {
        $model = new Store;
        $model->image = UploadedFile::getInstance($model, 'logo_tmp');
        if ($result = $model->upload()) {
            return JSON_encode($result);
        }
        return '';
    }

    public function requestEditAccess()
    {
        if (!$this->canEdit())
            throw new \yii\web\HttpException(403, 'No access');
    }

    public function canEdit()
    {

        $store = $this->getStore();

        if ($store)
            return User::isThisId($store->user_id) || User::isAdmin();

        return null;
    }

    public function actionOrders($id = null, $code = null)
    {
        Url::remember();

        $filter = new OrdersFilter;
        $filter->loadFilter($_POST);
        $filter->store_id = $this->getStore()->id;
        $orders = Order::getOrdersProvider($filter);

        UserNotifications::shutup('last_order');

        return $this->render('orders', ['orders' => $orders, 'filter' => $filter]);
    }

    public function actionDetails($order_id, $id = null, $code = null)
    {
        Url::remember();
        $order = Order::One($order_id);
        if ($order->load($_POST) && $order->validate()) {
            $order->save();
            return $this->redirect(['store/orders', 'code' => $order->store->slug]);
            //$order = Order::One($order_id);
        }

        return $this->render('details', ['order' => $order, 'products' => $order->products]);
    }

    public function render($view, $params = [])
    {
        $store = $this->store = $this->getStore();

        CountVisit::count($store->id);

        $params['store'] = $store;

        if (isset($this->actionParams['ajax']) && ($this->actionParams['ajax'] == 1))
            return parent::renderAjax($view, $params);
        else
            return parent::render($view, $params);

    }

}