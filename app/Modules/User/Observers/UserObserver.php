<?php

namespace App\Modules\User\Observers;

use App\Modules\User\Entities\User;



class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Modules\User\Entities\User $item
     * @return void
     */
    public function created(User $item)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Modules\User\Entities\User $item
     * @return void
     */
    public function updated(User $item)
    {


    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Modules\User\Entities\User $item
     * @return void
     */
    public function deleted(User $item)
    {
            $item->roles()->detach();
        //
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Modules\User\Entities\User $item
     * @return void
     */
    public function forceDeleted(User $item)
    {
        //
    }
}
