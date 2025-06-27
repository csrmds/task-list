<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela para evitar duplicatas (opcional)
        DB::table('tasks')->truncate();

        // Insere dados de teste
        Task::insert([
            [
                'descricao'      => 'Planejar reunião semanal',
                'agenda_inicio'  => '2025-06-25 09:00:00',
                'agenda_fim'     => '2025-06-25 10:00:00',
                'status'         => 'A fazer',
                'responsavel'    => 'João',
                'categoria'      => 'Reunião',
                'tags'           => 'planejamento, equipe',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'descricao'      => 'Desenvolver nova funcionalidade',
                'agenda_inicio'  => '2025-06-28 13:00:00',
                'agenda_fim'     => '2025-06-28 18:00:00',
                'status'         => 'Em progresso',
                'responsavel'    => 'Maria',
                'categoria'      => 'Desenvolvimento',
                'tags'           => 'backend, api',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'descricao'      => 'Testar módulo de pagamento',
                'agenda_inicio'  => '2025-06-30 14:30:00',
                'agenda_fim'     => '2025-06-30 17:30:00',
                'status'         => 'Feito',
                'responsavel'    => 'Carlos',
                'categoria'      => 'Testes',
                'tags'           => 'qa, pagamentos',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'descricao'      => 'Revisar código do frontend',
                'agenda_inicio'  => '2025-07-01 10:00:00',
                'agenda_fim'     => '2025-07-01 12:00:00',
                'status'         => 'A fazer',
                'responsavel'    => 'Ana',
                'categoria'      => 'Revisão',
                'tags'           => 'frontend, revisão',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'descricao'      => 'Atualizar documentação do projeto',
                'agenda_inicio'  => '2025-07-03 08:00:00',
                'agenda_fim'     => '2025-07-03 10:00:00',
                'status'         => 'Em progresso',
                'responsavel'    => 'Lucas',
                'categoria'      => 'Documentação',
                'tags'           => 'docs, projeto',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
