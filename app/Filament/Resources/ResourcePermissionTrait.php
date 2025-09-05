<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait ResourcePermissionTrait
{
    protected static function getResourceSlug(): string
    {
        // e.g., UserResource => user
        return \Illuminate\Support\Str::kebab(str_replace('Resource', '', class_basename(static::class)));
    }

    public static function canViewAny(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user && method_exists($user, 'can') ? $user->can(static::getResourceSlug() . '.view') : false;
    }

    public static function canCreate(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user && method_exists($user, 'can') ? $user->can(static::getResourceSlug() . '.create') : false;
    }

    public static function canEdit($record): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user && method_exists($user, 'can') ? $user->can(static::getResourceSlug() . '.edit') : false;
    }

    public static function canDelete($record): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user && method_exists($user, 'can') ? $user->can(static::getResourceSlug() . '.delete') : false;
    }
} 