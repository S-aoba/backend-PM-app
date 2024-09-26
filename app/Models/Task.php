<?php

namespace App\Models;

use App\Http\Requests\StoreTaskRequest;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'due_date',
        'status',
        'image_path',
        'priority',
        'assigned_user_id',
        'project_id',
        'created_by',
        'updated_by'
    ];


    static function storeTask(StoreTaskRequest $validatedData): void
    {
        // project_idを使ってProjectが存在しているかどうかを判定
        $projectId = $validatedData['projectId'];
        
        if(!Project::find($projectId)) {
            throw new ModelNotFoundException('Project is not exist.');
        }

        // assigned_user_idとcreated_byが異なる場合のみユーザーの検証を行う
        if($validatedData['assignedUserId'] !== $validatedData['created_by'])
        {
            // assign_user_idを使い、そのユーザーが存在しているかつ、タスクを作成するProjectへ参加しているかどうかを判定
            $assignedUserId = $validatedData['assignedUserId'];
    
            // ユーザーが存在しているかを確認
            $user = User::find($assignedUserId);
            if(is_null($user)){
                throw new ModelNotFoundException('User is not exist.');
            }

            // ユーザーがProjectに参加しているかどうかを確認
            $isJoinProject = $user->projects->where('id', $projectId)->isEmpty();
            
            if($isJoinProject) {
                throw new Exception('No user exist in the specified project.');
            }            
        }
        
        // 上記の条件をクリアすればDBへ登録する
        Task::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'due_date' => $validatedData['dueDate'],
            'status' => $validatedData['status'],
            'image_path' => $validatedData['imagePath'],
            'priority' => $validatedData['priority'],
            'assigned_user_id' => $validatedData['assignedUserId'],
            'project_id' => $validatedData['projectId'],
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);
    }
}
