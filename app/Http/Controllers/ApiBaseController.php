<?php

namespace App\Http\Controllers;

use App\Traits\Http\Response;
use Dingo\Api\Routing\Helpers;
use App\Repositories\Criterias\PaginationCriteria;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    use Response, Helpers;

    public function index(Request $request)
    {
        $limit = $request->get('limit');
        if (is_null($limit)) {
            $limit = 10;
        }

        // $this->repository->pushCriteria(new PaginationCriteria($page));

        // sets sort by w/ desc
        if (!empty($request->get('sortBy'))) {
            $sortBy = $request->get('sortBy');
            $sortTo  = $request->get('sortDesc') == 'true' ? 'desc' : 'asc';
            $this->repository->scopeQuery(function ($query) use ($sortBy, $sortTo) {
                return $query->orderBy($sortBy, $sortTo);
            });
        }

        // sets keyword



        if ($limit > 0) {
            $url = str_replace('api/', '', $request->path());
            $results = $this->repository
                            ->paginate($limit)
                            ->withPath($url);
        } else {
            $results = $this->repository->all();
        }

        return $this->success($results);
    }

    public function show($id)
    {
        $result = $this->repository->find($id);
        // TODO error when repository not found

        return $this->success($result);
    }


    public function store(Request $request)
    {

        if (isset($this->validations)) {
            $request->validate($this->validations);
        }

        $result = $this->repository->create($request->all());
        
        return $this->success($result);
    }

    public function update(Request $request, $id)
    {
        if (isset($this->validations)) {
            $request->validate($this->validations);
        }

        $result = $this->repository->update($request->all(), $id);
        return $this->success($result);
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        // TODO handle when to delete id is not found

        return 'true';
    }


    /**
     * checkIncludeParameters function
     *
     * @param [type] $includeParameters
     * @return boolean
     */
    public function checkIncludeParameters($includeParameters)
    {
        
        $avaialbleIncludes = $this->transformer->getAvailableIncludes();
        //explode requested string
        $explodedRequestIncludes = explode(',', $includeParameters);

        foreach ($explodedRequestIncludes as $value) {
            //check if item is in available includes
            if (!in_array(camel_case($value), $avaialbleIncludes)) {
                return false;
            }
        }

        return true;
    }
}
