<div class="card shadow mb-4">
    <!-- Header -->
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Main Categories') }}</h6>
        <a href="{{ route('admins.main-categories.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus me-1"></i> {{ __('Add New') }}
        </a>
    </div>

    <div class="px-3 py-2 border-bottom bg-light">
        <div class="d-flex align-items-center gap-2 flex-wrap ">
            <input wire:model.live.debounce.500="search" type="text"
                class="form-control form-control-sm w-auto flex-grow-1" placeholder="{{ __('Search by name...') }}">
        </div>
    </div>

    <!-- Table -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Serial') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Banner Image') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $i => $category)
                        <tr wire:key="category-{{ $category->id }}">
                            <td>{{ $categories->firstItem() + $i }}</td>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $category->getTranslation('name', $rev_locale) }}</small>
                            </td>
                            <td>
                                {{ strip_tags($category->description) }}
                                <br>
                                <small
                                    class="text-muted">{{ strip_tags($category->getTranslation('description', $rev_locale)) }}</small>
                            </td>
                            <td>
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="img-thumbnail w_200"
                                        alt="">
                                @else
                                    <span class="text-muted">{{ __('No image') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($category->banner_image)
                                    <img src="{{ asset('storage/' . $category->banner_image) }}"
                                        class="img-thumbnail w_200" alt="">
                                @else
                                    <span class="text-muted">{{ __('No Banner Image') }}</span>
                                @endif
                            </td>

                            <td>
                                <input type="checkbox" @if ($category->status == 'active') checked @endif
                                    data-toggle="toggle" data-on="{{ __('Active') }}" data-off="{{ __('Pending') }}"
                                    data-onstyle="success" data-id = "{{ $category->id }}" data-offstyle="danger"
                                    wire:change="toggleStatus({{ $category->id }})">
                            </td>

                            <td class="text-nowrap">
                                <a href="{{ route('admins.main-categories.edit', $category->id) }}"
                                    class="btn btn-warning btn-sm mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="delete-form-{{ $category->id }}"
                                    action="{{ route('admins.main-categories.destroy', $category->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mx-1"
                                        onclick="confirmDelete({{ $category->id }}); event.preventDefault();">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                {{ __('No categories found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="py-3 d-flex justify-content-between">
            {{ $categories->links() }}
        </div>
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

                    console.log(`[Bootstrap Toggle] Initialized ${$toggles.length} toggles`);
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
