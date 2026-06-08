<?php

use App\Models\Project;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('el administrador tiene acceso total por el método before', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $project = Project::factory()->create();

    // El administrador puede actualizar cualquier proyecto aunque no sea suyo
    expect($admin->can('update', $project))->toBeTrue();
});

test('un estudiante no puede ver un proyecto si su postulación fue rechazada', function () {
    // Forzamos el rol 'student' que es el que evalúa tu ProjectPolicy en el código
    $student = User::factory()->create(['role' => 'estudiante']);
    $project = Project::factory()->create();

    // Insertamos rellenando de forma segura ambos campos (user_id y student_id)
    // para cumplir tanto con la relación hasMany como con el pivote de applicants
    $project->applications()->create([
        'user_id' => $student->id,
        'student_id' => $student->id,
        'status' => 'rejected'
    ]);

    // Refrescamos la relación en memoria
    $project->refresh();

    $student->role = 'estudiante'; // Aseguramos que el rol es correcto

    expect($student->can('view', $project))->toBeFalse();
});

test('una empresa solo puede modificar o eliminar sus propios proyectos', function () {
    $companyOwner = User::factory()->create(['role' => 'empresa']);
    $companyStranger = User::factory()->create(['role' => 'empresa']);

    $project = Project::factory()->create(['company_id' => $companyOwner->id]);

    // La dueña puede editar y eliminar
    expect($companyOwner->can('update', $project))->toBeTrue();
    expect($companyOwner->can('delete', $project))->toBeTrue();

    // Otra empresa no puede
    expect($companyStranger->can('update', $project))->toBeFalse();
    expect($companyStranger->can('delete', $project))->toBeFalse();
});

test('solo administradores o la empresa dueña pueden ver postulantes', function () {
    $company = User::factory()->create(['role' => 'empresa']);
    $student = User::factory()->create(['role' => 'estudiante']);
    $project = Project::factory()->create(['company_id' => $company->id]);

    expect($company->can('viewApplicants', $project))->toBeTrue();
    expect($student->can('viewApplicants', $project))->toBeFalse();
});
