<?php

namespace App\Support;

use App\Models\Media;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class NoteableRegistry
{
    /**
     * @return array<string, class-string<Model>>
     */
    public static function map(): array
    {
        return [
            'user' => User::class,
            'page' => Page::class,
            'media' => Media::class,
        ];
    }

    /**
     * @return list<string>
     */
    public static function aliases(): array
    {
        return array_keys(self::map());
    }

    public static function resolve(string $alias, int|string $id): ?Model
    {
        /** @var class-string<Model>|null $modelClass */
        $modelClass = Arr::get(self::map(), $alias);

        if ($modelClass === null) {
            return null;
        }

        return $modelClass::query()->find($id);
    }

    public static function aliasFor(Model $model): ?string
    {
        $result = collect(self::map())->search(fn (string $class): bool => $model instanceof $class);

        return is_string($result) ? $result : null;
    }
}
