<?php

namespace InetStudio\PersonsPackage\Persons\Models\Traits;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;

/**
 * Trait HasPersons.
 */
trait HasPersons
{
    use HasPersonsCollection;

    /**
     * The Queued Persons.
     *
     * @var array
     */
    protected $queuedPersons = [];

    /**
     * Get Person class name.
     *
     * @return string
     *
     * @throws BindingResolutionException
     */
    public function getPersonClassName(): string
    {
        $model = app()->make(PersonModelContract::class);

        return get_class($model);
    }

    /**
     * Get all attached persons to the model.
     *
     * @return MorphToMany
     *
     * @throws BindingResolutionException
     */
    public function persons(): MorphToMany
    {
        $className = $this->getPersonClassName();

        return $this->morphToMany($className, 'personable')->withTimestamps();
    }

    /**
     * Attach the given person(s) to the model.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @throws BindingResolutionException
     */
    public function setPersonsAttribute($persons): void
    {
        if (! $this->exists) {
            $this->queuedPersons = $persons;

            return;
        }

        $this->attachPersons($persons);
    }

    /**
     * Boot the personable trait for a model.
     */
    public static function bootHasPersons()
    {
        static::created(
            function (Model $personableModel) {
                if ($personableModel->queuedPersons) {
                    $personableModel->attachPersons($personableModel->queuedPersons);
                    $personableModel->queuedPersons = [];
                }
            }
        );

        static::deleted(
            function (Model $personableModel) {
                $personableModel->syncPersons(null);
            }
        );
    }

    /**
     * Get the person list.
     *
     * @param  string  $keyColumn
     *
     * @return array
     *
     * @throws BindingResolutionException
     */
    public function personList(string $keyColumn = 'slug'): array
    {
        return $this->persons()->pluck('name', $keyColumn)->toArray();
    }

    /**
     * Scope query with all the given persons.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithAllPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        $persons = $this->isPersonsStringBased($persons)
            ? $persons : $this->hydratePersons($persons)->pluck($column)->toArray();

        collect($persons)->each(
            function ($person) use ($query, $column) {
                $query->whereHas(
                    'persons',
                    function (Builder $query) use ($person, $column) {
                        return $query->where($column, $person);
                    }
                );
            }
        );

        return $query;
    }

    /**
     * Scope query with any of the given persons.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithAnyPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        $persons = $this->isPersonsStringBased($persons)
            ? $persons : $this->hydratePersons($persons)->pluck($column)->toArray();

        return $query->whereHas(
            'persons',
            function (Builder $query) use ($persons, $column) {
                $query->whereIn($column, (array) $persons);
            }
        );
    }

    /**
     * Scope query with any of the given persons.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        return $this->scopeWithAnyPersons($query, $persons, $column);
    }

    /**
     * Scope query without the given persons.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     * @param  string  $column
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithoutPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        $persons = $this->isPersonsStringBased($persons)
            ? $persons : $this->hydratePersons($persons)->pluck($column)->toArray();

        return $query->whereDoesntHave(
            'persons',
            function (Builder $query) use ($persons, $column) {
                $query->whereIn($column, (array) $persons);
            }
        );
    }

    /**
     * Scope query without any persons.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeWithoutAnyPersons(Builder $query): Builder
    {
        return $query->doesntHave('persons');
    }

    /**
     * Attach the given Person(ies) to the model.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function attachPersons($persons)
    {
        $this->setPersons($persons, 'syncWithoutDetaching');

        return $this;
    }

    /**
     * Sync the given person(s) to the model.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract|null  $persons
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function syncPersons($persons)
    {
        $this->setPersons($persons, 'sync');

        return $this;
    }

    /**
     * Detach the given Person(s) from the model.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function detachPersons($persons)
    {
        $this->setPersons($persons, 'detach');

        return $this;
    }

    /**
     * Set the given person(s) to the model.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     * @param  string  $action
     *
     * @throws BindingResolutionException
     */
    protected function setPersons($persons, string $action): void
    {
        // Fix exceptional event name
        $event = $action === 'syncWithoutDetaching' ? 'attach' : $action;

        // Hydrate Persons
        $persons = $this->hydratePersons($persons)->pluck('id')->toArray();

        // Fire the Person syncing event
        static::$dispatcher->dispatch('inetstudio.persons.'.$event.'ing', [$this, $persons]);

        // Set Persons
        $this->persons()->$action($persons);

        // Fire the Person synced event
        static::$dispatcher->dispatch('inetstudio.persons.'.$event.'ed', [$this, $persons]);
    }

    /**
     * Hydrate persons.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return Collection
     *
     * @throws BindingResolutionException
     */
    protected function hydratePersons($persons): Collection
    {
        $isPersonsStringBased = $this->isPersonsStringBased($persons);
        $isPersonsIntBased = $this->isPersonsIntBased($persons);
        $field = $isPersonsStringBased ? 'slug' : 'id';
        $className = $this->getPersonClassName();

        return $isPersonsStringBased || $isPersonsIntBased
            ? $className::query()->whereIn($field, (array) $persons)->get() : collect($persons);
    }
}
