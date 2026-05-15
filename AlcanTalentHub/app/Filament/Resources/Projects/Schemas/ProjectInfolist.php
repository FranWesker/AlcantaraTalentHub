<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('company_id')
                    ->label('ID de la Empresa')
                    ->numeric(),
                TextEntry::make('title')
                    ->label('Nombre del Proyecto'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->html() // Agregamos este método para que interprete el HTML
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->label('¿Activo?')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
