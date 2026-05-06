<?php

namespace App\Filament\Resources\StudentProfiles;

use App\Filament\Resources\StudentProfiles\Pages\CreateStudentProfile;
use App\Filament\Resources\StudentProfiles\Pages\EditStudentProfile;
use App\Filament\Resources\StudentProfiles\Pages\ListStudentProfiles;
use App\Filament\Resources\StudentProfiles\Pages\ViewStudentProfile;
use App\Filament\Resources\StudentProfiles\Schemas\StudentProfileForm;
use App\Filament\Resources\StudentProfiles\Schemas\StudentProfileInfolist;
use App\Filament\Resources\StudentProfiles\Tables\StudentProfilesTable;
use App\Models\StudentProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StudentProfileResource extends Resource
{
    protected static ?string $model = StudentProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return StudentProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SkillsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentProfiles::route('/'),
            'create' => CreateStudentProfile::route('/create'),
            'view' => ViewStudentProfile::route('/{record}'),
            'edit' => EditStudentProfile::route('/{record}/edit'),
        ];
    }
}
