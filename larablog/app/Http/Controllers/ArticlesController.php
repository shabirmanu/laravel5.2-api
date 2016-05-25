<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use Illuminate\Http\Request;

use App\Article;
use App\Tag;


/**
 * Class ArticlesController
 * @package App\Http\Controllers
 */
class ArticlesController extends Controller
{
  /**
   * ArticlesController constructor.
   */
  public function __construct() {
    $this->middleware('article', ['except' => ['index', 'show']]);
    //$this->middleware('article', ['add' => ['create', 'edit']]);
  }


  /**
   * Shows all articles.
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index() {
    $articles = Article::latest('published_at')->published()->get();

    return view('articles.index', compact('articles'));
  }

  /**
   * Shows detail of article
   *
   * @param int $id Article Id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show(Article $article) {
    //$article = Article::find($id);

    return view('articles.show', compact('article'));
  }

  /**
   * Creates article.
   *
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function create() {
    $tags = Tag::lists('name','id');
    return view('articles.create' , compact('tags'));
  }

  /**
   * Handles post request and redirects to listing page.
   *
   * @param \App\Http\Requests\ArticleRequest $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function store(ArticleRequest $request) {

    $user = \Auth::user();

    $article = $user->articles()->create($request->all());


    $existingTags = $this->checkAndCreateNewTags($request->input('tag_list'));



    $article->tags()->attach($existingTags);

    return redirect('articles')->with([
      'flash_message' => 'Article has been created succesfully!',
    ]);
  }

  /**
   * Edits handler
   *
   * @param $id
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function edit(Article $article) {
    $tags = Tag::lists('name','id');
    return view('articles.edit', compact('article', 'tags'));
  }

  /**
   * Rest request for updating the article.
   *
   * @param \App\Http\Requests\ArticleRequest $request
   * @param $id
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function update(ArticleRequest $request, Article $article) {
    $article->update($request->all());


    $tagIds = $this->checkAndCreateNewTags($request->input('tag_list'));
    $article->tags()->sync($tagIds);


    return redirect('articles')->with([
      'flash_message' => 'Article has been updated succesfully!',
    ]);
  }

  /**
   * Checks for old and new tags and creates new if not exists.
   *
   * @param array $tagIds
   * @return array
   */
  private function checkAndCreateNewTags(array $tagIds) {
    $existingTags = array_filter($tagIds, "is_numeric");
    $newTags = array_diff($tagIds, $existingTags);

    foreach($newTags as $newTag) {
      $tag = Tag::create(['name' => $newTag]);
      $existingTags[] = $tag->id;
    }

    return $existingTags;
  }
}

