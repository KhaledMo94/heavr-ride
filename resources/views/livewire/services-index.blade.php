    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.services.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i>{{ __('Add New') }}</a>
            </div>
        </div>
        <div class="px-3 py-2 border-bottom bg-light">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input wire:model.live.debounce.500="search" type="text"
                    class="form-control form-control-sm w-auto flex-grow-1"
                    placeholder="{{ __('Search by name or description...') }}">

                <select wire:model.live="sort" class="form-control form-control-sm w-auto">
                    <option value="">{{ __('No Sorting') }}</option>
                    <option value="desc">{{ __('Most Used') }}</option>
                    <option value="asc">{{ __('Less Used') }}</option>
                </select>
                
                <select wire:model.live="show" class="form-control form-control-sm w-auto">
                    <option value="">{{ __('All') }}</option>
                    <option value="1">{{ __('Showed') }}</option>
                    <option value="0">{{ __('Not Showed') }}</option>
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
                <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Service Name') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Banner Image') }}</th>
                            <th>{{__('Show In Application')}}</th>
                            <th>{{ __('Label') }}</th>
                            <th>{{ __('Class') }}</th>
                            <th>{{ __('Providers Count') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($services as $service)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>
                                    {{ $service->name }}<br>
                                    {{ $service->getTranslation('name', $rev_locale) }}
                                </td>
                                @if (!is_null($service->image))
                                    <td><img src="{{ asset('storage/' . $service->image) }}" alt=""
                                            class="w_200"></td>
                                @else
                                    <td>
                                        <p>{{ __('No image') }}</p>
                                    </td>
                                @endif
                                @if (!is_null($service->banner_image))
                                    <td><img src="{{ asset('storage/' . $service->banner_image) }}" alt=""
                                            class="w_200"></td>
                                @else
                                    <td>
                                        <p>{{ __('No Banner Image') }}</p>
                                    </td>
                                @endif
                                
                                <td>
                                    @if ($service->show)
                                        <span class="badge badge-success">{{ __('Yes') }}</span>
                                    @else
                                        <span
                                            class="badge badge-danger">{{__('No')}}</span>
                                    @endif
                                </td>

                                <td>{{ $service->discount_label }}</td>
                                <td>{{ __('Class ') . ucfirst($service->class ) }}</td>
                                <td>{{ $service->service_providers_count }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admins.services.edit', $service->id) }}"
                                            class="mx-1 btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admins.services.destroy', $service->id) }}"
                                            method="POST">
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
            {{ $services->links() }}
        </div>
    </div>
