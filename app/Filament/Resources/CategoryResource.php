<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;
use Filament\Forms\Set;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $slug = 'sv23810310407-categories';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ĐÃ THÊM CODE VÀO TRONG SCHEMA CỦA FORM
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                    
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                    
                Forms\Components\Toggle::make('is_visible')
                    ->default(true),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ĐÃ THÊM CODE HIỂN THỊ CỘT TRONG TABLE
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\IconColumn::make('is_visible')->boolean(),
            ])
            ->filters([
                // ĐÃ THÊM BỘ LỌC TÌM KIẾM
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Trạng thái hiển thị'),
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