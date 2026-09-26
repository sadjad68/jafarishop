<?php
namespace App\Modules\Page\Services;

use App\Modules\General\Helper\FileManager;
use App\Modules\Page\DTO\PageDTO;
use App\Modules\Page\Entities\Page;
use App\Modules\Page\Http\Controllers\PageController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class PageService
{
    protected $model;

    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    public function create(PageDTO $pageDTO)
    {
        $page = Page::create([
            'title' => $pageDTO->getTitle(),
            'description' => $pageDTO->getDescription(),
            'url' => $pageDTO->getUrl(),
        ]);

    }
    public function update(int $id, PageDTO $pageDTO)
    {
        $page = Page::findOrfail($id);
        $page->update([
            'title' => $pageDTO->getTitle(),
            'description' => $pageDTO->getDescription(),
            'url' => $pageDTO->getUrl(),
        ]);

    }
    public function destroy(int $id)
    {
        Page::destroy($id);
    }
    public static function findOne($url)
    {
        return Page::orderBy('id','DESC')->where('url', $url)->firstOrFail();

    }
    public static function findAll($query = [],  $except_id = null)
    {
        $pages = Page::query();
        if ($except_id) {
            $pages->where('id', '<>', $except_id);
        }
        if (isset($query['select'])) {

            $pages->select($query['select']);
        }
        return $pages->orderby('id', 'DESC')->get();


    }
}
