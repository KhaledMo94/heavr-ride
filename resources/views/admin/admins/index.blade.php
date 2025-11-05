<x-dashboard.main-layout>
    <h1 class="mb-3 text-gray-800 h3">{{ __('Admins') }}</h1>
    <div class="mb-4 shadow card">
        <div class="py-3 card-header">
            <h6 class="m-0 mt-2 font-weight-bold text-primary"></h6>
            <div class="float-right d-inline">
                <a href="{{ route('admins.admins.create') }}" class="btn btn-primary btn-sm"><i
                        class="fa fa-plus"></i>{{ __('Add New') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable-ar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Serial') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Email Verified ?') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Phone Verified ?') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=0; @endphp
                        @foreach ($users as $user)
                            <tr data-id="{{ $user->id }}">
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email ?? __('No Email') }}</td>
                                <td>
                                    @if ($user->email_verified_at)
                                        <span class="badge badge-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td style="direction: ltr">{{ $user->phone }}</td>
                                <td>
                                    @if ($user->phone_verified_at)
                                        <span class="badge badge-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="checkbox" @if ($user->status == 'active') checked @endif
                                        data-toggle="toggle" data-on="{{ __('Active') }}"
                                        data-off="{{ __('Banned') }}" data-onstyle="success"
                                        data-id = "{{ $user->id }}" data-offstyle="danger">
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admins.admins.edit', $user->id) }}"
                                            class="mx-1 btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admins.admins.destroy', $user->id) }}"
                                            id="delete-form-{{ $user->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-1 btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $user->id }}); event.preventDefault();">
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
    </div>

    <script>
        $(document).ready(function() {

            $('input[type="checkbox"]').on('change', function() {

                const checkbox = $(this);
                const id = checkbox.data('id');
                const url = @json(route('admins.users.toggle', ['id' => ':id'])).replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {},
                    error: function(xhr) {},
                });
            });
        });

        function confirmDelete(userId) {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You will not be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>

</x-dashboard.main-layout>
