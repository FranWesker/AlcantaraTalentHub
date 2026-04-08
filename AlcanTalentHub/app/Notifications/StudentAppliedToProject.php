<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentAppliedToProject extends Notification
{
    use Queueable;

    public $student;
    public $project;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $student,Project $project)
    {
        $this->student = $student;
        $this->project = $project;
    }

    /**
     * Usaremos la base de datos para guardar las notificaciones
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Notificación que se guardará en la base de datos
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'message' => "El estudiante {$this->student->name} ha postulado a tu proyecto: {$this->project->title}.",
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
