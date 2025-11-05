<div class="mb-4 shadow card">
    <div class="py-3 card-header">
        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
        <div class="float-right d-inline">
            <form action="{{ route('admins.users.export') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm"><i
                        class="mx-2 fa fa-file-excel"></i>{{ __('Export') }}
                </button>
            </form>
        </div>
    </div>
    <div class="px-3 py-2 border-bottom bg-light">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input wire:model.live.debounce.500="search" type="text"
                class="form-control form-control-sm w-auto flex-grow-1"
                placeholder="{{ __('Search by name or description...') }}">

            <select wire:model.live="selectedCity" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Cities') }}</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}">
                        {{ $city->getTranslation('name', app()->getLocale()) }}
                    </option>
                @endforeach
            </select>

            <select wire:model.live="selectedStatus" class="form-control form-control-sm w-auto">
                <option value="">{{ __('All Statuses') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('Serial') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Phone Verified ?') }}</th>
                        <th>{{ __('Liked Providers Count') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Refers Count') }}</th>
                        <th>{{ __('Failed Orders Count') }}</th>
                        <th>{{ __('Failed Orders This Month') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                @if ($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                                        width="70">
                                @endif
                            </td>
                            <td style="direction: ltr">{{ $user->phone ?? '' }}</td>
                            <td>
                                @if ($user->phone_verified_at)
                                    <span class="badge badge-success">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('No') }}</span>
                                @endif
                            </td>
                            <td>{{ $user->service_providers_count }}</td>
                            <td>{{ $user->sex == 'm' ? __('Male') : __('Female') }}</td>
                            <td>{{ $user->refers_count }}</td>
                            <td>{{ $user->failed_orders_count }}</td>
                            <td>{{ $user->failed_orders_this_month_count }}</td>
                            <td>
                                <input type="checkbox" @if ($user->status == 'active') checked @endif
                                    data-toggle="toggle" data-on="{{ __('Active') }}" data-off="{{ __('Banned') }}"
                                    data-onstyle="success" data-id = "{{ $user->id }}" data-offstyle="danger"
                                    wire:change="toggleStatus({{ $user->id }})">
                            </td>
                            <td>
                                <div class="d-flex">
                                    <form action="{{ route('admins.users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                            onclick="return confirm('{{ __('Are you sure?') }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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
        {{ $users->links() }}
    </div>
        <script>
        document.addEventListener('livewire:init', () => {
            const initBootstrapToggle = () => {
                setTimeout(() => {
                    const $toggles = $('input[data-toggle="toggle"]');

                    $toggles.off('change.toggleStatus').on('change.toggleStatus', function() {
                        const id = $(this).data('id');
                        console.log(`[Toggle] Status changed for category ID: ${id}`);

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
