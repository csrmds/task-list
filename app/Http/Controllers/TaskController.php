<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Google_Client;
use Google_Service_Calendar;

class TaskController extends Controller
{
    private $task;

    public function __construct() {
        $this->task= new Task;
    }

    // public function teste(Request $request) {
    //     $user= Auth::user();
        
    //     $client= new Google_Client();
    //     $client->setAccessToken($user->access_token);
        
    //     $service= new Google_Service_Calendar($client);

    //     $calendarList= $service->calendarList->listCalendarList();
        
    //     return response(json_encode($calendarList));

    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks= Task::all();

        return response(json_encode($tasks));
    }

    public function getTasks(Request $request) {

        logger("getTasks resquest: ", $request->all());

        try {
            $userData= Auth::user();
            $filtroConcluidas= $request->query('concluidas') || false;

            $query= Task::where('user_id', $userData['id']);

            $tasks= $query->when($filtroConcluidas, function($query) {
                return $query->where('status', '!=', 'concluido');
            })
            ->orderBy('agenda_inicio')->get();

            return response()->json([
                'success'=> true,
                'data'=> $tasks
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao listar tarefas',
                'error'=> $th
            ]);
        }

        $tasks= Task::all();
        return response(json_encode($tasks));
    }

    public function getById(Request $request) {
        logger("getById request: ", $request->all());
        $id= $request->input('id');
        $task= Task::find($id);

        return response()->json($task);
    }

    protected function setTask($data) {
        $user= Auth::user();
        logger("setTask user: ", [$user]);

        $this->task->resumo= $data['resumo'];
        $this->task->descricao= $data['descricao'];
        $this->task->agenda_inicio= $data['agenda_inicio'];
        $this->task->agenda_fim= $data['agenda_fim'];
        $this->task->status= $data['status'];
        $this->task->responsavel= $data['responsavel'];
        $this->task->categoria= $data['categoria'];
        $this->task->tags= $data['tags'];
        $this->task->google_calendar_id= $data['google_calendar_id'];
        $this->task->google_calendar_link= $data['google_calendar_link'];
        $this->task->user_id= $user['id'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        logger("store request: ", $request->input('taskData'));

        //return false;
        $this->setTask($request->input('taskData'));

        try {
            $this->task->save();
            return response()->json([
                'success'=> true,
                'message'=> 'Tarefa criada com sucesso',
                'data'=> $this->task
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao criar a tarefa',
                'error'=> $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        logger("Task Controller Update request: ", $request->input('taskData'));

        try {
            $taskData= $request->input('taskData');
            $id= $taskData['id'];
            
            $task= Task::find($id);
            $task->resumo= $taskData['resumo'];
            $task->descricao= $taskData['descricao'];
            $task->agenda_inicio= $taskData['agenda_inicio'];
            $task->agenda_fim= $taskData['agenda_fim'];
            $task->status= $taskData['status'];
            $task->responsavel= $taskData['responsavel'];
            $task->categoria= $taskData['categoria'];
            $task->tags= $taskData['tags'];

            $task->save();
            return response()->json([
                'success'=> true,
                'message'=> 'Tarefa atualizada com sucesso',
                'data'=> $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao atualizar a tarefa',
                'error'=> $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        logger("destroy request: ", [$request->query()]);

        $task= json_decode($request->query('taskData'));

        try {
            $id= $task->id;
            $deleted= Task::destroy($id);
            return response()->json([
                'success'=> true,
                'message'=> 'Tarefa deletada com sucesso',
                'data'=> $deleted
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao deletar a tarefa',
                'error'=> $e->getMessage()
            ]);
        }

    }
}
