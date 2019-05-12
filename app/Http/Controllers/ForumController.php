<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\ChatterCategory;
use App\Models\ChatterDiscussion;
use App\Models\ChatterPost;
use Auth;
use AppHelper;
use Flash;

class ForumController extends Controller
{
    public function __construct() {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware('check_for_permission.access:forum.create', ['only' => ['category_index', 'category_destroy', 'category_create', 'category_edit', 'topic_index', 'topic_destroy', 'topic_create', 'topic_edit', 'post_index', 'post_destroy', 'post.create', 'post.edit']]);

        $this->title = "Forums";
        view()->share('title', $this->title);
    }

    /**
     * PAGE: forum-categories
     * GET: /forum-categories
     * This method handles the index view of forum-categories
     * @param
     * @return
     */
    public function category_index(){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
        $categories = ChatterCategory::orderBy('order', 'ASC')->paginate(20);
        $counter = 0;
        $categoryList = ChatterCategory::pluck('name', 'id');

        return view('forums/category-index', compact('categories', 'counter', 'categoryList'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category_destroy($id) {
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
        $model = ChatterCategory::find($id);
        if ($model) {
            $dependency = $model->deleteValidate($id);
            if (!$dependency) {
                $model->delete();
                //We need to set the child nodes parent id to null
                DB::table('chatter_categories')->where('parent_id', '=', $id)->update(array('parent_id' => null));

                //We need to delete child topics and post
                $topics = ChatterDiscussion::where('chatter_category_id', '=', $model->id)->get();
                foreach($topics as $topic){
                    ChatterPost::where('chatter_discussion_id', '=', $topic->id)->delete();
                }
                ChatterDiscussion::where('chatter_category_id', '=', $model->id)->delete();

                Flash::success(trans("comman.category_deleted"));
            } else {
                Flash::error(trans("comman.category_dependency_error", ['dependency' => $dependency]));
            }
        } else {
            Flash::error(trans("comman.category_error"));
        }
        return redirect('/forum-categories');
    }

    /**
     * PAGE: forum-categories/create
     * GET: forum-categories/create
     * @param Request $request
     * @return
     */
    public function category_create(Request $request){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }

        $categoryList = ChatterCategory::pluck('name', 'id');
        if(isset($request->save) && !empty($request->save)) {
            $this->validate($request, [
                'name' => array('required', 'String'),
                'color' => array('required','String'),
                'order' => array('required', 'numeric'),
            ]);

            $request->merge(array('slug' => str_slug($request->name)));
            if($request->parent_id == 0){
                $request->merge(array('parent_id' => null));
            }

            ChatterCategory::create($request->except(['save']));
            return redirect('/forum-categories')->with('status', 'Category added successfully.');
        }
        return view('forums/category-create', compact('categoryList'));
    }

    /**
     * PAGE: forum-categories/edit/{$category}
     * GET: forum-categories/edit/{$category}
     * @param ChatterCategory $category Request $request
     * @return
     */
    public function category_edit(ChatterCategory $category, Request $request){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }

        $categoryList = ChatterCategory::pluck('name', 'id');
        unset($categoryList[$category->id]);
        if(isset($request->save) && !empty($request->save)) {
            $this->validate($request, [
                'name' => array('required', 'String'),
                'color' => array('required','String'),
                'order' => array('required', 'numeric'),
            ]);

            $request->merge(array('slug' => str_slug($request->name)));
            if($request->parent_id == 0){
                $request->merge(array('parent_id' => null));
            }

            $category->update($request->except(['save']));

            //ChatterCategory::create($request->except(['save']));
            return redirect('/forum-categories')->with('status', 'Category edited successfully.');
        }
        return view('forums/category-create', compact('categoryList', 'category'));
    }

    /**
     * PAGE: forum-topics
     * GET: /forum-topics
     * This method handles the index view of forum-topics
     * @param
     * @return
     */
    public function topic_index(){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
        $topics = ChatterDiscussion::with('ChatterPost')->with('ChatterCategory')->orderBy('created_at', 'DESC')->paginate(20);
        $counter = 0;
        $categoryList = ChatterCategory::pluck('name', 'id');
        return view('forums/topic-index', compact('topics', 'counter', 'categoryList'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function topic_destroy($id) {
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
        $model = ChatterDiscussion::find($id);
        if ($model) {
            $dependency = $model->deleteValidate($id);
            if (!$dependency) {
                $model->delete();
                ChatterPost::where('chatter_discussion_id', '=', $model->id)->delete();

                Flash::success(trans("comman.topic_deleted"));
            } else {
                Flash::error(trans("comman.topic_dependency_error", ['dependency' => $dependency]));
            }
        } else {
            Flash::error(trans("comman.topic_error"));
        }
        return redirect('/forum-topics');
    }

    /**
     * PAGE: forum-topics/create
     * GET: forum-topics/create
     * @param Request $request
     * @return
     */
    public function topic_create(Request $request){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }

        $categoryList = ChatterCategory::pluck('name', 'id');
        if(isset($request->save) && !empty($request->save)) {
            $this->validate($request, [
                'chatter_category_id' => array('required', 'Integer'),
                'title' => array('required','String'),
            ]);

            $request->merge(array('slug' => str_slug($request->title)));
            $request->merge(array('user_id' => Auth::user()->id));
            $discussion = ChatterDiscussion::create($request->except(['save']));

            //To prevent errors on the front end we also need to to create a post to belong to the new topic
            $post_data = array('chatter_discussion_id' => $discussion->id, 'user_id' => Auth::user()->id, 'body' => 'Initial Post');
            ChatterPost::create($post_data);
            return redirect('/forum-topics')->with('status', 'Topic added successfully.');
        }
        return view('forums/topic-create', compact('categoryList'));
    }

    /**
     * PAGE: forum-topics/edit/{$topic}
     * GET: forum-topics/edit/{$topic}
     * @param ChatterDiscussion $topic Request $request
     * @return
     */
    public function topic_edit(ChatterDiscussion $topic, Request $request){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }

        $categoryList = ChatterCategory::pluck('name', 'id');
        if(isset($request->save) && !empty($request->save)) {
            $this->validate($request, [
                'chatter_category_id' => array('required', 'Integer'),
                'title' => array('required','String'),
            ]);

            $request->merge(array('slug' => str_slug($request->title)));
            $topic->update($request->except(['save']));

            return redirect('/forum-topics')->with('status', 'Topic edited successfully.');
        }
        return view('forums/topic-create', compact('categoryList', 'topic'));
    }

    /**
     * PAGE: forum-posts
     * GET: /forum-post
     * This method handles the index view of forum-post
     * @param
     * @return
     */
    public function post_index(){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
        $posts = ChatterPost::with('ChatterDiscussion')->with('User')->orderBy('created_at', 'DESC')->paginate(20);
        $counter = 0;
//        $categoryList = ChatterCategory::pluck('name', 'id');
        return view('forums/post-index', compact('posts', 'counter'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function post_destroy($id) {
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
        $model = ChatterPost::find($id);
        if ($model) {
            $dependency = $model->deleteValidate($id);
            if (!$dependency) {
                $model->delete();
                Flash::success(trans("comman.post_deleted"));
            } else {
                Flash::error(trans("comman.post_dependency_error", ['dependency' => $dependency]));
            }
        } else {
            Flash::error(trans("comman.post_error"));
        }
        return redirect('/forum-posts');
    }

    /**
     * PAGE: forum-post/create
     * GET: forum-post/create
     * @param Request $request
     * @return
     */
    public function post_create(Request $request){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
//        print_r($_POST);
//        dd($request->all());

        $discussionList = ChatterDiscussion::pluck('title', 'id');
        if(isset($request->save) && !empty($request->save)) {
            $this->validate($request, [
                'chatter_discussion_id' => array('required', 'Integer'),
                'body' => array('required','String'),
            ]);

            $request->merge(array('user_id' => Auth::user()->id));
            ChatterPost::create($request->except(['save', 'files']));
            return redirect('/forum-posts')->with('status', 'Post added successfully.');
        }
        return view('forums/post-create', compact('discussionList'));
    }

    /**
     * PAGE: forum-post/edit/{$post}
     * GET: forum-post/edit/{$post}
     * @param ChatterPost $post Request $request
     * @return
     */
    public function post_edit(ChatterPost $post, Request $request){
        if (!Auth::user()->hasAccess('forum.create')) {
            return redirect()->back()->withErrors(['You do not have permission to view this content']);
        }
        $discussionList = ChatterDiscussion::pluck('title', 'id');
        if(isset($request->save) && !empty($request->save)) {
            $this->validate($request, [
                'chatter_discussion_id' => array('required', 'Integer'),
                'body' => array('required','String'),
            ]);

            $post->update($request->except(['save', 'files']));

            return redirect('/forum-posts')->with('status', 'Post edited successfully.');
        }
        return view('forums/post-create', compact('discussionList', 'post'));
    }



}
