<?php

namespace App\Modules\Blog\DTO\Api;

use App\Modules\Blog\Http\Requests\Api\BlogRequest;
use Carbon\Carbon;
use App\Modules\General\Traits\HasFilteredArrayTrait;

class BlogDTO
{
    use HasFilteredArrayTrait;

    public ?string $title;
    public ?string $description;
    public ?int $parent_id;
    public ?bool $call_to_action;
    public ?bool $show_in_first_page;
    public ?string $author;
    public ?string $publish_date;
    public ?string $url;
    public ?string $title_seo;
    public ?string $description_seo;
    public ?array $services;
    public ?array $seo;

    protected array $originalData;

    public function __construct(array $data)
    {
        $this->originalData = $data;

        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->parent_id = isset($data['parent_id']) ? (int)$data['parent_id'] : null;
        $this->call_to_action = array_key_exists('call_to_action', $data) ? (bool)$data['call_to_action'] : null;
        $this->show_in_first_page = array_key_exists('show_in_first_page', $data) ? (bool)$data['show_in_first_page'] : null;
        $this->author = $data['author'] ?? null;
        $this->url = isset($data['url']) ? trim(str_replace(' ', '-', $data['url'])) : null;

        // تاریخ انتشار
        if (@$data['publish_date']) {
            $publish = explode('/', $data['publish_date']);
            if (count($publish) === 3) {
                // حذف صفرهای اضافه و تبدیل به int
                $day = intval($publish[2]);   // توجه: روز آخر هست
                $month = intval($publish[1]);
                $year = intval($publish[0]);

                // jmktime شمسی به timestamp
                $s = jmktime(0, 0, 0, $month, $day, $year);
                $this->publish_date = Carbon::createFromTimestamp($s);
            }
        } else {
            $this->publish_date = null;
        }



        $this->seo = $data['seo'] ?? [];
        $this->services = $data['services'] ?? [];
    }

    public static function fromRequest(BlogRequest $request): self
    {
        return new self($request->all());
    }

}
