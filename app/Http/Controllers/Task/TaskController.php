<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
       /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get paginated list of tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="errors", type="null"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->orderBy("id", "desc")->paginate(10);
        return response()->json(['success' => true, 'errors' => null, 'data' => new TaskCollection($tasks)], 200);
    }
    /**
 * @OA\Post(
 *     path="/api/tasks",
 *     tags={"Tasks"},
 *     summary="Create a new task",
 *     description="Create a new task with title and description",
 *     operationId="createTask",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title"},
 *             @OA\Property(property="title", type="string", example="Task Title"),
 *             @OA\Property(property="description", type="string", example="Task Description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Task created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="errors", type="null"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}}),
 *             @OA\Property(property="data", type="null")
 *         )
 *     )
 * )
 */
      public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'=> 'required',
                'description'=> 'nullable',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['success' => false,'errors' => $e->validator->errors(),'data'=>null], 422);
          }


        $task=Task::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'user_id' => $request->user()->id,


        ]);

        return response()->json(['success' => true,'errors'=>null,'data'=>$task], 201);

    }

        /**
     * @OA\Get(
     *     path="/api/tasks/{task}",
     *     summary="Get a specific task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID of the task",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example="true"),
     *             @OA\Property(property="errors", type="null"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Task belongs to another user",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example="false"),
     *             @OA\Property(property="errors", type="string", example="This task belongs to another user"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function show(Request $request,Task $task)
    {
        $user_id=$request->user()->id;
        if($task->user_id!=$user_id){
            return response()->json(['success' => false,'errors'=>'this task belong other users','data'=>null], 401);

        }
        return response()->json(['success' => true,'errors'=>null,'data'=>new TaskResource($task)], 200);
        
    }

        /**
 * @OA\Put(
 *     path="/api/tasks/{task}",
 *     summary="Update a specific task",
 *     tags={"Tasks"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         required=true,
 *         description="ID of the task",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Updated title"),
 *             @OA\Property(property="description", type="string", example="Updated description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Task updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example="true"),
 *             @OA\Property(property="errors", type="null"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Task belongs to another user",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example="false"),
 *             @OA\Property(property="errors", type="string", example="This task belongs to another user"),
 *             @OA\Property(property="data", type="null")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example="false"),
 *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required"}}),
 *             @OA\Property(property="data", type="null")
 *         )
 *     )
 * )
 */
    public function update(Request $request, Task $task)
    {
        try {
            $validated = $request->validate([
                'title'=> 'required',
                'description'=> 'nullable',

          ]);
          } catch (ValidationException $e) {
              // Validation failed, return a JSON response with validation errors
              return response()->json(['success' => false,'errors' => $e->validator->errors(),'data'=>null], 422);
          }
          $user_id=$request->user()->id;
          if($task->user_id!=$user_id){
              return response()->json(['success' => false,'errors'=>'this task belong other users','data'=>null], 401);
  
          }


        $task->update([
            'title' => $request['title'],
            'description' => $request['description'],

        ]);

        return response()->json(['success' => true,'errors'=>null,'data'=>$task], 201);
    }

   /**
 * @OA\Delete(
 *     path="/api/tasks/{task}",
 *     tags={"Tasks"},
 *     summary="Delete a task",
 *     description="Deletes a task by ID",
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         required=true,
 *         description="ID of the task to delete",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example="true"),
 *             @OA\Property(property="errors", type="null"),
 *             @OA\Property(property="data", type="string", example="task deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example="false"),
 *             @OA\Property(property="errors", type="string", example="Task not found"),
 *             @OA\Property(property="data", type="null")
 *         )
 *     ),
 * )
 */
public function destroy(Task $task)
{
    $task->delete();
    return response()->json(['success' => true, 'errors' => null, 'data' => 'task deleted'], 201);
}
    /**
 * @OA\Put(
 *     path="done/tasks/{task}",
 *     tags={"Tasks"},
 *     summary="Mark a task as done",
 *     description="Marks a task as done by updating the `done_at` field",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         required=true,
 *         description="ID of the task to mark as done",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example="true"),
 *             @OA\Property(property="errors", type="null"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example="false"),
 *             @OA\Property(property="errors", type="string", example="This task belongs to other users"),
 *             @OA\Property(property="data", type="null")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example="false"),
 *             @OA\Property(property="errors", type="string", example="Task not found"),
 *             @OA\Property(property="data", type="null")
 *         )
 *     ),
 * )
 */
public function doneTask(Request $request, Task $task)
{
    $user_id = $request->user()->id;
    if ($task->user_id != $user_id) {
        return response()->json(['success' => false, 'errors' => 'This task belongs to other users', 'data' => null], 401);
    }

    $task->update([
        'done_at' => Carbon::now(),
    ]);

    return response()->json(['success' => true, 'errors' => null, 'data' => $task], 201);
}
}
