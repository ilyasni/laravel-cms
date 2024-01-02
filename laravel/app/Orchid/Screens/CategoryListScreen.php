<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Orchid\Layouts\CategoryListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;

class CategoryListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): array
    {
        return ['categories' => Category::paginate()];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Categories';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.category.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [CategoryListLayout::class];
    }

}
