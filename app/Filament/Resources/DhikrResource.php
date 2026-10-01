<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DhikrResource\Pages;
use App\Filament\Resources\DhikrResource\RelationManagers;
use App\Models\Category;
use App\Models\Dhikr;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DhikrResource extends Resource
{
    protected static ?string $model = Dhikr::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('category');
    }

    public static function getModelLabel(): string
    {
        return __('dhikrs.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dhikrs.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('dhikrs.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('dhikrs.main_section'))
                    ->schema([
                        Select::make('category_id')
                            ->label(__('dhikrs.category'))
                            ->relationship('category', 'name_ar')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('text_ar')
                            ->label(__('dhikrs.text_ar'))
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('repeat_count')
                            ->label(__('dhikrs.repeat_count'))
                            ->required()
                            ->numeric()
                            ->default(1),
                        Forms\Components\TextInput::make('audio_url')
                            ->label(__('dhikrs.audio_url'))
                            ->maxLength(255),
                    ])->columns(2),

                Section::make(__('dhikrs.review_section'))
                    ->schema([
                        Forms\Components\TextInput::make('source')
                            ->label(__('dhikrs.source'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('license')
                            ->label(__('dhikrs.license'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('reviewed_by')
                            ->label(__('dhikrs.reviewed_by'))
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('reviewed_at')
                            ->label(__('dhikrs.reviewed_at')),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name_ar')
                    ->label(__('dhikrs.category'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('text_ar')
                    ->label(__('dhikrs.text_ar'))
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->text_ar)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('repeat_count')
                    ->label(__('dhikrs.repeat_count'))
                    ->numeric(),
                    //->sortable(),
                Tables\Columns\IconColumn::make('audio_url')
                    ->label(__('dhikrs.has_audio'))
                    ->boolean()
                    ->getStateUsing(fn ($record) => ! empty($record->audio_url)),
                Tables\Columns\TextColumn::make('reviewed_by')
                    ->label(__('dhikrs.reviewed_by'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('dhikrs.order'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('dhikrs.category'))
                    ->relationship('category', 'name_ar'),
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
            'index' => Pages\ListDhikrs::route('/'),
            'create' => Pages\CreateDhikr::route('/create'),
            'edit' => Pages\EditDhikr::route('/{record}/edit'),
        ];
    }
}
