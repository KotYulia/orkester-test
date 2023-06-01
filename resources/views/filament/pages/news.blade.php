@php use App\Models\News; @endphp
<x-filament::page>
    <form method="POST" action="{{ route('filament.pages.news.sync') }}">
        @csrf
        @method('POST')
        <div class="grid grid-cols-1   lg:grid-cols-2   filament-forms-component-container gap-6">
            <input type="text"
                   name="country_code"
                   class="filament-forms-input block w-full transition duration-75 rounded-lg shadow-sm outline-none focus:ring-1 focus:ring-inset disabled:opacity-70 border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                   placeholder="Code of the country you want to get headlines for">
            <button type="submit"
                    class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 filament-page-button-action">
                {{__('Synchronize with News API')}}
            </button>
        </div>
    </form>

    <div>
        @php $news = News::all()->sortByDesc("published_at"); @endphp

        <ul>
            @foreach ($news as $article)
                <li>
                    <h2 class="font-bold">
                        {{ $article->title }}
                    </h2>
                    <p>
                        {{ $article->description }}
                    </p>
                </li>
            @endforeach

        </ul>
    </div>
</x-filament::page>
