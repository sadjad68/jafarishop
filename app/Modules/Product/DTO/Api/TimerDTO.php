<?php

namespace App\Modules\Product\DTO\Api;

use App\Modules\Product\Http\Requests\Api\TimerRequest;
use Carbon\Carbon;
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
            $timer_hour = $request->get('end_hour');
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
            $s = \jmktime($time[0], $time[1], 0, $date[1], $date[2], $date[0]);
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
            $s = \jmktime($hour[0], $hour[1], 0, $start[1], $start[2], $start[0]);
            $start_timer = Carbon::createFromTimestamp($s);
        }
        $self->end_timer = $end_timer;
        $self->start_timer = $start_timer;
        $self->timer_active = $request->get('timer_active') ? 1 : 0;
        return $self;
    }
}
