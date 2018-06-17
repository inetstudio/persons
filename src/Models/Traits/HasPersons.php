<?php

namespace InetStudio\Persons\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use InetStudio\Persons\Contracts\Models\PersonModelContract;

/**
 * Trait HasPersons.
 */
trait HasPersons
{
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
     */
    public static function getPersonClassName(): string
    {
        $model = app()->make('InetStudio\Persons\Contracts\Models\PersonModelContract');

        return get_class($model);
    }

    /**
     * Get all attached persons to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function persons(): MorphToMany
    {
        return $this->morphToMany(static::getPersonClassName(), 'personable')->withTimestamps();
    }

    /**
     * Attach the given person(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return void
     */
    public function setPersonsAttribute($persons)
    {
        if (! $this->exists) {
            $this->queuedPersons = $persons;

            return;
        }

        $this->attachPersons($persons);
    }

    /**
     * Boot the personable trait for a model.
     *
     * @return void
     */
    public static function bootHasPersons()
    {
        static::created(function (Model $personableModel) {
            if ($personableModel->queuedPersons) {
                $personableModel->attachPersons($personableModel->queuedPersons);
                $personableModel->queuedPersons = [];
            }
        });

        static::deleted(function (Model $personableModel) {
            $personableModel->syncPersons(null);
        });
    }

    /**
     * Get the person list.
     *
     * @param string $keyColumn
     *
     * @return array
     */
    public function personList(string $keyColumn = 'slug'): array
    {
        return $this->persons()->pluck('name', $keyColumn)->toArray();
    }

    /**
     * Scope query with all the given persons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        $persons = static::isPersonsStringBased($persons)
            ? $persons : static::hydratePersons($persons)->pluck($column);

        collect($persons)->each(function ($person) use ($query, $column) {
            $query->whereHas('persons', function (Builder $query) use ($person, $column) {
                return $query->where($column, $person);
            });
        });

        return $query;
    }

    /**
     * Scope query with any of the given persons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        $persons = static::isPersonsStringBased($persons)
            ? $persons : static::hydratePersons($persons)->pluck($column);

        return $query->whereHas('persons', function (Builder $query) use ($persons, $column) {
            $query->whereIn($column, (array) $persons);
        });
    }

    /**
     * Scope query with any of the given persons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        return static::scopeWithAnyPersons($query, $persons, $column);
    }

    /**
     * Scope query without the given persons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     * @param string $column
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutPersons(Builder $query, $persons, string $column = 'slug'): Builder
    {
        $persons = static::isPersonsStringBased($persons)
            ? $persons : static::hydratePersons($persons)->pluck($column);

        return $query->whereDoesntHave('persons', function (Builder $query) use ($persons, $column) {
            $query->whereIn($column, (array) $persons);
        });
    }

    /**
     * Scope query without any persons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAnyPersons(Builder $query): Builder
    {
        return $query->doesntHave('persons');
    }

    /**
     * Attach the given Person(ies) to the model.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return $this
     */
    public function attachPersons($persons)
    {
        static::setPersons($persons, 'syncWithoutDetaching');

        return $this;
    }

    /**
     * Sync the given person(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract|null $persons
     *
     * @return $this
     */
    public function syncPersons($persons)
    {
        static::setPersons($persons, 'sync');

        return $this;
    }

    /**
     * Detach the given Person(s) from the model.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return $this
     */
    public function detachPersons($persons)
    {
        static::setPersons($persons, 'detach');

        return $this;
    }

    /**
     * Determine if the model has any the given persons.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return bool
     */
    public function hasPerson($persons): bool
    {
        // Single Person slug
        if (is_string($persons)) {
            return $this->persons->contains('slug', $persons);
        }

        // Single Person id
        if (is_int($persons)) {
            return $this->persons->contains('id', $persons);
        }

        // Single Person model
        if ($persons instanceof PersonModelContract) {
            return $this->persons->contains('slug', $persons->slug);
        }

        // Array of Person slugs
        if (is_array($persons) && isset($persons[0]) && is_string($persons[0])) {
            return ! $this->persons->pluck('slug')->intersect($persons)->isEmpty();
        }

        // Array of Person ids
        if (is_array($persons) && isset($persons[0]) && is_int($persons[0])) {
            return ! $this->persons->pluck('id')->intersect($persons)->isEmpty();
        }

        // Collection of Person models
        if ($persons instanceof Collection) {
            return ! $persons->intersect($this->persons->pluck('slug'))->isEmpty();
        }

        return false;
    }

    /**
     * Determine if the model has any the given persons.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return bool
     */
    public function hasAnyPerson($persons): bool
    {
        return static::hasPerson($persons);
    }

    /**
     * Determine if the model has all of the given persons.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return bool
     */
    public function hasAllPersons($persons): bool
    {
        // Single person slug
        if (is_string($persons)) {
            return $this->persons->contains('slug', $persons);
        }

        // Single person id
        if (is_int($persons)) {
            return $this->persons->contains('id', $persons);
        }

        // Single person model
        if ($persons instanceof PersonModelContract) {
            return $this->persons->contains('slug', $persons->slug);
        }

        // Array of person slugs
        if (is_array($persons) && isset($persons[0]) && is_string($persons[0])) {
            return $this->persons->pluck('slug')->count() === count($persons)
                && $this->persons->pluck('slug')->diff($persons)->isEmpty();
        }

        // Array of person ids
        if (is_array($persons) && isset($persons[0]) && is_int($persons[0])) {
            return $this->persons->pluck('id')->count() === count($persons)
                && $this->persons->pluck('id')->diff($persons)->isEmpty();
        }

        // Collection of person models
        if ($persons instanceof Collection) {
            return $this->persons->count() === $persons->count() && $this->persons->diff($persons)->isEmpty();
        }

        return false;
    }

    /**
     * Set the given person(s) to the model.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     * @param string $action
     *
     * @return void
     */
    protected function setPersons($persons, string $action)
    {
        // Fix exceptional event name
        $event = $action === 'syncWithoutDetaching' ? 'attach' : $action;

        // Hydrate Persons
        $persons = static::hydratePersons($persons)->pluck('id')->toArray();

        // Fire the Person syncing event
        static::$dispatcher->dispatch("inetstudio.persons.{$event}ing", [$this, $persons]);

        // Set Persons
        $this->persons()->$action($persons);

        // Fire the Person synced event
        static::$dispatcher->dispatch("inetstudio.persons.{$event}ed", [$this, $persons]);
    }

    /**
     * Hydrate persons.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return \Illuminate\Support\Collection
     */
    protected function hydratePersons($persons)
    {
        $isPersonsStringBased = static::isPersonsStringBased($persons);
        $isPersonsIntBased = static::isPersonsIntBased($persons);
        $field = $isPersonsStringBased ? 'slug' : 'id';
        $className = static::getPersonClassName();

        return $isPersonsStringBased || $isPersonsIntBased
            ? $className::query()->whereIn($field, (array) $persons)->get() : collect($persons);
    }

    /**
     * Determine if the given person(s) are string based.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return bool
     */
    protected function isPersonsStringBased($persons)
    {
        return is_string($persons) || (is_array($persons) && isset($persons[0]) && is_string($persons[0]));
    }

    /**
     * Determine if the given person(s) are integer based.
     *
     * @param int|string|array|\ArrayAccess|PersonModelContract $persons
     *
     * @return bool
     */
    protected function isPersonsIntBased($persons)
    {
        return is_int($persons) || (is_array($persons) && isset($persons[0]) && is_int($persons[0]));
    }
}
