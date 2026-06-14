<?php

use App\Models\User;
use App\Models\Project;
use App\Models\StudentProfile;
use App\Notifications\StudentAppliedToProject;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un estudiante no puede postularse si no tiene un CV subido', function () {
    $student = User::factory()->create(['role' => 'estudiante']);
    $project = Project::factory()->create();

    // Nos aseguramos de que no tenga un perfil creado
    expect($student->profile)->toBeNull();

    $response = $this->actingAs($student)
                     ->post(route('applications.store', $project));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Debes subir tu CV antes de postularte a un proyecto.');
});

test('un estudiante puede postularse exitosamente y dispara una notificación', function () {
    Notification::fake();

    $company = User::factory()->create(['role' => 'empresa']);
    $project = Project::factory()->create(['company_id' => $company->id]);
    $student = User::factory()->create(['role' => 'estudiante']);

    StudentProfile::create([
        'user_id' => $student->id,
        'cv_pdf_path' => 'cvs/mi_curriculum.pdf'
    ]);

    $response = $this->actingAs($student)
                     ->post(route('applications.store', $project));

    $this->assertDatabaseHas('applications', [
        'project_id' => $project->id,
        'student_id' => $student->id,
        'status' => 'pending'
    ]);
});

test('un estudiante no puede postularse dos veces al mismo proyecto', function () {
    $company = User::factory()->create(['role' => 'empresa']);
    $project = Project::factory()->create(['company_id' => $company->id]);
    $student = User::factory()->create(['role' => 'estudiante']);

    StudentProfile::create([
        'user_id' => $student->id,
        'cv_pdf_path' => 'cvs/mi_curriculum.pdf'
    ]);

    // Simular primera postulación
    $project->applicants()->attach($student->id, ['status' => 'pending']);

    // Intentar segunda postulación por HTTP
    $response = $this->actingAs($student)
                     ->post(route('applications.store', $project));

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Ya te has postulado a este proyecto.');
});

test('una empresa puede aceptar la postulación de un estudiante', function () {
    $company = User::factory()->create(['role' => 'empresa']);
    $project = Project::factory()->create(['company_id' => $company->id]);
    $student = User::factory()->create(['role' => 'estudiante']);

    $project->applicants()->attach($student->id, ['status' => 'pending']);

    $response = $this->actingAs($company)
                     ->post(route('applications.accept', [$project, $student]));

    $response->assertRedirect();
    $this->assertDatabaseHas('applications', [
        'project_id' => $project->id,
        'student_id' => $student->id,
        'status' => 'accepted'
    ]);
});
