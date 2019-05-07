<?php

namespace Helper;

use File;
use \Intervention\Image\Facades\Image as Image;

/**
 * Description of AppHelper
 *
 * @author Dinesh Rabara <dinesh.rabara@gmail.com>
 */
class AppHelper {

    private $user;
    private $class;
    private $path;
    private $is_public = true;
    private $size;
    private $defaultImage;

    //put your code here

    /**
     * Set default image
     * @param string $defaultImage image string path
     */
    public function setDefaultImage($defaultImage) {
        $this->defaultImage = $defaultImage;
        return $this;
    }

    /**
     * Get Default image
     * @return string default image path
     */
    public function getDefaultImage() {
        return $this->defaultImage;
    }

    /**
     * Set path
     * @param  string $path path
     * @return Obj  return object
     */
    public function path($path, $is_public = true) {
        $this->path = $path;
        $this->is_public = $is_public;
        return $this;
    }

    /**
     * To set the size
     * @param  string $size size
     * @return Obj Description
     */
    public function size($size) {
        $this->size = $size;
        return $this;
    }

    /**
     * To set current class
     * @param class name space
     */
    public function setClass($class) {

        $this->class = $class;
        return $this;
    }

    /**
     * To get class using name space
     * @param class name space
     */
    public function getClass() {
        return $this->class;
    }

    /**
     *
     * @param type $file_name
     * @return type
     * Example :
     * AppHelper::path('/uploads/images/faq/')
     * ->size('10x10')
     * ->getImageUrl($faqcategory->icon)
     *
     * {{HTML::image(AppHelper::size('50x50')
     * ->path('/uploads/images/faq/')
     * ->getImageUrl($faqcategory->icon))}}
     */
    public function getImageUrl($file_name = null) {
        if (!empty($this->size)) {
            $url = $this->imageSize($this->path . $file_name, $this->size);
        } else {
            $url = $this->path . $file_name;
        }
        return ($url);
    }

    /**
     *
     * @param type $file_name
     * @return type
     * Example :
     * {{AppHelper::path('uploads/images/abc/')
     * ->getImagePath($faqcategory->icon)}}
     */
    public function getImagePath($file_name = '') {
        if ($this->is_public) {
            $path = public_path($this->path);
        } else {
            $path = storage_path($this->path);
        }

        if (\File::isDirectory($path) === false) {
            \File::makeDirectory($path, 0777, true);
            $this->createIndexHtmlFile($path);
        }
        return $path . $file_name;
    }

    private function imageSize($path, $size) {
        $real_path = public_path($path);
        if (File::isFile($real_path) === false) {
            //$path = 'uploads/images/default/default.jpg';
            $path = $this->defaultImage;
            $real_path = public_path($path);
        }
        list($width, $height) = explode('x', $size);
        $file_name = pathinfo($real_path, PATHINFO_BASENAME);
        $new_image_path = pathinfo($real_path, PATHINFO_DIRNAME) . '/' . $size;
        if (File::isDirectory($new_image_path) === false) {
            File::makeDirectory($new_image_path, 0777);
            $this->createIndexHtmlFile($new_image_path);
        }
        $new_image_path .= '/' . $file_name;
        if (File::isFile($new_image_path) === false) {
            Image::make($real_path)->resize($width, $height)->save($new_image_path);
        }
        return pathinfo($path, PATHINFO_DIRNAME) . '/' . $size . '/' . $file_name;
    }

    private function createIndexHtmlFile($path) {
        $path = str_finish($path, '/');
        if (File::isFile("{$path}index.html") === false) {
            File::put("{$path}index.html", '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
    }

    /**
     *
     * @param type $fileInput
     * @param type $destination
     * @return type
     * AppHelper::getUniqueFilename(Input::file('image'),AppHelper::getImagePath());
     */
    public function getUniqueFilename($fileInput, $destination) {
        $filename = $fileInput->getClientOriginalName();
        $i = 0;
        $path_parts = pathinfo($filename);
        $path_parts['filename'] = str_slug($path_parts['filename'], '-');
        $filename = $path_parts['filename'];
        while (File::exists($destination . '/' . $filename . '.' . $path_parts['extension'])) {
            $filename = $path_parts['filename'] . '-' . $i;
            $i++;
        }
        return $filename . '.' . $path_parts['extension'];
    }

    /**
     *  Use for trimming string input excludes object(image file),array,integer etc !!
     * @param  [type] $data array of input
     * @return [type]       Trimmed input
     */
    public function getTrimmedData($data) {
        $input = array_map(function ($value) {
            if (gettype($value) === 'string') {
                $value = str_replace('__:__', '', $value);
                $value = str_replace('__-__-____', '', $value);
                $value = str_replace('__/__/____', '', $value);
                $value = str_replace('_____ _____', '', $value);
                return trim($value);
            }
            return $value;
        }, $data);
        return $input;
    }

    public function get_operator($index) {
        $input = ['1' => '=', '2' => '!=', '3' => '<', '4' => '>', '5' => '<=', '6' => '>=', '7' => 'like', '8' => 'not like'];
        if (isset($input[$index])) {
            return $input[$index];
        } else {
            return $input[1];
        }
    }
    /* Useing genrate activation code */
    public function GenrateCode()
    {
        $code = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'),array(time('ymdhis')));
        $max = count($characters) - 1;
        for ($i = 0; $i < 35; $i++) {
            $rand = mt_rand(0, $max);
            $code .= $characters[$rand];
        }
        return $code;
    }
      /**
     * [this function is use for sort array by key in multidimensional for more use this link: http://php.net/manual/en/function.sort.php]
     * @param  [type] $array [description]
     * @param  [type] $on    [description]
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
     public function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                break;
                case SORT_DESC:
                    arsort($sortable_array);
                break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
}