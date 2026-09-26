<?php

namespace App\Modules\Product\DTO;

use App\Modules\Blog\Entities\Blog;
use App\Modules\Blog\Http\Requests\BlogRequest;
use App\Modules\Product\Http\Requests\TimerRequest;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use function App\Modules\Blog\DTO\jmktime;

class TimerDTO
{

    protected string|null $end_timer;

    public function getTimerDate(): string|null
    {

        return $this->end_timer;
    }
    protected string|null $start_timer;

    public function getStartTimer(): string|null
    {

        return $this->start_timer;
    }
    protected string|null $timer_hour;

    public function getTimerHour(): string|null
    {

        return $this->timer_hour;
    }
    protected string|null $start_hour;

    public function getStartHour(): string|null
    {

        return $this->start_hour;
    }


    protected string $product_id;
    public function getProductId() : int
    {
        return $this->product_id;
    }
    protected int|null $timer_active;

    public function getTimerActive(): ?int
    {
        return $this->timer_active;
    }

    public static function fromRequest(TimerRequest $request)
    {
        $self = new self();
        $end_timer = null;
        $start_timer = null;
        if ($request->get('end_timer')) {
            $date = explode('/', $request->get('end_timer'));
            $timer_hour = $request->get('timer_hour');
            if (empty($timer_hour)) {
                $timer_hour = '00:00';
            } else {
                $time = explode(':', $timer_hour);

                // اگر فقط ساعت وارد شده بود
                if (count($time) == 1) {
                    $timer_hour = $time[0] . ':00';
                }
            }
            $time= explode(':', $timer_hour);
            $s = \jmktime($time[0], $time[1], 0, $date[1], $date[0], $date[2]);
            $end_timer = Carbon::createFromTimestamp($s);
        }
        if ($request->get('start_timer')) {
            $start = explode('/', $request->get('start_timer'));
            $ti_hour = $request->get('start_hour');
            if (empty($ti_hour)) {
                $ti_hour = '00:00';
            } else {
                $time = explode(':', $ti_hour);
                // اگر فقط ساعت وارد شده بود
                if (count($time) == 1) {
                    $ti_hour = $time[0] . ':00';
                }
            }
            $hour= explode(':', $ti_hour);
            $s = \jmktime($hour[0], $hour[1], 0, $start[1], $start[0], $start[2]);
            $start_timer = Carbon::createFromTimestamp($s);
        }
        $self->end_timer = $end_timer;
        $self->start_timer = $start_timer;
        $self->product_id = $request->get('product_id');
        $self->timer_active = $request->has('timer_active') ? 1 : 0;
        return $self;
    }
}
