<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Skill;
use App\Models\Project;
use App\Models\StudentProfile;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //* Creamos las skills
        $skillPhp = Skill::create(['name' => 'PHP']);
        $skillLaravel = Skill::create(['name' => 'Laravel']);
        $skillVue = Skill::create(['name' => 'Vue']);
        $skillReact = Skill::create(['name' => 'React']);
        $skillNodejs = Skill::create(['name' => 'Node.js']);
        $skillJavascript = Skill::create(['name' => 'Javascript']);

        //* Creamos 2 empresas
        $company1 = User::create([
            'name' => 'Tech Solutions S.L.',
            'email' => 'contacto@techsolutions.com',
            'password' => Hash::make('12345678'),
            'role' => 'empresa',
            'is_validated' => true,
        ]);
        $company2 = User::create([
            'name' => 'Innovate Web Agency',
            'email' => 'info@innovateweb.com',
            'password' => Hash::make('password123'),
            'role' => 'empresa',
            'is_validated' => true,
        ]);

        //* Creamos 2 proyectos para la primera empresa
        Project::create([
            'company_id' => $company1->id,
            'title' => 'Desarrollo de API REST en Laravel',
            'description' => 'Buscamos un estudiante para desarrollar endpoints y lógica de negocio.',
            'is_active' => true,
        ]);
        Project::create([
            'company_id' => $company1->id,
            'title' => 'Desarrollo de aplicación web con Vue.js',
            'description' => 'Necesitamos un estudiante para crear una interfaz de usuario moderna y responsiva.',
            'is_active' => true,
        ]);

        //* Creamos 2 proyectos para la segunda empresa
        Project::create([
            'company_id' => $company2->id,
            'title' => 'Desarrollo de aplicación con React',
            'description' => 'Buscamos un estudiante para desarrollar una aplicación web con React.',
            'is_active' => true,
        ]);
        Project::create([
            'company_id' => $company2->id,
            'title' => 'Desarrollo de backend con Node.js',
            'description' => 'Necesitamos un estudiante para crear una API REST con Node.js.',
            'is_active' => true,
        ]);

        //* Creamos 2 estudiantes y a uno de ellos le asigno skills
        $student1 = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('12345678'),
            'role' => 'estudiante',
            'is_validated' => true,
        ]);
        //* Le añadimos URL al usuario estudiante
        StudentProfile::create([
            'user_id' => $student1->id,
            'github_url' => 'https://github.com/carlos-estudiante',
            'linkedin_url' => 'https://linkedin.com/in/carlos-estudiante',
        ]);

        //* Usamos las relacion skill() definida en el modelo User para asignar skills al estudiante
        $student1->skills()->attach([$skillPhp->id, $skillLaravel->id, $skillVue->id]);

        //* A este estudiante no le asignamos skills para probar el caso de un estudiante sin habilidades
        $student2 = User::create([
            'name' => 'Laura Desarrolladora',
            'email' => 'laura@example.com',
            'password' => Hash::make('password123'),
            'role' => 'estudiante',
            'is_validated' => true,
        ]);

        StudentProfile::create([
            'user_id' => $student2->id,
            'github_url' => 'https://github.com/laura-dev',
            'linkedin_url' => 'https://linkedin.com/in/laura-dev',
        ]);

    }
}
