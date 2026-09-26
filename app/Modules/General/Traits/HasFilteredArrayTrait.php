<?php
namespace App\Modules\General\Traits;

trait HasFilteredArrayTrait{

    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            if ($key === 'originalData') continue;

            // فقط فیلدهایی که کاربر واقعاً فرستاده یا آرایه خالی هستن
            if (array_key_exists($key, $this->originalData) || (is_array($value) && empty($value))) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
