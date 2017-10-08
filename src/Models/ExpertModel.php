<?php

namespace InetStudio\Experts\Models;

use Cocur\Slugify\Slugify;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\Media;
use Phoenix\EloquentMeta\MetaTrait;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

/**
 * InetStudio\Experts\Models\ExpertModel.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $post
 * @property string|null $description
 * @property string|null $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Contracts\Routing\UrlGenerator|string $href
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\Phoenix\EloquentMeta\Meta[] $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel findSimilarSlugs(\Illuminate\Database\Eloquent\Model $model, $attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Experts\Models\ExpertModel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel wherePost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Experts\Models\ExpertModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Experts\Models\ExpertModel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Experts\Models\ExpertModel withoutTrashed()
 * @mixin \Eloquent
 */
class ExpertModel extends Model implements HasMediaConversions
{
    use MetaTrait;
    use Sluggable;
    use Searchable;
    use SoftDeletes;
    use HasMediaTrait;
    use RevisionableTrait;
    use SluggableScopeHelpers;

    const HREF = '/expert/';

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'experts';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'post', 'description', 'content',
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

    protected $revisionCreationsEnabled = true;

    /**
     * Настройка полей для поиска.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $arr = array_only($this->toArray(), ['id', 'name', 'post', 'description', 'content']);

        return $arr;
    }

    /**
     * Возвращаем конфиг для генерации slug модели.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'unique' => true,
            ],
        ];
    }

    /**
     * Правила для транслита.
     *
     * @param Slugify $engine
     * @return Slugify
     */
    public function customizeSlugEngine(Slugify $engine)
    {
        $rules = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'je', 'ю' => 'ju', 'я' => 'ja',
        ];

        $engine->addRules($rules);

        return $engine;
    }

    /**
     * Ссылка на эксперта.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getHrefAttribute()
    {
        return url(self::HREF.(! empty($this->slug) ? $this->slug : $this->id));
    }

    /**
     * Регистрируем преобразования изображений.
     *
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $quality = (config('experts.images.quality')) ? config('experts.images.quality') : 75;

        if (config('experts.images.conversions')) {
            foreach (config('experts.images.conversions') as $collection => $image) {
                foreach ($image as $crop) {
                    foreach ($crop as $conversion) {
                        $imageConversion = $this->addMediaConversion($conversion['name']);

                        if (isset($conversion['size']['width'])) {
                            $imageConversion->width($conversion['size']['width']);
                        }

                        if (isset($conversion['size']['height'])) {
                            $imageConversion->height($conversion['size']['height']);
                        }

                        if (isset($conversion['fit']['width']) && isset($conversion['fit']['height'])) {
                            $imageConversion->fit('max', $conversion['fit']['width'], $conversion['fit']['height']);
                        }

                        if (isset($conversion['quality'])) {
                            $imageConversion->quality($conversion['quality']);
                            $imageConversion->optimize();
                        } else {
                            $imageConversion->quality($quality);
                        }

                        $imageConversion->performOnCollections($collection);
                    }
                }
            }
        }
    }
}
