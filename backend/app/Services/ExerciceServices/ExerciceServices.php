<?php

namespace App\Services\ExerciceServices;

use App\Models\User;
use App\Models\Exercice;
use App\Models\UserExercice;


class ExerciceServices
{
    public function addExercice($request)
    {
        $exercice = Exercice::create([
            'area' => $request->area,
            'muscle' => $request->muscle,
            'exercice' => $request->exercice,
            'description' => $request->description ?? null,
            'difficulty' => $request->difficulty ,
            'equipment' => $request->equipment ?? null,
            'type' => $request->type ?? null,
            'duration' => $request->duration ?? null,
            'image_url' => $request->image_url ?? null,
            'video_url' => $request->video_url ?? null,
            'tags' => $request->tags?? null
        ]);

        if (!$exercice){
            throw new \Exception('Error creating exercice', 500);
        }
        return $exercice;
    }

    public function getExercicesPreviews($request)
    {
        $exercices = Exercice::orderBy('area')
            ->get(['id', 'exercice', 'image_url']);
        if ($exercices->isEmpty()) {
            throw new \Exception('Could not fetch exercices', 500);
        }
        return $exercices;
    }

    public function searchExercice($request)
    {
        $query = Exercice::query();
        if ($request->query('id')) {
            $query->where('id', $request->query('id'));
        }
        if ($request->query('area')) {
            $query->where('area', 'like', '%' . $request->query('area') . '%');
        }
        if ($request->query('muscle')) {
            $query->where('muscle', 'like', '%' . $request->query('muscle') . '%');
        }
        if ($request->query('exercice')) {
            $query->where('exercice', 'like', '%' . $request->query('exercice') . '%');
        }
        if ($request->query('description')) {
            $query->where('description', 'like', '%' . $request->query('description') . '%');
        }
        if ($request->query('min_difficulty')) {
            $query->where('difficulty', '>=', $request->query('min_difficulty'));
        }
        if ($request->query('max_difficulty')) {
            $query->where('difficulty', '<=', $request->query('max_difficulty'));
        }
        if ($request->query('equipment')) {
            $query->where('equipment', 'like', '%' . $request->query('equipment') . '%');
        }
        if ($request->query('type')) {
            $query->where('type', 'like', '%' . $request->query('type') . '%');
        }
        if ($request->query('duration')) {
            $query->where('duration', 'like', '%' . $request->query('duration') . '%');
        }
        if ($request->query('tags')) {
            $query->where('tags', 'like', '%' . $request->query('tags') . '%');
        }

        $exercices = $query->orderBy('area')->get();
        if (!$exercices) {
            throw new \Exception('No exercices found', 404);
        }
        return $exercices;
    }

    

    public function getRecommendedExercices($request)
    {
        $user = auth()->user();
        $recommendations = [];
        $exercises = Exercice::all();
        foreach ($exercises as $exercise) {
            $score = 0;
        
            
            if ($exercise->difficulty === $user->fitness_level) {
                $score += 20;
            }
            if ($exercise->difficulty < $user->fitness_level) {
                $score += 10;
            }

            foreach (json_decode($user->fitness_interests) as $interest) {
                if (in_array($interest, json_decode($exercise->tags) ?? [])) {
                    $score += 10;
                }
            }

            
            if (in_array($exercise->equipment,  json_decode($user->fitness_equipment))) {
                $score += 15;
            }

            
            if ( json_decode($user->injuries) && !in_array($exercise->area, json_decode($user->injuries))) {
                $score += 20;
            }

            $recommendations[] = [
                'exercise' => $exercise,
                'score' => $score,
            ];
        }
        usort($recommendations, fn($a, $b) => $b['score'] <=> $a['score']);

        $topExercises = array_slice($recommendations, 0, 3);

        return $topExercises;

    }

    public function getSavedExercices($request)
    {
        $user = auth()->user();
        return $user->exercices()->withPivot('id','sets')->wherePivotNull('deleted_at')->wherePivot('is_completed',false)->get();
    }
    
    public function incrementSetCount($id)
    {
        $row=UserExercice::find($id);
        if(!$row){
            throw new Exception('Row not found',404);
        }
        $result=$row->increment('sets');
        if(!$result){
            throw new Exception('Could not increment',500);
        }
        $newRow = UserExercice::find($id);
        return $newRow;
    }

    public function decrementSetCount($id)
    {
        $row=UserExercice::find($id);
        if(!$row){
            throw new Exception('Row not found',404);
        }
        $result=$row->decrement('sets');
        if(!$result){
            throw new Exception('Could not decrement',500);
        }
        $newRow = UserExercice::find($id);
        return $newRow;
    }

    public function completeExercice($id)
    {
        $row = UserExercice::find($id);
        if(!$row){
            throw new Exception("Row not found",404);
        }
        $row->is_completed = true;
        $row->completed_at = now();
        if (!$row->save()){
            throw new Exception("Could not save row",500);
        }
        return $row;
    }

    public function deleteExercice($id)
    {
        $row = UserExercice::find($id);
        if(!$row){
            throw new Exception("Row not found",404);
        }
        if(!$row->delete()){
            throw new Exception("Row not deleted",500);
        }
        return;
    }

    public function saveExercice($exerciceId)
    {
        $userId = auth()->user()->id;
        $exercice = Exercice::find($exerciceId);
        if(!$exercice){
            throw new Exception("exercice not found",404);
        }

        $userExercice = UserExercice::create([
            'user_id'=>$userId,
            'exercice_id'=>$exerciceId
        ]);

        return $userExercice;
    }
}