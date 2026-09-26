<?php

namespace App\Http\Controllers\Torob;

use App\Http\Controllers\Controller;
use App\Services\Torob\TorobProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TorobProductController extends Controller
{
    public function __construct(
        private TorobProductService $torobProductService,

    ) {
    }

    public function products(Request $request): JsonResponse
    {
        if ($request->isMethod('GET') && !config('torob.skip_auth')) {
            return $this->error('Method not allowed', 405);
        }

        $payload = $this->resolvePayload($request);

        if ($payload === false) {
            return $this->error('request body is invalid or empty');
        }

        if ($payload === []) {
            return $this->error('request body is empty');
        }

        $type = $this->detectRequestType($payload);

        return match ($type) {
            'page_urls' => $this->handlePageUrls($payload),
            'page_uniques' => $this->handlePageUniques($payload),
            'pagination' => $this->handlePagination($payload),
            'missing_sort' => $this->error('sort parameter is not provided'),
            'missing_page' => $this->error('page parameter is not provided'),
            default => $this->error('invalid request parameters'),
        };
    }

    public function viewProduct($slug,Request $request): JsonResponse
    {
        if ($request->isMethod('GET') && !config('torob.skip_auth')) {
            return $this->error('Method not allowed', 405);
        }

        return $this->jsonResponse(
            $this->torobProductService->findByPageUnique($slug)
        );
    }

    private function handlePageUrls(array $payload): JsonResponse
    {
        if (!$this->isNonEmptyStringList($payload['page_urls'])) {
            return $this->error('page_urls must be a non-empty list of strings');
        }

        return $this->jsonResponse($this->torobProductService->byPageUrls($payload['page_urls']));
    }

    private function handlePageUniques(array $payload): JsonResponse
    {
        if (!$this->isNonEmptyStringList($payload['page_uniques'])) {
            return $this->error('page_uniques must be a non-empty list of strings');
        }

        return $this->jsonResponse($this->torobProductService->byPageUniques($payload['page_uniques']));
    }

    private function handlePagination(array $payload): JsonResponse
    {
        $page = $payload['page'];
        $sort = $payload['sort'];

        if (!$this->isPositiveInteger($page)) {
            return $this->error('page must be a positive integer');
        }

        if (!is_string($sort)) {
            return $this->error('sort must be a string');
        }

        if (!in_array($sort, ['date_added_desc', 'date_updated_desc'], true)) {
            return $this->error('sort must be date_added_desc or date_updated_desc');
        }

        return $this->jsonResponse(
            $this->torobProductService->paginated((int) $page, $sort)
        );
    }

    /**
     * @return 'page_urls'|'page_uniques'|'pagination'|'missing_sort'|'missing_page'|'invalid'
     */
    private function detectRequestType(array $payload): string
    {
        $keys = array_keys($payload);

        $hasPageUrls = in_array('page_urls', $keys, true);
        $hasPageUniques = in_array('page_uniques', $keys, true);
        $hasPage = in_array('page', $keys, true);
        $hasSort = in_array('sort', $keys, true);
        $hasPagination = $hasPage || $hasSort;

        if ((int) $hasPageUrls + (int) $hasPageUniques + (int) $hasPagination > 1) {
            return 'invalid';
        }

        if ($hasPageUrls) {
            return $keys === ['page_urls'] ? 'page_urls' : 'invalid';
        }

        if ($hasPageUniques) {
            return $keys === ['page_uniques'] ? 'page_uniques' : 'invalid';
        }

        if ($hasPage && !$hasSort) {
            return 'missing_sort';
        }

        if ($hasSort && !$hasPage) {
            return 'missing_page';
        }

        if ($hasPage && $hasSort) {
            return $keys === ['page', 'sort'] ? 'pagination' : 'invalid';
        }

        return 'invalid';
    }

    private function isPositiveInteger(mixed $value): bool
    {
        if (is_int($value)) {
            return $value >= 1;
        }

        if (is_string($value) && ctype_digit($value)) {
            return (int) $value >= 1;
        }

        return false;
    }

    private function isNonEmptyStringList(mixed $value): bool
    {
        if (!is_array($value) || $value === []) {
            return false;
        }

        foreach ($value as $item) {
            if (!is_string($item) || trim($item) === '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array|false
     */
    private function resolvePayload(Request $request): array|false
    {
        if (config('torob.skip_auth')) {
            if ($request->isMethod('GET')) {
                return $this->parseLocalQuery($request);
            }

            $json = $this->parseJsonBody($request);
            if ($json !== false) {
                return $json;
            }

            $query = $this->parseLocalQuery($request);
            if ($this->detectRequestType($query) !== 'invalid') {
                return $query;
            }

            return ['page' => 1, 'sort' => 'date_added_desc'];
        }

        return $this->parseJsonBody($request);
    }

    private function parseLocalQuery(Request $request): array
    {
        if ($request->filled('page_urls')) {
            $urls = $request->query('page_urls');
            if (is_string($urls)) {
                $decoded = json_decode($urls, true);
                $urls = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $urls)));
            }

            return ['page_urls' => is_array($urls) ? $urls : [$urls]];
        }

        if ($request->filled('page_uniques')) {
            $uniques = $request->query('page_uniques');
            if (is_string($uniques)) {
                $decoded = json_decode($uniques, true);
                $uniques = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $uniques)));
            }

            return ['page_uniques' => is_array($uniques) ? $uniques : [$uniques]];
        }

        $page = $request->query('page', 1);
        $sort = $request->query('sort', 'date_added_desc');

        return [
            'page' => is_numeric($page) ? (int) $page : 1,
            'sort' => (string) $sort,
        ];
    }

    /**
     * @return array|false
     */
    private function parseJsonBody(Request $request): array|false
    {
        $raw = trim($request->getContent());
        $raw = ltrim($raw, "\xEF\xBB\xBF");

        if ($raw === '' || !str_starts_with($raw, '{')) {
            return false;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded) || json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        if (isset($decoded['page']) && is_string($decoded['page']) && ctype_digit($decoded['page'])) {
            $decoded['page'] = (int) $decoded['page'];
        }

        return $decoded;
    }

    private function jsonResponse(array $data): JsonResponse
    {
        return response()->json(
            $data,
            200,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function error(string $message, int $status = 400): JsonResponse
    {
        return response()->json(
            ['error' => $message],
            $status,
            ['Content-Type' => 'application/json; charset=UTF-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
}
