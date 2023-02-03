<?php

namespace App\Http\Controllers\Api;

use App\Events\NewsCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $news = News::paginate(15);
        return new JsonResponse($news, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NewsRequest  $request
     * @return JsonResponse
     */
    public function store(NewsRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['user_id' => $request->user()->id]);
        $news = News::create($data);

        event(new NewsCreated($news));

        return new JsonResponse(['data' => $news, 'msg' => 'News has been created successfully'], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  News  $news
     * @return JsonResponse
     */
    public function show(News $news): JsonResponse
    {
        return new JsonResponse(['data' => $news], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NewsRequest  $request
     * @param  News  $news
     * @return JsonResponse
     */
    public function update(NewsRequest $request, News $news): JsonResponse
    {
        $news->update($request->validated());
        return new JsonResponse(['data' => $news, 'msg' => 'News has been updated successfully'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  News  $news
     * @return JsonResponse
     */
    public function destroy(News $news): JsonResponse
    {
        if($news->delete()){
            return new JsonResponse(['msg' => 'News has been deleted successfully'], Response::HTTP_OK);
        }

        return new JsonResponse(['msg' => 'Something went wrong, please try again'], Response::HTTP_BAD_REQUEST);
    }
}
