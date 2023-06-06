<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;

class StudentsController extends Controller
{
    public function create(Request $request){
        $exists = Students::where('name' ,$request -> name)
            ->where('level', $request -> level)
            ->where('class', $request -> class)
            ->where('parent_contact', $request -> parent_contact)
            ->exists(); 

        if ($exists) {
            return response()->json(['msg'=>'Record Existed.']);
        }else{
            $students = Students::create([
                'name' => $request -> name,
                'level' => $request -> level,
                'class' => $request -> class,
                'parent_contact' => $request -> parent_contact
            ]);
        }
    }

    public function read(Request $request){
        $query = Students::orderByDesc('created_at');
        $students = $query->paginate(20);
        
        return response()->json($students);
    }
}
