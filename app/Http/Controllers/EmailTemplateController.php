<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Crypt;
use Flash;
use Cache;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:email_template.view', ['only' => ['index', 'show']]);
        $this->middleware('check_for_permission.access:email_template.update', ['only' => ['edit', 'update']]);
        $this->title = trans('comman.emiltemplate');
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        $email_template = EmailTemplate::orderBy('id','asc')->paginate($per_page);

        view()->share('email_template', $email_template);
        return view('email-template-module.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $id = Crypt::decryptString($id);
        view()->share('module_action', array(
                "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('email-template.index'),
                    "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
            ));
        $email_template = EmailTemplate::find($id);
        return view('email-template-module.view', compact('email_template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = Crypt::decryptString($id);
        view()->share('module_action', array(
                "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('email-template.index'),
                    "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
            ));
        $email_template = EmailTemplate::find($id);
        return view('email-template-module.edit', compact('email_template'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $id = Crypt::decryptString($id);
         $email_template = EmailTemplate::findOrFail($id);
         $input = $request->all();
         $result = $this->validate($request, [
            'template_name' => "required",
            'slug' => "required",
            'to' => "required",
            'subject' => "required",
            'content'=>"required"
        ]);
        $email_template->update($input);
        Flash::success(trans("comman.email_template_update"));
        if ($request->get('save_exit')) {
            return redirect()->route('email-template.index');
        } else {
            return redirect()->route('email-template.edit',Crypt::encryptString($id));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
