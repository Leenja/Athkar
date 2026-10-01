<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('categories.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('categories.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('categories.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_ar')
                    ->label(__('categories.name_ar'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_en')
                    ->label(__('categories.name_en'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label(__('categories.slug'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TimePicker::make('starts_at')
                    ->label(__('categories.starts_at'))
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('h:i A'),
                TimePicker::make('ends_at')
                    ->label(__('categories.ends_at'))
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('h:i A'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('categories.name_ar'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('categories.name_en'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('categories.slug'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label(__('categories.starts_at'))
                    ->time('h:i A'),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label(__('categories.ends_at'))
                    ->time('h:i A'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->paginated(false)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
