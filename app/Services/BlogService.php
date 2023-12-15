<?php

namespace App\Services;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BlogService {
    public function getdata($param)
    {
        # code...
        
        if(isset($param['status']) || isset($param['date']) || isset($param['author'])){
            $stats = $param['status'] == "yes" ? "asc" : "desc";
            $author = $param['author'] == "yes" ? "asc" : "desc";

            $data = Blog::with('user')->where(function($y) use ($param){
                if($param['date'] == "today"){
                    $y->whereDate("created_at", Carbon::today());
                }else if($param['date'] == "week"){
                    $y->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                }else if($param['date'] == "month"){
                    $y->whereMonth('created_at', Carbon::now()->month);
                }else if($param['date'] == "year"){
                    $y->whereYear('created_at', Carbon::now()->year);
                }else{
                    $y->orderBy('created_at', 'ASC');
                }   
            })
            ->orderBy('status', $stats)
            ->orderBy('author', $author)
            ->get();
            
            return [
                'status' => true,
                'data' => $data
            ];
        }

        return [
            'status' => false,
            'message' => 'Please input status, date, or author'
        ];
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