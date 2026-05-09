<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\Application;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';
    protected static ?string $title = 'Alumnos Postulados';
    protected static ?string $modelLabel = 'Postulación';
    protected static ?string $pluralModelLabel = 'Postulaciones';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('student.name')->required()->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.name')->label('Nombre del Alumno')->searchable()->sortable(),
                TextColumn::make('student.email')->label('Email')->searchable(),
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
            ->filters([])
            ->headerActions([])
            ->actions([
                Action::make('accept')
                    ->label('Aceptar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('¿Aceptar postulación?')
                    ->visible(fn (Application $record) => $record->status !== 'accepted')
                    ->action(function (Application $record) {
                        $record->update(['status' => 'accepted']);
                        Notification::make()->title('Postulación Aceptada')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Rechazar postulación?')
                    ->visible(fn (Application $record) => $record->status !== 'rejected')
                    ->action(function (Application $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()->title('Postulación Rechazada')->success()->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
