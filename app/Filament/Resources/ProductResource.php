<?php

namespace App\Filament\Resources;

use Illuminate\Support\Str;
use Filament\Forms\Set;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $slug = 'sv23810310407-products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),                       
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),                       
                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Nháp',
                            'published' => 'Xuất bản',
                            'out_of_stock' => 'Hết hàng',
                        ])
                        ->default('draft')
                        ->required(),
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->minValue(0) 
                        ->required(),
                    Forms\Components\TextInput::make('stock_quantity')
                        ->numeric()
                        ->integer() 
                        ->default(0)
                        ->required(),
                    Forms\Components\TextInput::make('discount_percent')
                        ->label('Phần trăm giảm giá (%)')
                        ->numeric()
                        ->integer()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(0),
                    Forms\Components\FileUpload::make('image_path')
                        ->image()
                        ->maxFiles(1) 
                        ->columnSpanFull(),
                    Forms\Components\RichEditor::make('description') 
                        ->columnSpanFull(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('Ảnh'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),                   
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục'),
                Tables\Columns\TextColumn::make('price')
                    ->money('VND') //
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Giảm giá')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state > 50 => 'danger',
                        $state > 0 => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('stock_quantity')->label('Tồn kho'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Danh mục'),
            ]);
    }
    // ...
}