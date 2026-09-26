<?php

namespace App\Modules\Course\Http\Controllers;

use App\Modules\Course\Entities\SessionFile;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

class SessionFileController extends Controller
{

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $sessionFile = SessionFile::findOrfail($id);
        if ($sessionFile->file != null) {
            File::delete('assets/uploads/Session/' . $sessionFile->file);
        }
        $sessionFile->destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }
}
