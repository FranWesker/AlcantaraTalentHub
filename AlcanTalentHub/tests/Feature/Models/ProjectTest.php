<?php

use App\Models\Project;
use App\Models\User;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un proyecto pertenece a una empresa', function () {
    $company = User::factory()->create(['role' => 'empresa']);

    // Insertamos manualmente los atributos obligatorios para evitar la falta de Factory
    $project = Project::create([
        'company_id' => $company->id,
        'title' => 'Proyecto Test',
        'description' => 'Descripción del proyecto',
        'is_active' => true,
    ]);

    expect($project->company)->toBeInstanceOf(User::class);
    expect($project->company->id)->toBe($company->id);
});

test('un proyecto puede tener múltiples estudiantes postulados', function () {
    $company = User::factory()->create(['role' => 'empresa']);
    $project = Project::create([
        'company_id' => $company->id,
        'title' => 'Proyecto Test',
        'description' => 'Descripción',
        'is_active' => true,
    ]);

    // Usamos el rol correcto 'estudiante' según tu restricción CHECK
    $student1 = User::factory()->create(['role' => 'estudiante']);
    $student2 = User::factory()->create(['role' => 'estudiante']);

    $project->applicants()->attach($student1->id, ['status' => 'pending']);
    $project->applicants()->attach($student2->id, ['status' => 'pending']);

    expect($project->applicants)->toHaveCount(2);
});

test('el scope oculta los proyectos donde el estudiante fue rechazado', function () {
    $student = User::factory()->create(['role' => 'estudiante']);
    $company = User::factory()->create(['role' => 'empresa']);

    $projectActive = Project::create(['company_id' => $company->id, 'title' => 'P1', 'description' => 'D', 'is_active' => true]);
    $projectRejected = Project::create(['company_id' => $company->id, 'title' => 'P2', 'description' => 'D', 'is_active' => true]);

    Application::create([
        'project_id' => $projectRejected->id,
        'user_id' => $student->id,
        'student_id' => $student->id,
        'status' => 'rejected'
    ]);

    $visibleProjects = Project::hideRejectedForStudent($student)->get();

    expect($visibleProjects)->toHaveCount(1);
    expect($visibleProjects->first()->id)->toBe($projectActive->id);
});
