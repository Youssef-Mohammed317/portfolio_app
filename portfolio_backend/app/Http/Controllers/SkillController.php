<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function getSkills(){
        return SkillResource::collection(Skill::all());
    }
    public function getSkill($id){
        $skill = Skill::find($id);
        if($skill){
            return new SkillResource($skill);
        }else{
            return response()->json([
                'message' => 'Skill not found'
            ]);
        }
    }
    public function index()
    {
        try{
            $skills = Skill::where('user_id', Auth::user()->id)->get();
            return SkillResource::collection($skills);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {

        $req = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5096',
        ]);

        if ($req->fails()) {
            return response()->json(['errors' => $req->errors()], 422);
        }

        try {
            $user = Auth::user();
            if(!$user->email_verified_at){
                return response()->json(['message' => 'Please verify your email first'], 401);
            }
            // save the skill image
            $saveImage = Storage::disk('public')->put('skills', $request->file('image'));
            if (!$saveImage) {
                return response()->json([
                    'message' => 'Image not saved try again',
                ], 500);
            }
            // create the skill
            $skill = new Skill();
            $skill->name = $request->input('name');
            $skill->image = $request->file('image')->hashName();
            $skill->user_id = Auth::user()->id;
            if (!$skill->save()) {
                return response()->json([
                    'message' => 'Skill not created try again',
                ], 500);
            }

            return response()->json([
                'message' => 'Skill created successfully',
                'skill' => new SkillResource($skill),
            ]);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function show(string $id)
    {
        try{
        $skill = Skill::find($id);
        if (!$skill) {
            return response()->json([
                'message' => 'Skill not found',
            ]);
        }
        return new SkillResource($skill);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, string $id)
    {
        $req = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5096',
        ]);

        if ($req->fails()) {
            return response()->json(['errors' => $req->errors()], 422);
        }
        try{
            $user = Auth::user();
            if(!$user->email_verified_at){
                return response()->json(['message' => 'Please verify your email first'], 401);
            }

            $skill = Skill::find($id);

            if (!$skill) {
                return response()->json([
                    'message' => 'Skill not found',
                ], 404);
            }
            if(!$skill->user_id == Auth::user()->id){
                return response()->json([
                    'message' => 'You are not authorized to update this skill',
                ], 403);
            }
            if($request->hasFile('image')) {
                $saveImage = Storage::disk('public')->put('skills', $request->file('image'));
                if (!$saveImage) {
                    return response()->json([
                        'message' => 'Image not saved',
                    ], 500);
                }
                Storage::disk('public')->delete('skills/'.$skill->image);
                $skill->image = $request->file('image')->hashName();
            }
            $skill->name = $request->input('name');

            if($skill->save()){
                return response()->json([
                    'message' => 'Skill updated successfully',
                    'skill' => new SkillResource($skill),
                ]);
            }
            return response()->json([
                'message' => 'Skill not updated',
            ], 500);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            if(!$user->email_verified_at){
                return response()->json(['message' => 'Please verify your email first'], 401);
            }
            $skill = Skill::find($id);
            if (!$skill) {
                return response()->json([
                    'message' => 'Skill not found',
                ], 404);
            }
            if(!$skill->user_id == Auth::user()->id){
                return response()->json([
                    'message' => 'You are not authorized to delete this skill',
                ], 403);
            }
            if(Storage::disk('public')->exists('skills/'.$skill->image)){
                Storage::disk('public')->delete('skills/'.$skill->image);
            }
            if($skill->delete()){
                return response()->json([
                    'message' => 'Skill deleted successfully',
                ]);
            }
            return response()->json([
                'message' => 'Skill not deleted',
            ], 500);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    }
