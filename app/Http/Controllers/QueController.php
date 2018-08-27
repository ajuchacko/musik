<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Que;
use App\Track;
use Illuminate\Support\Facades\DB;

class QueController extends Controller
{
    public function destroy()
    {
      $que = new Que;
      $que->reset();
      return response()->json([], 202);
    }

    public function index()
    {
      $que = new Que;

      $current_que = $que->currentQue();
      if($current_que) {
        $collect = DB::table('tracks')
                    ->whereIn('title', $current_que['que'])
                    ->get();
      } else {
        $collect = collect([]);
      }
      return view('que.index', ['tracks' => $collect]);
    }
}
