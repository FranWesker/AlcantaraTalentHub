<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use App\Models\Application;
use Filament\Notifications\Notification;


class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    protected static ?string $title = 'Alumnos Postulados';
    protected static ?string $modelLabel = 'Postulación';
    protected static ?string $pluralModelLabel = 'Postulaciones';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('student.name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nombre del Alumno')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        'pending'  => 'warning',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accepted' => 'Aceptado',
                        'rejected' => 'Rechazado',
                        'pending'  => 'Pendiente',
                        default    => ucfirst($state),
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                // Acción para Aceptar al alumno
                Action::make('accept')
                    ->label('Aceptar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation() // Pide confirmación antes de ejecutar
                    ->modalHeading('¿Aceptar postulación?')
                    ->modalDescription('El alumno será aceptado en el proyecto.')
                    ->visible(fn (Application $record) => $record->status !== 'accepted') // Ocultar si ya está aceptado
                    ->action(function (Application $record) {
                        $record->update(['status' => 'accepted']);

                        Notification::make()
                            ->title('Postulación Aceptada')
                            ->success()
                            ->send();
                    }),

                // Acción para Rechazar al alumno
                Action::make('reject')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation() // Pide confirmación antes de ejecutar
                    ->modalHeading('¿Rechazar postulación?')
                    ->modalDescription('El alumno será rechazado. Esta acción no se puede deshacer.')
                    ->visible(fn (Application $record) => $record->status !== 'rejected') // Ocultar si ya está rechazado
                    ->action(function (Application $record) {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('Postulación Rechazada')
                            ->success() // Usamos success porque la acción se completó correctamente
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
