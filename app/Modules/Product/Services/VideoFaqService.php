<?php
namespace App\Modules\Product\Services;

use App\Modules\Faq\Entities\Faq;
use App\Modules\General\Helper\FileManager;
use App\Modules\Product\DTO\VideoFaqDTO;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Video;

class VideoFaqService
{
    public function create(VideoFaqDTO $DTO)
    {
        if ($DTO->getVideos()){
            foreach ($DTO->getVideos() as $video){
                if ($video['video_id'] == null){
                    Video::create([
                        'code'=>$video['code'],
                        'product_id'=>$DTO->getProductId(),
                    ]);
                }
                else{
                    $check = Video::findOrFail($video['video_id']);
                    $check->update([
                        'code'=>$video['code'],
                    ]);
                }
            }
        }
      if ($DTO->getFaqs()){
          foreach ($DTO->getFaqs() as $faq){
              if ($faq['faq_id'] == null){
                  Faq::create([
                      'question'=>$faq['question'],
                      'answer'=>$faq['answer'],
                      'faqable_id'=>$DTO->getProductId(),
                      'faqable_type'=>'App\Modules\Product\Entities\Product',
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

    public function deleteVideo(int $id): void
    {
        $video = Video::findOrFail($id);
        $video->delete();
    }
    public function deleteFaq(int $id): void
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
    }





}
