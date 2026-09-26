<?php

namespace App\Admin\Services;

class ProductImportResult
{
    public int $totalRows = 0;
    public int $imported = 0;
    public int $updated = 0;
    public int $failed = 0;
    /** @var array<int, array{row: int, errors: string[]}> */
    public array $rowErrors = [];

    public function addRowError(int $row, string $error): void
    {
        if (! isset($this->rowErrors[$row])) {
            $this->rowErrors[$row] = ['row' => $row, 'errors' => []];
        }
        $this->rowErrors[$row]['errors'][] = $error;
    }

    public function addRowErrors(int $row, array $errors): void
    {
        foreach ($errors as $error) {
            $this->addRowError($row, $error);
        }
    }

    public function getRowErrors(): array
    {
        return array_values($this->rowErrors);
    }
}
