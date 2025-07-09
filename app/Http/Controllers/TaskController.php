<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    private $task;

    public function __construct() {
        $this->task= new Task;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks= Task::all();

        return response(json_encode($tasks));
    }

    public function getTasks() {
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
        $this->task->descricao= $data['descricao'];
        $this->task->agenda_inicio= $data['agenda_inicio'];
        $this->task->agenda_fim= $data['agenda_fim'];
        $this->task->status= $data['status'];
        $this->task->responsavel= $data['responsavel'];
        $this->task->categoria= $data['categoria'];
        $this->task->tags= $data['tags'];
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
            return response(json_encode($this->task));
        } catch(\Exception $e) {
            return response(json_encode($e->getMessage()));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        logger("destroy request: ", $request->all());

        try {
            $id= $request->input('id');
            $deleted= Task::destroy($id);
            return response(json_encode($deleted));
        } catch(\Exception $e) {
            return response(json_encode($e->getMessage()));
        }

    }
}
