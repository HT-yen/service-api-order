<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepositoryInterface as CategoryRepository;
use Illuminate\Http\Response;

class XMLResponse extends ApiController
{
    protected $categoryRepository;

    /**
     * UserAPIController constructor.
     *
     * @param User $user Dependence injection
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $xml = new \SimpleXMLElement('<rootTag/>');
        // foreach ($this->categoryRepository->all() as $category) {
        //     $this->to_xml($xml, $category);
        // }

        $this->to_xml($xml, $this->categoryRepository->all());

        header('Content-type: text/xml');
        return $xml->asXML();
    }

    function to_xml(\SimpleXMLElement $object, $data, $level = 0)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild(($level == 0) ? 'marker' : $key);
                to_xml($new_object, $value, $level + 1);
            } else {
                $object->addChild($key, $value);
            }
        }
    }

}
