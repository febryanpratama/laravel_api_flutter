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
                'published_at' => Carbon::parse($data['published_at']),
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
    
}