<?php

namespace InetStudio\PersonsPackage\Persons\Models\Traits;

use ArrayAccess;
use Illuminate\Support\Collection;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;

/**
 * Trait HasPersonsCollection.
 */
trait HasPersonsCollection
{
    /**
     * Determine if the model has any the given persons.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return bool
     */
    public function hasPerson($persons): bool
    {
        if ($this->isPersonsStringBased($persons)) {
            return ! $this->persons->pluck('slug')->intersect((array) $persons)->isEmpty();
        }

        if ($this->isPersonsIntBased($persons)) {
            return ! $this->persons->pluck('id')->intersect((array) $persons)->isEmpty();
        }

        if ($persons instanceof PersonModelContract) {
            return $this->persons->contains('slug', $persons['slug']);
        }

        if ($persons instanceof Collection) {
            return ! $persons->intersect($this->persons->pluck('slug'))->isEmpty();
        }

        return false;
    }

    /**
     * Determine if the model has any the given persons.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return bool
     */
    public function hasAnyPerson($persons): bool
    {
        return $this->hasPerson($persons);
    }

    /**
     * Determine if the model has all of the given persons.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return bool
     */
    public function hasAllPersons($persons): bool
    {
        if ($this->isPersonsStringBased($persons)) {
            $persons = (array) $persons;

            return $this->persons->pluck('slug')->intersect($persons)->count() == count($persons);
        }

        if ($this->isPersonsIntBased($persons)) {
            $persons = (array) $persons;

            return $this->persons->pluck('id')->intersect($persons)->count() == count($persons);
        }

        if ($persons instanceof PersonModelContract) {
            return $this->persons->contains('slug', $persons['slug']);
        }

        if ($persons instanceof Collection) {
            return $this->persons->intersect($persons)->count() == $persons->count();
        }

        return false;
    }

    /**
     * Determine if the given person(s) are string based.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return bool
     */
    protected function isPersonsStringBased($persons): bool
    {
        return is_string($persons) || (is_array($persons) && isset($persons[0]) && is_string($persons[0]));
    }

    /**
     * Determine if the given person(s) are integer based.
     *
     * @param  int|string|array|ArrayAccess|PersonModelContract  $persons
     *
     * @return bool
     */
    protected function isPersonsIntBased($persons): bool
    {
        return is_int($persons) || (is_array($persons) && isset($persons[0]) && is_int($persons[0]));
    }
}
