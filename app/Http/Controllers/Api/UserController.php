<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use App\Utils\ResponseCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    protected $blogService;
    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function getBlog(Request $request){
        // dd($request->all());

        $response = $this->blogService->getdata($request->all());

        if($response['status']){
            return ResponseCode::successGet('Successfully get blogs', $response['data']);
        }else{
            return ResponseCode::errorPost('Failed get blogs');
        }
    }

    // public function getBlogById(Request $request, $id){

    //     $response = $this->blogService->getdataById($request->all(), $id);
    //     return ResponseCode::successGet('Successfully get blogs', $data);
    // }

    public function createBlog(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'content' => 'required',
            'author' => 'required',
            'status' => 'required|in:published,draft',
        ]);

        if($validator->fails()){
            return ResponseCode::errorPost( $validator->errors()->first());
        }

        $response = $this->blogService->createData($request->all());

        if($response['status']){
            return ResponseCode::successPost('Successfully create blog', $response);
        }else{
            return ResponseCode::errorPost('Failed create blog', $response);
        }
    }

    public function getBlogById($id){
        $response = $this->blogService->getdataById($id);
        if($response['status']){
            return ResponseCode::successGet('Successfully get blog', $response['data']);
        }else{
            return ResponseCode::errorPost('Failed get blog');
        }
    }

    public function updateBlog(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:blogs,id',
            'title' => 'required',
            'content' => 'required',
            'author' => 'required',
            'status' => 'required|in:published,draft',
        ]);

        if($validator->fails()){
            return ResponseCode::errorPost($validator->errors()->first());
        }

        // dd($request->all());
        $response = $this->blogService->updateData($request->all());

        if(!$response['status']){
            return ResponseCode::errorPost($response['message']);
        }else{
            return ResponseCode::successPost('Successfully update blog', NULL);
        }
    }


    public function createComment(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'blog_id' => 'required|exists:blogs,id',
            'comment' => 'required',
        ]);

        if($validator->fails()){
            return ResponseCode::errorPost($validator->errors()->first());
        }

        $response = $this->blogService->createComment($request->all());

        if($response['status']){
            return ResponseCode::successPost('Successfully create comment', $response);
        }
        return ResponseCode::errorPost('Failed create comment');
    }
}
