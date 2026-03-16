<?php

declare(strict_types=1);

namespace App\Domain\Shared\Repositories\Classes;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

abstract class AbstractRepository
{
    public function __construct(
        protected Model $model
    ) {}

    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function findWithCondition(array $conditions)
    {
        return $this->model->where($conditions)->first();
    }

    public function listAllBy(
        array $conditions = [],
        array $relations = [],
        array $select = ['*'],
        string $orderBy = 'id',
        string $orderType = 'DESC',
        array $orConditions = [],
        array $whereHasConditions = [],
        array $whereDoesntHaveConditions = [],
        $location = []
    ): array|Collection {
        $model = $this->model;
        if (in_array('status', $this->model->getFillable(), true)) {
            $model = $model->active();
        }
        if (! empty($whereHasConditions)) {
            foreach ($whereHasConditions as $relation => $callback) {
                $model = $model->whereHas($relation, $callback);
            }
        }
        if (! empty($whereDoesntHaveConditions)) {
            foreach ($whereDoesntHaveConditions as $relation => $callback) {
                $model = $model->whereDoesntHave($relation, $callback);
            }
        }

        $query = $model->with($relations)->select($select);
        if (! empty($conditions)) {
            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    $query->whereIn($key, $value);
                } elseif (is_callable($value)) {
                    $query->where($value);
                } else {
                    $query->where($key, $value);
                }
            }
        }
        if (! empty($orConditions)) {
            $query->orWhere($orConditions);
        }
        if (isset($location['radius'])) {
            $query->selectRaw('
        *,
        (6371 * acos(
            cos(radians(?)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) *
            sin(radians(latitude))
        )) AS distance
        ', [$location['latitude'], $location['longitude'], $location['latitude']])
                ->whereRaw('
            (6371 * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude))
            )) <= ?
        ', [
                    $location['latitude'],
                    $location['longitude'],
                    $location['latitude'],
                    $location['radius'],
                ])
                ->orderBy('distance');
        } else {
            $query->orderBy($orderBy, $orderType);
        }

        return $query->get();
    }

    public function updateOrCreate(array $find, array $data): mixed
    {
        return $this->model->updateOrCreate($find, $data);
    }

    public function firstOrCreate(array $find, array $data = []): mixed
    {
        return $this->model->firstOrCreate($find, $data);
    }

    public function firstOrCreateWithConditions(array $conditions, array $data): mixed
    {
        return $this->model->firstOrCreate($conditions, $data);
    }

    public function first(array $conditions = [], array $relations = [], array $select = ['*'], array $orConditions = [], array $groupBy = []): Model|Builder|null
    {
        return $this->prepareQuery($conditions, $orConditions, $relations, $select, $groupBy)->first();
    }

    public function prepareQuery(array $conditions = [], array $orConditions = [], array $relations = [], array $select = ['*'], array $groupBy = []): Builder|\Illuminate\Database\Eloquent\Builder
    {
        return $this->model
            ->with($relations)
            ->where($conditions)
            ->orWhere($orConditions)
            ->select($select)
            ->groupBy(...$groupBy);
    }

    public function retrieve(
        array $conditions = [],
        array $relations = [],
        array $select = ['*'],
        string $orderBy = 'id',
        array $whereHasConditions = [],
        string $order = 'DESC'
    ): LengthAwarePaginator {

        $model = $this->model;

        if (in_array('status', $this->model->getFillable(), true)) {
            $model = $model->active();
        }

        if (! empty($whereHasConditions)) {
            $model = $model->whereHas(
                $whereHasConditions[0],
                $whereHasConditions[1] ?? null
            );
        }

        return $model
            ->search()
            ->filter()
            ->createdAtRange()
            ->with($relations)
            ->where($conditions)
            ->select($select)
            ->orderBy($orderBy, $order)
            ->paginate(
                request('paginate') ?? config('general_settings.pagination.value', env('PAGINATE_COUNT'))
            );
    }

    public function updateWhere(array $data, array $conditions = [])
    {
        $this->model->where($conditions)->update($data);
    }

    public function update(array $data, array $conditions = [], array $select = ['*']): Model
    {
        $model = $this->model->where($conditions)->select($select)->firstOrFail();
        $model->update($data);

        return $model;
    }

    public function updateAll(array $data, array $conditions = [], array $select = ['*']): void
    {
        $this->model->where($conditions)->update($data);
    }

    public function firstOrFail(array $conditions = [], array $relations = [], array $select = ['*'], array $orConditions = []): Model|Builder
    {
        return $this->prepareQuery($conditions, $orConditions, $relations, $select)
            ->firstOrFail();
    }

    public function getWhereIn(array $ids, array $conditions = [], string $selectedColumn = 'id', array $select = ['*'], array $relations = []): mixed
    {
        return $this->prepareWhereIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->select($select)->with($relations)->get();
    }

    public function getWhereInWithoutLazyLoading(array $ids, array $conditions = [], string $selectedColumn = 'id', array $select = ['*'], array $relations = []): mixed
    {
        return $this->prepareWhereIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->select($select)->with($relations)->get();
    }

    private function prepareWhereIn(string $selectedColumn = 'id', array $ids = [], array $conditions = []): mixed
    {
        return $this->model->whereIn($selectedColumn, $ids)->where($conditions);
    }

    public function getWhereNotIn(array $ids, array $conditions = [], string $selectedColumn = 'id', array $select = ['*'], array $relations = []): mixed
    {
        return $this->prepareWhereNotIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->select($select)->with($relations)->get();
    }

    private function prepareWhereNotIn(string $selectedColumn = 'id', array $ids = [], array $conditions = []): mixed
    {
        return $this->model->whereNotIn($selectedColumn, $ids)->where($conditions);
    }

    public function deleteWhereNotIn(array $ids, array $conditions = [], string $selectedColumn = 'id'): mixed
    {
        return $this->prepareWhereNotIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->delete();
    }

    public function delete(array $conditions): void
    {
        $model = $this->first(conditions: $conditions);
        if ($model) {
            $model->delete();
        }
    }

    public function deleteAll(array $conditions): void
    {
        $this->model->where($conditions)->delete();
    }

    public function deleteAllIn(array $listIds, $key = 'id'): void
    {
        $this->model->whereIn($key, $listIds)->delete();
    }

    public function deleteAllInWithCondition(array $listIds, array $conditions, $key = 'id'): void
    {
        $this->model->where($conditions)->whereIn($key, $listIds)->delete();
    }

    public function updateWhereIn(array $data, array $ids = [], string $selectedColumn = 'id', array $conditions = []): void
    {
        $this->prepareWhereIn(selectedColumn: $selectedColumn, ids: $ids, conditions: $conditions)->update($data);
    }

    public function truncate(): void
    {
        $this->model->truncate();
    }

    public function takeRaws(int $nRaws, array $conditions = [], array $relations = [], array $select = ['*'], string $orderColumn = 'id', string $orderType = 'DESC', array $orConditions = []): Collection|array
    {
        return $this->prepareQuery($conditions, $orConditions, $relations, $select)
            ->orderBy($orderColumn, $orderType)
            ->take($nRaws)
            ->get();
    }

    public function getFromTo(int $skip, int $limit)
    {
        return $this->model->orderByDesc('id')->skip($skip)->take($limit)->get();
    }

    public function getCount(array $conditions = [])
    {
        return $this->model->where($conditions)->count();
    }

    public function lastOrFailOnlyTrashedRecord(array $conditions = [], array $relations = [], array $select = ['*'], array $orConditions = []): Model|Builder|null
    {
        return $this->prepareQuery($conditions, $orConditions, $relations, $select)->orderBy('created_at', 'desc')->onlyTrashed()->firstOrFail();
    }

    public function lastOrFailWithTrashedRecord(array $conditions = [], array $relations = [], array $select = ['*'], array $orConditions = []): Model|Builder|null
    {
        return $this->prepareQuery($conditions, $orConditions, $relations, $select)->orderBy('created_at', 'desc')->withTrashed()->firstOrFail();
    }

    public function softDeleteAndDuplicate(Model $model, array $data = []): Model
    {
        $newRecord = $this->replicate(model: $model, data: $data);
        $this->delete(
            conditions: [
                'id' => $model?->id,
            ]
        );

        return $newRecord;
    }

    public function replicate(Model $model, array $data = []): Model
    {
        $duplicatedRecord = $model->replicate()->fill($data);
        $duplicatedRecord->save();

        return $duplicatedRecord;
    }

    public function firstOrFailWithTrashed(array $conditions = [], array $orConditions = [], array $relations = [], array $select = ['*'])
    {
        return $this->prepareQuery($conditions, $orConditions, $relations, $select)->withTrashed()->firstOrFail();
    }

    public function upsert(array $data, array|string $keys): mixed
    {
        return $this->model->upsert($data, $keys);
    }

    public function createMany(array $data): mixed
    {
        return $this->model->insert($data);
    }

    public function whereJsonContains($condition, $attribute)
    {
        return $this->model->whereJsonContains($condition, $attribute)->first();
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model->newQuery();
    }

    public function count(array $conditions = []): int
    {
        $query = $this->model;

        if (! empty($conditions)) {
            $query = $query->where($conditions);
        }

        return $query->count();
    }
}
