<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('source_id')
                    ->maxLength(255)->autocomplete(),
                Forms\Components\TextInput::make('source_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('author')
                    ->maxLength(255),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Textarea::make('description'),
                Textarea::make('content'),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->required(),
                Forms\Components\TextInput::make('urlToImage')
                    ->url(),
                DateTimePicker::make('published_at')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source_id')->searchable(),
                Tables\Columns\TextColumn::make('source_name')->searchable(),
                Tables\Columns\TextColumn::make('author')->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(20),
                Tables\Columns\TextColumn::make('description')->searchable()->limit(20),
                Tables\Columns\TextColumn::make('url')->searchable()->limit(20),
                Tables\Columns\TextColumn::make('urlToImage')->searchable()->limit(20),
                Tables\Columns\TextColumn::make('published_at')->dateTime('d-M-Y')->sortable()
            ])
            ->defaultSort('published_at')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
