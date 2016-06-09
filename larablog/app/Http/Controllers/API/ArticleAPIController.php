<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateArticleAPIRequest;
use App\Http\Requests\API\UpdateArticleAPIRequest;
use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ArticleController
 * @package App\Http\Controllers\API
 */

class ArticleAPIController extends AppBaseController
{
    /** @var  ArticleRepository */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepo)
    {
        $this->articleRepository = $articleRepo;
    }

    /**
     * Display a listing of the Article.
     * GET|HEAD /articles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->articleRepository->pushCriteria(new RequestCriteria($request));
        $this->articleRepository->pushCriteria(new LimitOffsetCriteria($request));
        $articles = $this->articleRepository->all();

        return $this->sendResponse($articles->toArray(), 'Articles retrieved successfully');
    }

    /**
     * Store a newly created Article in storage.
     * POST /articles
     *
     * @param CreateArticleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateArticleAPIRequest $request)
    {
        $input = $request->all();

        $articles = $this->articleRepository->create($input);

        return $this->sendResponse($articles->toArray(), 'Article saved successfully');
    }

    /**
     * Display the specified Article.
     * GET|HEAD /articles/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Article $article */
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            return Response::json(ResponseUtil::makeError('Article not found'), 400);
        }

        return $this->sendResponse($article->toArray(), 'Article retrieved successfully');
    }

    /**
     * Update the specified Article in storage.
     * PUT/PATCH /articles/{id}
     *
     * @param  int $id
     * @param UpdateArticleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateArticleAPIRequest $request)
    {
        $input = $request->all();

        /** @var Article $article */
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            return Response::json(ResponseUtil::makeError('Article not found'), 400);
        }

        $article = $this->articleRepository->update($input, $id);

        return $this->sendResponse($article->toArray(), 'Article updated successfully');
    }

    /**
     * Remove the specified Article from storage.
     * DELETE /articles/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Article $article */
        $article = $this->articleRepository->find($id);

        if (empty($article)) {
            return Response::json(ResponseUtil::makeError('Article not found'), 400);
        }

        $article->delete();

        return $this->sendResponse($id, 'Article deleted successfully');
    }

    /**
     * Display the users Article.
     * GET|HEAD /articles/{user_id}
     *
     * @param  int $user_id
     *
     * @return Response
     */
    public function userArticles($user_id)
    {
        /** @var Article $article */
        $articles = $this->articleRepository->findByField('user_id', $user_id)->toArray();

        if (empty($articles)) {
            return Response::json(ResponseUtil::makeError('This user has no article'), 400);
        }

        return $this->sendResponse($articles, 'Article retrieved successfully');
    }

    public function delegateArticles(Request $request)
    {
        $input = $request->all();

        if(empty($input)) {
            return Response::json(ResponseUtil::makeError('Ids not provided!'), 400);
        }

        if(empty($input['user_id']) && empty($input['delegate_id'])) {
            return Response::json(ResponseUtil::makeError('User ID and Delegate User ID not provided!'), 400);
        }

        if(!empty($input['user_id']) && empty($input['delegate_id'])) {
            $articles = $this->articleRepository->findWhere(['user_id' => $input['user_id']])->toArray();
            if(!empty($articles)) {
                foreach ($articles as $article) {
                    $this->articleRepository->delete($article['id']);
                }
                return $this->sendResponse('Success', 'User Delegation Failed! Articles deleted');
            }
        }

        $articles = $this->articleRepository->findWhere(['user_id' => $input['user_id']])->toArray();

        if(!empty($articles)) {
            foreach ($articles as $article) {
                $this->articleRepository->update(['user_id' => $input['delegate_id']], $article['id']);
            }
            return $this->sendResponse('Success', 'User Delegation successful');
        }

        return Response::json(ResponseUtil::makeError('Error updating records!'), 400);

    }
}
