<?php

namespace App\Helpers\Category;

use App\Helpers\Container;
use Illuminate\View\View;

class Category 
{

    public static string $tpl = '';


    public static function getMenu(string $tpl, string $cacheKey = "")
    {
    
        self::$tpl = $tpl;

        if($cacheKey){
          $menuHtml = cache($cacheKey, '');
          if($menuHtml){
            return $menuHtml;
          }
        }

        $categories_data = self::getCategories();
        $categories_tree = self::getCategoriesTree($categories_data);
        $categories_html = self::getHtml($categories_tree);
        
        if($cacheKey){
          cache([$cacheKey=> $categories_html], now()->addDay());
          
        }

       return $categories_html;

     


    }

    public static function getHtml(array $data_tree, $tab = ''): string
    {

     $html_str = '';
     foreach($data_tree as $id => $val){
        $html_str.= self::getValTemplate($id, $val, $tab);
     }

       return $html_str;

    }

    public static function getValTemplate($id, $val, $tab): string
    {

      ob_start();
      
      echo view(self::$tpl, ['id'=>$id, 'val'=>$val, 'tab'=>$tab]);
      return ob_get_clean();

    }


    public static function getCategories()
    {
           
    $categories_data = Container::get('categories_data');
        if(!$categories_data){
                $categories_data = \App\Models\Category::all()->keyBy('id')->toArray();
                Container::set('categories_data', $categories_data);
        }


        return $categories_data;
    }

    public static function getCategoriesTree($data): array
    {
      $data_tree = [];
      foreach($data as $id => &$item){
        if(!$item['parent_id']){
          $data_tree[$id] = &$item;
        } else{
          $data[$item['parent_id']]['children'][$id] = &$item;
        }

        
      }


      return $data_tree;
    }


    public static function getIds(int $category_id): string //3
    {
      $ids = '';

      $categories = self::getCategories();
      foreach($categories as $category){
        if($category['parent_id'] == $category_id){
             $ids.= $category['id'] . ',';
             $ids.= self::getIds($category['id']); // 7
        }

      }

      return $ids;



    }






}