<?php

namespace App\Providers;

use App\Models\AuditLog;
use App\Models\Dokumen;
use App\Models\Kendaraan;
use App\Models\Pic;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $oldValues = [];

        $models = [
            Kendaraan::class => 'kendaraan',
            Dokumen::class => 'dokumen',
            Pic::class => 'pic',
            User::class => 'user',
        ];

        foreach ($models as $modelClass => $prefix) {
            $modelClass::created(function (Model $model) use ($prefix) {
                self::recordAudit(
                    $model,
                    $prefix . '_created',
                    [],
                    $model->getAttributes()
                );
            });

            $modelClass::updating(function (Model $model) use (&$oldValues) {
                $key = spl_object_id($model);

                $oldValues[$key] = collect($model->getDirty())
                    ->mapWithKeys(fn($value, $attribute) => [
                        $attribute => $model->getRawOriginal($attribute),
                    ])
                    ->all();
            });

            $modelClass::updated(function (Model $model) use ($prefix, &$oldValues) {
                $key = spl_object_id($model);
                $before = $oldValues[$key] ?? [];

                unset($oldValues[$key]);

                self::recordAudit(
                    $model,
                    $prefix . '_updated',
                    $before,
                    $model->getChanges()
                );
            });

            $modelClass::deleted(function (Model $model) use ($prefix) {
                self::recordAudit(
                    $model,
                    $prefix . '_deleted',
                    $model->getAttributes(),
                    []
                );
            });
        }
    }

    protected static function recordAudit(
        Model $model,
        string $action,
        array $oldValues = [],
        array $newValues = []
    ): void {
        $filter = fn(array $values) => collect($values)
            ->except([
                'password',
                'remember_token',
                'created_at',
                'updated_at',
            ])
            ->all();

        $actor = Auth::user();

        $item = $model->nomor_unit
            ?? $model->plat_nomor
            ?? $model->jenis_dokumen
            ?? $model->nama
            ?? $model->name
            ?? ('ID ' . $model->getKey());

        AuditLog::create([
            'user_id' => $actor?->id,
            'user_name' => $actor?->name ?? 'Sistem',
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'description' => ucfirst(str_replace('_', ' ', $action))
                . ': ' . $item,
            'old_values' => $filter($oldValues),
            'new_values' => $filter($newValues),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}