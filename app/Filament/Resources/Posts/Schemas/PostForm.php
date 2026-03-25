<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->description('Fill in details of the post')
                    ->icon('heroicon-o-document-text')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            // ->required()
                            ->rules(["required", "min:5", "max:10"]),
                        TextInput::make('slug')
                            ->rules('required', 'min:3')
                            ->unique()
                            ->validationMessages([
                                'unique' => 'Slug harus unik dan tidak boleh sama',
                            ]),
                        Select::make('category_id')
                            ->required()
                            ->relationship('category', 'name')
                            ->options(Category::all()->pluck('name', 'id'))
                            // ->preload()
                            ->searchable(),
                        ColorPicker::make('color'),
                        MarkdownEditor::make('content')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(2),
                Group::make([
                    Section::make('Image Upload')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('image')
                                ->required()
                                ->validationMessages([
                                    'required' => 'Image is required',
                                ])
                                ->disk('public')
                                ->directory('post'),
                        ]),
                    Section::make('Meta Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Select::make('tags')
                                ->relationship('postTags', 'name')
                                ->multiple()
                                ->preload(),
                            Checkbox::make('published'),
                            DatePicker::make('published_at'),
                        ]),
                ])->columnSpan(1),
            ])
            ->columns(3);
    }
}
