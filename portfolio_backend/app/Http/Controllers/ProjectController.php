<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function getProjects(){
        return ProjectResource::collection(Project::all());
    }
    public function getProject($id){
        $project = Project::find($id);
        if($project){
            return new ProjectResource($project);
        }else{
            return response()->json([
                'message' => 'Project not found'
            ]);
        }
    }

    public function index()
    {
        try {
            $projects = Project::where('user_id', Auth::user()->id)->get();
            return ProjectResource::collection($projects);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        $req = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5096',
            'project_url' => 'nullable|url',
            'skill_id' => 'required|exists:skills,id',
        ]);

        if ($req->fails()) {
            return response()->json(['errors' => $req->errors()], 422);
        }
        try {
            $user = Auth::user();
            if(!$user->email_verified_at){
                return response()->json(['message' => 'Please verify your email first'], 401);
            }
        // save the project image
        $saveImage = Storage::disk('public')->put('projects', $request->file('image'));
        if (!$saveImage) {
            return response()->json([
                'message' => 'Image not saved',
            ], 500);
        }
        // create the project
        $project = new Project();
        $project->name = $request->input('name');
        $project->image = $request->file('image')->hashName();
        $project->project_url = $request->input('project_url');
        $project->skill_id = $request->input('skill_id');
        $project->user_id = Auth::user()->id;

        if (!$project->save()) {
            return response()->json([
                'message' => 'Project not created',
            ], 500);
        }

        return response()->json([
            'message' => 'Project created successfully',
            'project' => new ProjectResource($project),
        ]);
    } catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()], 500);
    }
    }

    public function show(string $id)
    {
        try{
            $project = Project::find($id);
            if (!$project) {
                return response()->json([
                    'message' => 'Project not found',
                ]);
            }
            return new ProjectResource($project);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        if(!$user->email_verified_at){
            return response()->json(['message' => 'Please verify your email first'], 401);
        }
        $req = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5096',
            'project_url' => 'nullable|url',
            'skill_id' => 'required|exists:skills,id',
        ]);

        if ($req->fails()) {
            return response()->json(['errors' => $req->errors()], 422);
        }
        try {
            $project = Project::find($id);
            if (!$project) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            if(!$project->user_id == Auth::user()->id){
                return response()->json([
                    'message' => 'You are not authorized to update this project',
                ], 403);
            }
            if($request->hasFile('image')) {
                $saveImage = Storage::disk('public')->put('projects', $request->file('image'));
                if (!$saveImage) {
                    return response()->json([
                        'message' => 'Image not saved',
                    ], 500);
                }
                Storage::disk('public')->delete('projects/'.$project->image);
                $project->image = $request->file('image')->hashName();
            }
            $project->name = $request->input('name');
            $project->project_url = $request->input('project_url');
            $project->skill_id = $request->input('skill_id');
            if($project->save()){
                return response()->json([
                    'message' => 'Project updated successfully',
                    'project' => new ProjectResource($project),
                ]);
            }
            return response()->json([
                'message' => 'Project not updated',
            ], 500);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            if(!$user->email_verified_at){
                return response()->json(['message' => 'Please verify your email first'], 401);
            }
            $project = Project::find($id);
            if (!$project) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            if(!$project->user_id == Auth::user()->id){
                return response()->json([
                    'message' => 'You are not authorized to delete this project',
                ]);
            }
            if(Storage::disk('public')->exists('projects/'.$project->image)){
                Storage::disk('public')->delete('projects/'.$project->image);
            }
            if($project->delete()){
                return response()->json([
                    'message' => 'Project deleted successfully',
                ]);
            }
            return response()->json([
                'message' => 'Project not deleted',
            ], 500);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
