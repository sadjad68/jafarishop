<?php

namespace App\Services;

class ProductImportResult
{
    private int $totalRows = 0;
    private int $imported = 0;
    private int $updated = 0;
    private int $failed = 0;

    /** @var array<int, array{name: string, id: int}> */
    private array $importedRows = [];

    /** @var array<int, array{name: string, id: int}> */
    private array $updatedRows = [];

    /** @var array<int, array{name: string, errors: array<string>}> */
    private array $failedRows = [];

    /** @var array<string> */
    private array $globalErrors = [];

    public function setTotalRows(int $n): void
    {
        $this->totalRows = $n;
    }

    public function addImported(int $rowNumber, string $name, int $id): void
    {
        $this->imported++;
        $this->importedRows[$rowNumber] = ['name' => $name, 'id' => $id];
    }

    public function addUpdated(int $rowNumber, string $name, int $id): void
    {
        $this->updated++;
        $this->updatedRows[$rowNumber] = ['name' => $name, 'id' => $id];
    }

    public function addFailedRow(int $rowNumber, string $name, array $errors): void
    {
        $this->failed++;
        $this->failedRows[$rowNumber] = ['name' => $name, 'errors' => $errors];
    }

    public function addGlobalError(string $message): void
    {
        $this->globalErrors[] = $message;
    }

    public function getTotalRows(): int
    {
        return $this->totalRows;
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getUpdatedCount(): int
    {
        return $this->updated;
    }

    public function getFailedCount(): int
    {
        return $this->failed;
    }

    /** @return array<int, array{name: string, id: int}> */
    public function getImportedRows(): array
    {
        return $this->importedRows;
    }

    /** @return array<int, array{name: string, id: int}> */
    public function getUpdatedRows(): array
    {
        return $this->updatedRows;
    }

    /** @return array<int, array{name: string, errors: array<string>}> */
    public function getFailedRows(): array
    {
        return $this->failedRows;
    }

    /** @return array<string> */
    public function getGlobalErrors(): array
    {
        return $this->globalErrors;
    }

    public function hasGlobalErrors(): bool
    {
        return !empty($this->globalErrors);
    }
}
