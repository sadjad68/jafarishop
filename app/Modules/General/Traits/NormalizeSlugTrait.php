<?php
namespace App\Modules\General\Traits;

trait NormalizeSlugTrait{

    public function cleanSlug($field = 'url'): void
    {
        if ($this->has($field)) {
            $clean = $this->input($field);

            $clean = trim($clean);

            // فاصله و _ رو با - جایگزین کن
            $clean = str_replace([' ', '_'], '-', $clean);

            // اسلش‌های تکراری رو یکی کن
            $clean = preg_replace('#/+#', '/', $clean);

            // حروف رو کوچیک کن
            $clean = strtolower($clean);



            $this->merge([$field => $clean]);
        }
    }
}
