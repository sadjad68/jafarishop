<?php

namespace App\Http\Controllers;

use App\Modules\User\Entities\UserType;
use App\Services\Legacy\TopickalaLegacyImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Throwable;

class LegacyImportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizeToken($request);

        return response()->json([
            'steps' => $this->steps(),
        ]);
    }

    public function prepare(Request $request): JsonResponse
    {
        return $this->runSection($request, 'prepare');
    }

    public function catalog(Request $request): JsonResponse
    {
        return $this->runSection($request, 'catalog');
    }

    public function content(Request $request): JsonResponse
    {
        return $this->runSection($request, 'content');
    }

    public function services(Request $request): JsonResponse
    {
        return $this->runSection($request, 'services');
    }

    public function users(Request $request): JsonResponse
    {
        return $this->runSection($request, 'users');
    }

    public function settings(Request $request): JsonResponse
    {
        return $this->runSection($request, 'settings');
    }

    public function commerce(Request $request): JsonResponse
    {
        return $this->runSection($request, 'commerce');
    }

    public function redirects(Request $request): JsonResponse
    {
        return $this->runSection($request, 'redirects');
    }

    public function media(Request $request): JsonResponse
    {
        return $this->runSection($request, 'media');
    }

    private function runSection(Request $request, string $section): JsonResponse
    {
        $this->authorizeToken($request);
        set_time_limit(0);

        $limit = (int) $request->query('limit', 200);
        if ($limit < 1) {
            $limit = 200;
        }

        $importer = TopickalaLegacyImporter::make();
        $importer->setRowLimit($limit);

        try {
            $stats = $importer->run($section);
        } catch (InvalidArgumentException $exception) {
            abort(404, $exception->getMessage());
        } catch (Throwable $exception) {
            return response()->json([
                'section' => $section,
                'inserted' => 0,
                'skipped' => 0,
                'failed' => 1,
                'remaining' => null,
                'error' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'section' => $section,
            'inserted' => $stats->inserted,
            'skipped' => $stats->skipped,
            'failed' => $stats->failed,
            'remaining' => $importer->remaining($section),
            'limit' => $limit,
        ]);
    }

    private function authorizeToken(Request $request): void
    {
        $token = (string) $request->query('token', '');
        $expected = (string) env('AUTH_TOKEN', '');
        $tokenOk = $expected !== '' && hash_equals($expected, $token);

        $isAdmin = false;
        if (Auth::check()) {
            $isAdmin = UserType::where('user_id', Auth::id())->where('type', 'Admin')->exists();
        }

        if (!$tokenOk && !$isAdmin) {
            abort(403, 'Unauthorized.');
        }
    }

    private function steps(): array
    {
        return [
            [
                'section' => 'prepare',
                'path' => '/legacy-import/prepare',
                'depends_on' => [],
            ],
            [
                'section' => 'catalog',
                'path' => '/legacy-import/catalog',
                'depends_on' => ['prepare'],
            ],
            [
                'section' => 'content',
                'path' => '/legacy-import/content',
                'depends_on' => ['prepare'],
            ],
            [
                'section' => 'services',
                'path' => '/legacy-import/services',
                'depends_on' => ['prepare'],
            ],
            [
                'section' => 'users',
                'path' => '/legacy-import/users',
                'depends_on' => ['prepare'],
            ],
            [
                'section' => 'settings',
                'path' => '/legacy-import/settings',
                'depends_on' => ['prepare'],
            ],
            [
                'section' => 'commerce',
                'path' => '/legacy-import/commerce',
                'depends_on' => ['prepare', 'catalog', 'services', 'users'],
            ],
            [
                'section' => 'redirects',
                'path' => '/legacy-import/redirects',
                'depends_on' => ['prepare', 'catalog', 'content'],
            ],
            [
                'section' => 'media',
                'path' => '/legacy-import/media',
                'depends_on' => ['prepare', 'catalog', 'content', 'settings'],
            ],
        ];
    }
}
