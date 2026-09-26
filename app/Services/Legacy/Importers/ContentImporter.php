<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;

class ContentImporter
{
    public function __construct(private LegacyImportSupport $support)
    {
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $this->blogCategories($stats);
        $this->blogs($stats);
        $this->faqs($stats);
        $this->comments($stats);
        $this->contacts($stats);
        foreach (['blog_categories', 'blogs', 'faqs', 'comments', 'contacts'] as $table) {
            $this->support->realignAutoIncrement($table);
        }

        return $stats;
    }

    private function blogCategories(LegacyImportStats $stats): void
    {
        $this->support->eachPending('post_categories', function ($row) use ($stats) {
            $id = (int) $row->id;
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $url = LegacyMapper::normalizeRedirect($row->url ?? null);
            $this->support->copyRow('post_categories', $id, 'blog_categories', [
                'id' => $id,
                'title' => LegacyMapper::combineText($row->name ?? null) ?? ('blog-category-' . $id),
                'description' => $row->description ?? null,
                'url' => $url,
                'type' => in_array($url, ['videos', 'video'], true) ? 'video' : 'text',
                'image' => null,
                'status' => 1,
                'parent_id' => null,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ], $stats);
        });
    }

    private function blogs(LegacyImportStats $stats): void
    {
        $this->support->eachPending('posts', function ($row) use ($stats) {
            $id = (int) $row->id;
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('posts', $id, 'blogs', [
                'id' => $id,
                'title' => LegacyMapper::combineText($row->title ?? null) ?? ('post-' . $id),
                'description' => $row->body ?? null,
                'url' => LegacyMapper::normalizeRedirect($row->url ?? null),
                'image' => null,
                'status' => 1,
                'parent_id' => LegacyMapper::positiveInt($row->category_id ?? null),
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ], $stats);
        });
    }

    private function faqs(LegacyImportStats $stats): void
    {
        $this->support->eachPending('questions', function ($row) use ($stats) {
            $id = (int) $row->id;
            $question = LegacyMapper::faqQuestion($row->title ?? null, $row->question ?? null);
            if ($question === null) {
                $this->support->mark('questions', $id);
                $stats->skipped++;

                return;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('questions', $id, 'faqs', [
                'id' => $id,
                'faqable_id' => null,
                'faqable_type' => null,
                'question' => $question,
                'answer' => $row->answer ?? null,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        });
    }

    private function comments(LegacyImportStats $stats): void
    {
        $this->support->eachPending('comments', function ($row) use ($stats) {
            $id = (int) $row->id;
            $morph = LegacyMapper::seoMorph($row->commentable_type ?? null);
            $commentableId = LegacyMapper::positiveInt($row->commentable_id ?? null);
            if ($morph === null || $commentableId === null) {
                $this->support->mark('comments', $id);
                $stats->skipped++;

                return;
            }
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('comments', $id, 'comments', [
                'id' => $id,
                'commentable_id' => $commentableId,
                'commentable_type' => $morph,
                'status' => (int) ($row->status ?? 0),
                'user_id' => null,
                'reply_id' => LegacyMapper::positiveInt($row->parent_id ?? null),
                'name' => $row->name ?? null,
                'mobile' => $row->phone ?? null,
                'content' => $row->message ?? null,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        });
    }

    private function contacts(LegacyImportStats $stats): void
    {
        $this->support->eachPending('contacts', function ($row) use ($stats) {
            $id = (int) $row->id;
            $email = trim((string) ($row->email ?? ''));
            $address = trim((string) ($row->address ?? ''));
            $message = LegacyMapper::combineText(
                $row->message ?? null,
                $email !== '' ? 'ایمیل: ' . $email : null,
                $address !== '' ? 'آدرس: ' . $address : null
            );
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $this->support->copyRow('contacts', $id, 'contacts', [
                'id' => $id,
                'name' => $row->name ?? null,
                'mobile' => $row->mobile ?? null,
                'title' => $row->subject ?? null,
                'message' => $message,
                'status' => (int) ($row->read_at ?? 0) === 1 ? 1 : 0,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => LegacyMapper::nullableTimestamp($row->deleted_at ?? null),
            ], $stats);
        }, 400);
    }
}
