<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Boot the trait. Laravel akan otomatis memanggil fungsi 
     * dengan nama boot[NamaTrait] saat model di-boot.
     */
    protected static function bootLogsActivity()
    {
        // Log saat Data Dibuat
        static::created(function ($model) {
            self::recordActivity($model, 'created', 'Membuat data baru');
        });

        // Log saat Data Diupdate
        static::updated(function ($model) {
            self::recordActivity($model, 'updated', 'Mengubah data');
        });

        // Log saat Data Dihapus (Destructive)
        static::deleted(function ($model) {
            self::recordActivity($model, 'deleted', 'Menghapus data');
        });
    }

    protected static function recordActivity(Model $model, string $action, string $description)
    {
        // Siapkan data perubahan (Old vs New)
        $properties = [];

        if ($action === 'updated') {
            // Ambil hanya kolom yang berubah (Dirty)
            foreach ($model->getDirty() as $key => $value) {
                // Abaikan kolom timestamp
                if (in_array($key, ['updated_at', 'created_at'])) continue;

                $properties['old'][$key] = $model->getOriginal($key);
                $properties['attributes'][$key] = $value;
            }
        } elseif ($action === 'created') {
            $properties['attributes'] = $model->getAttributes();
        } elseif ($action === 'deleted') {
            $properties['old'] = $model->getAttributes();
        }

        // Simpan ke Database
        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => $action,
            'description'  => $description,
            'subject_type' => get_class($model),
            'subject_id'   => $model->id,
            'properties'   => count($properties) > 0 ? $properties : null,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);
    }
}
