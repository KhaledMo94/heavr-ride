<div class="mb-4 shadow card">
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <div class="py-3 card-header">
        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
        <div class="float-right d-inline">
            <a href="{{ route('admins.providers.create') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-plus"></i>{{ __('Add New') }}</a>
        </div>
    </div>
    <div class="px-3 py-2 border-bottom bg-light">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            
            <input wire:model.live.debounce.500="search" type="text"
                class="form-control form-control-sm w-auto flex-grow-1"
                placeholder="{{ __('Search by name or description...') }}">

            <select wire:model.live="selectedCategory" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Main Categories') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="status" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
            </select>
            
            <select wire:model.live="selectedClass" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Classes') }}</option>
                @foreach (range('A', 'J') as $class)
                    <option value="{{ strtolower($class) }}">{{ __('Class ' . $class) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable-ar" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Serial') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Banner Image') }}</th>
                        <th>{{ __('Provider Name') }}</th>
                        <th>{{ __('Provider Category') }}</th>
                        <th>{{ __('Class') }}</th>
                        <th>{{ __('Failed Orders This Month Count') }}</th>
                        <th>{{ __('Failed Orders Count') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($serviceProviders as $provider)
                        <tr wire:key="provider-{{ $provider->id }}">
                            <td>{{ ++$i }}</td>
                            @if (!is_null($provider->image))
                                <td>
                                    <img src="{{ asset('storage/' . $provider->image) }}" width="100px" alt=""
                                        class="">
                                </td>
                            @else
                                <td>
                                    <p>{{ __('No Image') }}</p>
                                </td>
                            @endif
                            @if (!is_null($provider->banner_image))
                                <td>
                                    <img src="{{ asset('storage/' . $provider->banner_image) }}" width="100px"
                                        alt="" class="">
                                </td>
                            @else
                                <td>
                                    <p>{{ __('No Banner Image') }}</p>
                                </td>
                            @endif
                            <td>{{ $provider->name }} <br> {{ $provider->getTranslation('name', $rev_locale) }}
                            </td>
                            <td>{{ $provider->category->name ?? __('Uncategorized') }} <br>
                                {{ $provider->category?->getTranslation('name', $rev_locale) ?? '' }}</td>
                            <td>{{ __('Class ') . ucfirst($provider->class ) }}</td>
                            <td>{{ $provider->failed_orders_count }}</td>
                            <td>{{ $provider->failed_orders_this_month_count }}</td>
                            <td>
                                <input type="checkbox" {{ $provider->status == 'active' ? 'checked' : '' }}
                                    data-id="{{ $provider->id }}" data-toggle="toggle" data-on="{{ __('Active') }}"
                                    data-off="{{ __('Pending') }}" data-onstyle="success" data-offstyle="danger"
                                    wire:change="toggleStatus({{ $provider->id }})">
                            </td>

                            <td>
                                <div class="d-flex justify-content-center h-100">
                                    <a href="{{ route('admins.providers.show', $provider->id) }}"
                                        class="mx-1 btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admins.providers.edit', $provider->id) }}"
                                        class="mx-1 btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $provider->id }}"
                                        action="{{ route('admins.providers.destroy', $provider->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="confirmDelete({{ $provider->id }}); event.preventDefault();">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <div class="py-3 d-flex justify-content-between">
            {{ $serviceProviders->links() }}
        </div>
        </div>
    </div>
    <script>
            document.addEventListener('livewire:init', () => {
                const initBootstrapToggle = () => {
                    setTimeout(() => {
                        const $toggles = $('input[data-toggle="toggle"]');

                        // Remove old listeners and re-attach
                        $toggles.off('change.toggleStatus').on('change.toggleStatus', function() {
                            const id = $(this).data('id');
                            console.log(`[Toggle] Status changed for provider ID: ${id}`);

                            // Call Livewire method dynamically
                            Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                                    'wire:id'))
                                .call('toggleStatus', id);
                        });

                        // Reinitialize Bootstrap Toggle UI
                        try {
                            $toggles.bootstrapToggle('destroy');
                        } catch (e) {}
                        $toggles.bootstrapToggle();
                    }, 150);
                };

                // Run on init and after each DOM update
                initBootstrapToggle();

                Livewire.hook('commit', ({
                    respond
                }) => {
                    respond(() => initBootstrapToggle());
                });

                Livewire.on('jsInit', initBootstrapToggle);
            });

    </script>
</div>
