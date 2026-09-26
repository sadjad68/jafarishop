<?php
namespace App\Modules\Service\Services;

use App\Modules\Faq\Entities\Faq;
use App\Modules\General\Helper\FileManager;
use App\Modules\Product\DTO\VideoFaqDTO;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Video;
use App\Modules\Service\DTO\FaqDTO;

class FaqService
{
    public function create(FaqDTO $DTO)
    {
      if ($DTO->getFaqs()){
          foreach ($DTO->getFaqs() as $faq){
              if ($faq['faq_id'] == null){
                  Faq::create([
                      'question'=>$faq['question'],
                      'answer'=>$faq['answer'],
                      'faqable_id'=>$DTO->getServiceId(),
                      'faqable_type'=>'App\Modules\Service\Entities\Service',
                  ]);
              }
              else{
                  $check = Faq::findOrFail($faq['faq_id']);
                  $check->update([
                      'question'=>$faq['question'],
                      'answer'=>$faq['answer'],
                  ]);
              }
          }
      }



    }

    public function deleteFaq(int $id): void
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
    }





}
