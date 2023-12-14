<?php

namespace App\Services;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BlogService {
    public function getdata($param)
    {
        # code...
        $data = Blog::get();

        return $data;
    }

    public function createData($data){

        // dd(auth('sanctum')->user()->id);
        try {
            $data = Blog::create([
                'user_id' => auth('sanctum')->user()->id, // Auth::user()->id (get user id from auth
                'title' => $data['title'],
                'content' => $data['content'],
                'author' => $data['author'],
                'status' => $data['status'],
                'published_at' => $data['status'] == 'published' ? Carbon::now() : NULL,
            ]);

            return [
                'status' => true,
                'data' => $data
            ];

        } catch (\Throwable $th) {
            // dd($th->getMessage());
            return [
                'status' => false,
                'data' => $th->getMessage()
            ];
        }

        
    }

    static function getdataById($id){
        $data = Blog::find($id);
        if($data){
            return [
                'status' => true,
                'data' => $data
            ];
        }else{
            return [
                'status' => false,
                'data' => 'Data not found'
            ];
        }
    }

    static function updateData($data){
        try {

            $data = Blog::where('id', $data['id'])->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'author' => $data['author'],
                'status' => $data['status'],
                'published_at' => $data['status'] == 'published' ? Carbon::now() : NULL,
            ]);

            // dd($data);
    
            return [
                'status' => true,
                'message' => 'Successfully update blog'
            ];
            //code...
        } catch (\Throwable $th) {
            //throw $th;

            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
       
    }
    
}