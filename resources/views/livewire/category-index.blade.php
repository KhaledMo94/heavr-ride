<div class="mb-4 shadow card">
    <div class="py-3 card-header">
        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
        <div class="float-right d-inline">
            <a href="{{ route('admins.categories.create') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-plus"></i>{{ __('Add New') }}</a>
        </div>
    </div>
    <div class="px-3 py-2 border-bottom bg-light">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input wire:model.live.debounce.500="search" type="text"
                class="form-control form-control-sm w-auto flex-grow-1"
                placeholder="{{ __('Search by name or description...') }}">

            <!-- 🔽 Main Category Filter -->
            <select wire:model.live="selectedMainCategory" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Main Categories') }}</option>
                @foreach ($mainCategories as $mainCategory)
                    <option value="{{ $mainCategory->id }}">
                        {{ $mainCategory->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Serial') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Parent Category') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Banner Image') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($categories as $category)
                        <tr wire:key="category-{{ $category->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $category->name ?? '' }} <br>
                                {{ $category->getTranslation('name', $rev_locale) }}</td>
                            <td>{{ $category->mainCategory->name ?? '' }} <br>
                                {{ $category->mainCategory->getTranslation('name', $rev_locale) }}</td>
                            <td>{{ $category->description }} <br>
                                {{ $category->getTranslation('description', $rev_locale) }}</td>
                            @if (!is_null($category->image))
                                <td><img src="{{ asset('storage/' . $category->image) }}" alt=""
                                        class="w_200"></td>
                            @else
                                <td>
                                    <p>{{ __('No image') }}</p>
                                </td>
                            @endif
                            @if (!is_null($category->banner_image))
                                <td><img src="{{ asset('storage/' . $category->banner_image) }}" alt=""
                                        class="w_200"></td>
                            @else
                                <td>
                                    <p>{{ __('No Banner image') }}</p>
                                </td>
                            @endif
                            <td>
                                <input type="checkbox" @if ($category->status == 'active') checked @endif
                                    data-toggle="toggle" data-on="{{ __('Active') }}" data-off="{{ __('Pending') }}"
                                    data-onstyle="success" data-id = "{{ $category->id }}" data-offstyle="danger"
                                    wire:change="toggleStatus({{ $category->id }})">
                            </td>
                            <td class="">
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <a href="{{ route('admins.categories.edit', $category->id) }}"
                                        class="mx-1 btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $category->id }}"  
                                        action="{{ route('admins.categories.destroy', $category->id) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $category->id }}); event.preventDefault();"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="py-2 d-flex justify-content-center">
        {{ $categories->links() }}
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const initBootstrapToggle = () => {
                setTimeout(() => {
                    const $toggles = $('input[data-toggle="toggle"]');

                    $toggles.off('change.toggleStatus').on('change.toggleStatus', function() {
                        const id = $(this).data('id');
                        console.log(`[Toggle] Status changed for category ID: ${id}`);

                        // Call Livewire function directly from JS
                        Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                                'wire:id'))
                            .call('toggleStatus', id);
                    });

                    // Initialize bootstrap toggles
                    try {
                        $toggles.bootstrapToggle('destroy');
                    } catch (e) {}
                    $toggles.bootstrapToggle();

                }, 150);
            };

            initBootstrapToggle();

            Livewire.hook('commit', ({
                respond
            }) => {
                respond(() => {
                    initBootstrapToggle();
                });
            });

            Livewire.on('jsInit', initBootstrapToggle);
        });
    </script>
</div>
