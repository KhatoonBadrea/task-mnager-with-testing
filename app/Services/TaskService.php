<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Arr;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Events\StatusUpdateLogEvent;
use App\Http\Resources\TaskResource;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Cache;
use App\Events\TaskStatusUpdatedEvent;
use App\Http\Requests\Task\StoreTaskRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class TaskService
{

    use ApiResponseTrait;

    //===================================================getAllTask==========================

    /**
     * fetch the all task from DB and fillter it
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllTask($priority = null, $status = null, $type = null, $due_date = null, $assignedUser = null, $dependentTaskName = null)
    {
        try {
            // Generate a unique cache key based on search criteria
            $cacheKey = 'tasks' . implode('_', array_filter([
                $priority,
                $status,
                $type,
                $due_date,
                $assignedUser,
                $dependentTaskName
            ]));

            // Retrieve from cache or perform a query if not cached
            $tasks = Cache::remember($cacheKey, 3600, function () use ($priority, $status, $type, $due_date, $assignedUser, $dependentTaskName) {
                $query = Task::query();

                if ($priority) {
                    $query->priority($priority);
                }

                if ($status) {
                    $query->status($status);
                }

                if ($type) {
                    $query->type($type);
                }

                if ($due_date) {
                    $query->dueDate($due_date);
                }

                if ($assignedUser) {
                    $query->assignedToUser($assignedUser);
                }

                if ($dependentTaskName) {
                    $query->dependsOnTask($dependentTaskName);
                }

                return $query->get();
            });

            // Store the search frequency 
            $this->storeSearchFrequency($priority, $status, $type, $due_date, $assignedUser, $dependentTaskName);

            // Return the tasks as a collection of resources
            return TaskResource::collection($tasks);
        } catch (\Exception $e) {
            Log::error('Error in TaskService@getAllTask: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Store search frequency based on search criteria
     */
    protected function storeSearchFrequency($priority, $status, $type, $due_date, $assignedUser, $dependentTaskName)
    {
        // Log search queries for analysis and frequency tracking
        $searchCriteria = [
            'priority' => $priority, 
            'status' => $status,
            'type' => $type,
            'due_date' => $due_date,
            'assigned_user' => $assignedUser,
            'dependent_task_name' => $dependentTaskName,
        ];

        // Store search frequency (you can log to the database or another storage)
        // For simplicity, we'll just log it to a file or database table
        Log::info('Search criteria:', $searchCriteria);
    }





    //===================================================create_task==========================

    /**
     * create new task
     * @param array $data
     * @return \Illuminate\Http\JsonResponse 
     */
    public function create_task($data)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $status = is_null(Arr::get($data, 'depends_on')) ? 'Open' : 'Blocked';

        try {
            $task = Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'type' => $data['type'],
                'status' => $status,
                'priority' => $data['priority'],
                'due_date' => null,
                'created_by' => $user->id,
                'assigned_to' => $data['assigned_to'],
                'depends_on' => Arr::get($data, 'depends_on'),
            ]);
            Cache::forget('tasks');
            return true;
        } catch (\Exception $e) {
            Log::error('Error in TaskService@create_Task: ' . $e->getMessage() . $e->getLine());
            return false;
        }
    }

    //===================================================update_task==========================

    /**
     * update the task
     * @param Task $task
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_task(Task $task, array $data)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$task->exists) {
                return $this->notFound('Task not found.');
            }
            //   Update only the fields that are provided in the data array
            $task->update(array_filter([
                'title' => $data['title'] ?? $task->title,
                'description' => $data['description'] ?? $task->description,
                'priority' => $data['priority'] ?? $task->priority,
                'due_date' => $data['due_date'] ?? $task->due_date,
                'status' => $data['status'] ?? $task->status,
                'assigned_to' => $data['assigned_to'] ?? $task->assigned_to,
                'created_by' => $user->id ?? $task->created_by,
                'type' => $data['type'] ?? $task->type,
                'depends_on' => $data['depends_on'] ?? $task->depends_on,
            ]));

            return true;
        } catch (\Exception $e) {
            Log::error('Error in TaskService@update_Task' . $e->getMessage());
            return false;
        } catch (ModelNotFoundException $e) {
            log::error('did not found any think' . $e->getMessage());
            return false;
        } catch (RelationNotFoundException $e) {
            log::error('there is not any relation' . $e->getMessage());
            return false;
        } 
    }



    //===================================================update_assigned_to==========================

    /**
     * update assigned to in the task
     * @param Task $task
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */

    public function update_assigned_to(Task $task, array $data)
    {
        try {

            if (!$task->exists) {
                return $this->notFound('Task not found.');
            }
            //   Update only the fields that are provided in the data array
            $task->update(array_filter([
                'due_date' => null,
                'assigned_to' => $data['assigned_to'] ?? $task->assigned_to,
            ]));


            // Return the updated task as a resource
            return TaskResource::make($task)->toArray(request());
        } catch (\Exception $e) {
            Log::error('Error in TaskService@update_Task' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . 'there is an error in the server', [], 500);
        } catch (ModelNotFoundException $e) {
            log::error('did not found any think' . $e->getMessage());
            return false;
        } catch (RelationNotFoundException $e) {
            log::error('there is not any relation' . $e->getMessage());
            return false;
        }
    }


    //===================================================get_assigned_task_for_user==========================

    /**
     * get all task that assigned to the one user
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_assigned_task_for_user()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $task = Task::where('assigned_to', $user->id)->get();

            return TaskResource::collection($task);
        } catch (\Exception $e) {
            Log::error('Error in TaskService@get_assigned_task_for_user: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . 'there is an error in the server', 500);
        } catch (ModelNotFoundException $e) {
            log::error('did not found any think' . $e->getMessage());
            return false;
        } catch (RelationNotFoundException $e) {
            log::error('there is not any relation' . $e->getMessage());
            return false;
        }
    }

    //===================================================show_task ==========================

    /**
     * show the specific task
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */

    public function show_task(Task $task)
    {
        try {

            if (!$task->exists) {
                return $this->notFound('Task not found.');
            }

            $task = $task->load('comments');
            return $task;
        } catch (\Exception $e) {
            Log::error('Error in TaskService@show_task: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: ' . 'there is an error in the server', 500);
        } catch (ModelNotFoundException $e) {
            log::error('did not found any think' . $e->getMessage());
            return false;
        } catch (RelationNotFoundException $e) {
            log::error('there is not any relation' . $e->getMessage());
            return false;
        }
    }
    /**
     * user can update the status of the task
     * @param Task $task
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */

    public function update_status(Task $task, array $data)
    {
        try {
            $task->status = $data['status'] ?? $task->status;
            $task->save();

            event(new TaskStatusUpdatedEvent($task));
            return $task;
        } catch (\Exception $e) {
            Log::error('Error in TaskService@update_status: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: there is an error in the server', 500);
        } catch (ModelNotFoundException $e) {
            log::error('did not found any think' . $e->getMessage());
            return false;
        } catch (RelationNotFoundException $e) {
            log::error('there is not any relation' . $e->getMessage());
            return false;
        }
    }

    /**
     * user can update the type of the task
     * @param Task $task
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */

    public function update_type(Task $task, array $data)
    {
        try {
            $task->type = $data['type'] ?? $task->type;
            $task->save();

            event(new StatusUpdateLogEvent($task));

            return $task;
        } catch (\Exception $e) {
            Log::error('Error in TaskService@update_status: ' . $e->getMessage());
            return $this->errorResponse('An error occurred: there is an error in the server', 500);
        } catch (ModelNotFoundException $e) {
            log::error('did not found any think' . $e->getMessage());
            return false;
        } catch (RelationNotFoundException $e) {
            log::error('there is not any relation' . $e->getMessage());
            return false;
        }
    }
}
