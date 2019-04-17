<?php

namespace InetStudio\PersonsPackage\Persons\Models;

use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use InetStudio\Meta\Models\Traits\Metable;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\ACL\Users\Models\Traits\HasUser;
use InetStudio\Uploads\Models\Traits\HasImages;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use InetStudio\Classifiers\Models\Traits\HasClassifiers;
use InetStudio\AdminPanel\Base\Models\Traits\SluggableTrait;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;

/**
 * Class PersonModel.
 */
class PersonModel extends Model implements PersonModelContract
{
    use HasUser;
    use Metable;
    use Auditable;
    use HasImages;
    use Sluggable;
    use Searchable;
    use SoftDeletes;
    use HasClassifiers;
    use SluggableTrait;
    use BuildQueryScopeTrait;

    /**
     * Часть слага для сущности.
     */
    const HREF = '/person/';

    const ENTITY_TYPE = 'person';

    /**
     * Should the timestamps be audited?
     *
     * @var bool
     */
    protected $auditTimestamps = true;

    /**
     * Настройки для генерации изображений.
     *
     * @var array
     */
    protected $images = [
        'config' => 'persons',
        'model' => 'person',
    ];

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'persons';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'post',
        'description',
        'content',
        'user_id',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Настройка полей для поиска.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $arr = Arr::only($this->toArray(), ['id', 'name', 'post', 'description', 'content']);

        return $arr;
    }

    /**
     * Возвращаем конфиг для генерации slug модели.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'unique' => true,
                'includeTrashed' => true,
            ],
        ];
    }

    /**
     * Загрузка модели.
     */
    protected static function boot()
    {
        parent::boot();

        self::$buildQueryScopeDefaults['columns'] = [
            'id',
            'user_id',
            'name',
            'post',
            'slug',
        ];

        self::$buildQueryScopeDefaults['relations'] = [
            'meta' => function (MorphMany $metaQuery) {
                $metaQuery->select(['metable_id', 'metable_type', 'key', 'value']);
            },

            'classifiers' => function (MorphToMany $classifiersQuery) {
                $classifiersQuery->select(
                    ['classifiers_entries.id', 'classifiers_entries.value', 'classifiers_entries.alias']
                );
            },

            'media' => function (MorphMany $mediaQuery) {
                $mediaQuery->select(
                    ['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk', 'custom_properties']
                );
            },
        ];
    }

    /**
     * Сеттер атрибута name.
     *
     * @param $value
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = strip_tags($value);
    }

    /**
     * Сеттер атрибута slug.
     *
     * @param $value
     */
    public function setSlugAttribute($value): void
    {
        $this->attributes['slug'] = strip_tags($value);
    }

    /**
     * Сеттер атрибута post.
     *
     * @param $value
     */
    public function setPostAttribute($value): void
    {
        $value = (isset($value['text'])) ? $value['text'] : (! is_array($value) ? $value : '');

        $this->attributes['post'] = trim(str_replace('&nbsp;', ' ', strip_tags($value)));
    }

    /**
     * Сеттер атрибута description.
     *
     * @param $value
     */
    public function setDescriptionAttribute($value): void
    {
        $value = (isset($value['text'])) ? $value['text'] : (! is_array($value) ? $value : '');

        $this->attributes['description'] = trim(str_replace('&nbsp;', ' ', $value));
    }

    /**
     * Сеттер атрибута content.
     *
     * @param $value
     */
    public function setContentAttribute($value): void
    {
        $value = (isset($value['text'])) ? $value['text'] : (! is_array($value) ? $value : '');

        $this->attributes['content'] = trim(str_replace('&nbsp;', ' ', $value));
    }

    /**
     * Сеттер атрибута user_id.
     *
     * @param $value
     */
    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = (int) trim(strip_tags($value));
    }

    /**
     * Геттер атрибута type.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    /**
     * Геттер атрибута href.
     *
     * @return string
     */
    public function getHrefAttribute(): string
    {
        return url(self::HREF.($this->slug ?: $this->id));
    }

    /**
     * Выборка объектов по типу.
     *
     * @param  Builder  $query
     * @param  string  $type
     * @param  array  $params
     *
     * @return Builder
     */
    public function scopeItemsByType(Builder $query, string $type = '', array $params = []): Builder
    {
        $query->buildQuery($params);

        if ($type) {
            $query->whereHas(
                'classifiers',
                function ($classifiersQuery) use ($type) {
                    $classifiersQuery->where('classifiers_entries.alias', $type);
                }
            );
        } else {
            $query->whereHas('classifiers');
        }

        return $query;
    }
}
