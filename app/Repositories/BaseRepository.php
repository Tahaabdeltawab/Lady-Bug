<?php

namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     *
     * @throws \Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getFieldsSearchable();

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $columns = ['*'])
    {
        $query = $this->allQuery();

        return $query->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery($search = [], $skip = null, $limit = null)
    {
        $query = $this->model->newQuery();

        if (count($search)) {
            foreach($search as $key => $value) {
                if (in_array($key, $this->getFieldsSearchable())) {
                    $query->where($key, $value);
                }
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }

    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($search = [], $skip = null, $limit = null, $relations = [], $columns = ['*'])
    {
        $query = $this->allQuery($search, $skip, $limit);

        if (count($relations))
        {
            $query->with($relations);
        }

        return $query->get($columns);
    }

    //latest
    public function latest()
    {
        $query = $this->model->newQuery();

        return $query->latest();
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    public function save_localized($input, $id='')
    {
        $collection = collect($input);

        $exploded = $collection->filter(function ($value, $key) {
            return Str::endsWith($key, 'localized');
        })->keys()->map(function($value,$key){
            return  explode('_', $value);
        }); //output is => [['name', 'ar', 'localized' ],['name', 'en', 'localized']]

        foreach($exploded as $ex){
            if (count($ex) != 3){
                return response()->json(["success"=>false, "message" => "Fields names improperly formatted"], 402);
            }
        }

        // if the $input has localized fields like 'name_ar_localized'
        if(count($exploded))
        {
            $localized_fields = $exploded->pluck(0)->mapWithKeys(function($value, $key){
                return [$value => $key];
            })->keys();

            $langs = $exploded->pluck(1)->mapWithKeys(function($value, $key){
                return [$value => $key];
            })->keys();;

            foreach($localized_fields as $field){
                foreach($langs as $lang){
                    $data[$field][$lang] = $input[$field.'_'.$lang.'_localized'];
                    unset($input[$field.'_'.$lang.'_localized']);
                }
            }
            // output is data['ar']['name'] = 'المزرعة السعيدة'
            //           data['en']['name'] = 'the happy farm'

            $tosave = array_merge($data, $input);
        }
        else
        {
            $tosave = $input;
        }

        // return response()->json($tosave);
       if($id)
       {
            $query = $this->model->newQuery();
            $model = $query->find($id);
            if(!$model)
            {
                return response()->json(["success"=>false, "message" => "Not found model"], 404);
            };
            $model->fill($tosave);
            $model->save();
       }
       else
       {
           $model = $this->model->newInstance($tosave);
           $model->save();
        }

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update($input, $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete($id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }



    public function findBy(array $criteria, array $columns = [], bool $single = true)
    {
        $query = $this->model::query();

        foreach ($criteria as $key => $item)
        {
            $query->where($key, $item);
        }

        $method = $single ? 'first' : 'get';

        return empty($columns) ? $query->{$method}() : $query->{$method}($columns);
    }

    public function where(array $criteria)
    {
        $query = $this->model::query();

        foreach ($criteria as $key => $item)
        {
            $query->where($key, $item);
        }
        return $query->get();
    }

    public function whereIn(array $criteria)
    {
        $query = $this->model::query();

        foreach ($criteria as $key => $item)
        {
            $query->whereIn($key, $item);
        }
        return $query->get();
    }

    public function updateBy(array $criteria, array $data)
    {
        $query = $this->model::query();

        foreach ($criteria as $key => $value)
        {
            $query->where($key, $value);
        }

        return $query->update($data);
    }

    public function getUserBaseRole($roleRequest)
    {
        $query = $this->model::query();

        return $query->when($roleRequest, function ($q) use($roleRequest){

            $q->whereHas('roles', function ($q) use ($roleRequest) {
                $q->where('name', $roleRequest->name);
            });

        })
            ->orderBy('created_at','DESC')
            ->paginate();

    }

    public function allWithTrashed()
    {
        $query = $this->model::query();

        return $query->withTrashed()
            ->orderBy('created_at','DESC')
            ->paginate();
    }

    public function restore(int $ID)
    {
        $query = $this->model::query();

        return $query->withTrashed()->where('id', $ID)->restore();
    }
}
