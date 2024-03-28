<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddActivityRequest;
use App\Models\Activity;

class ActivitiesController extends Controller
{
    public function getAllActivities() {
        die('Not implemented');
    }

    public function addActivity(AddActivityRequest $request) {
        $validatedRequest = $request->validated();
        // dd($request);
        $activity = new Activity;
        $data = $activity->addActivity(
            $validatedRequest['title'],
            $validatedRequest['description'],
            $validatedRequest['color'],
            $request->header('user_id'),
            $validatedRequest['elapsed_time'],
            $validatedRequest['priority'],
            $validatedRequest['category_id']
        );

        if($data["status"] === 202) {
            return response()->json(["status" => $data["status"], "activity" => $data["activity"], "msg" => ""], $data['status']);
        }
        else {
            return response()->json(["status" => $data["status"], "activity" => null, "msg" => "Wrong email or password"], $data['status']);
        }
    }

    public function editActivity() {
        die('Not implemented');
    }
    public function deleteActivity() {
        die('Not implemented');
    }
}
