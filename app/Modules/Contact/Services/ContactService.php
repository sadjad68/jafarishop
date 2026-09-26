<?php
namespace App\Modules\Contact\Services;


use App\Modules\Contact\DTO\ContactDTO;
use App\Modules\Contact\Entities\Contact;


class ContactService
{
    protected $model;

    public function __construct(Contact $model)
    {
        $this->model = $model;
    }

    public static function create(ContactDTO $contactDTO)
    {
        Contact::create([
            'title' => $contactDTO->getTitle(),
            'name' => $contactDTO->getName(),
            'mobile' => $contactDTO->getMobile(),
            'message' => $contactDTO->getMessage(),

        ]);

    }

    public function destroy(int $id)
    {
        $this->model::destroy($id);
    }
    public function changeStatus(int $id)
    {
        $contact = $this->model::findOrfail($id);
        $contact ->update([
           'status'=>1
        ]);
    }
}
