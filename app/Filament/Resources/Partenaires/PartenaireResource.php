<?php

namespace App\Filament\Resources\Partenaires;

use App\Filament\Resources\Partenaires\Pages\CreatePartenaire;
use App\Filament\Resources\Partenaires\Pages\EditPartenaire;
use App\Filament\Resources\Partenaires\Pages\ListPartenaires;
use App\Filament\Resources\Partenaires\Schemas\PartenaireForm;
use App\Filament\Resources\Partenaires\Schemas\PartenaireInfolist;
use App\Filament\Resources\Partenaires\Tables\PartenairesTable;
use App\Models\Partenaire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartenaireResource extends Resource
{
    protected static ?string $model = Partenaire::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'App\Models\Partenaire';

    protected static string|UnitEnum|null $navigationGroup = 'Portfolio';

    protected static ?string $pluralLabel = "Equipe & Collaborateurs";

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return PartenaireForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartenaireInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartenairesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartenaires::route('/'),
            'create' => CreatePartenaire::route('/create'),
            'edit' => EditPartenaire::route('/{record}/edit'),
        ];
    }
}
