<div class="mb-4 shadow card">
    <div class="py-3 card-header">
        <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
        <div class="float-right d-inline">
            <a href="{{ route('admins.cities.create') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-plus"></i>{{ __('Add New') }}</a>
        </div>
    </div>

    <div class="px-3 py-2 border-bottom bg-light">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input wire:model.live.debounce.500="search" type="text"
                class="form-control form-control-sm w-auto flex-grow-1"
                placeholder="{{ __('Search by name or description...') }}">

            <select wire:model.live="sortByBranchesCount" class="form-control form-control-sm w-auto">
                <option value="">{{ __('Sort By Branches') }}</option>
                <option value="a">{{ __('Ascending') }}</option>
                <option value="d">{{ __('Descending') }}</option>
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
                        <th>{{ __('City Name') }}</th>
                        <th>{{ __('City Description') }}</th>
                        <th>{{ __('Branches Count') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0; @endphp
                    @foreach ($cities as $city)
                        <tr data-id="{{ $city->id }}">
                            <td>{{ ++$i }}</td>
                            @if (!is_null($city->image))
                                <td>
                                    <img src="{{ asset('storage/' . $city->image) }}" alt="" class="w_200">
                                </td>
                            @else
                                <td>
                                    <p>{{ __('No Image') }}</p>
                                </td>
                            @endif
                            <td>{{ $city->name }} <br> {{ $city->getTranslation('name', $rev_locale) }} </td>
                            <td>{{ strip_tags($city->description) }} <br>
                                {{ strip_tags($city->getTranslation('description', $rev_locale)) }}</td>
                            <td>{{ $city->branches_count }}</td>
                            <td class="d-flex justify-content-center">
                                <a href="{{ route('admins.cities.edit', $city->id) }}"
                                    class="mx-1 btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <form id="delete-form-{{ $city->id }}"
                                    action="{{ route('admins.cities.destroy', $city->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="mx-1 btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $city->id }}); event.preventDefault();">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="py-2 d-flex justify-content-center">
        {{ $cities->links() }}
    </div>
</div>
