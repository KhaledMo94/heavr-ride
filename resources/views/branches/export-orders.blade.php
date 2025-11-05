<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'ar' ? 'en' : 'ar';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Export Orders') }}</h1>

    <div class="card-body">
        <form action="{{ route('branch-moderators.orders.download') }}" method="POST">
            @csrf

            <div class="row">
                <div class="my-2 form-group col-md-6">
                    <label for="from">{{ __('From') }}</label>
                    <input type="date" name="from" required class="form-control" id="from"
                        value="{{ old('from') }}">
                </div>
                <div class="my-2 form-group col-md-6">
                    <label for="to">{{ __('To') }}</label>
                    <input type="date" name="to" required class="form-control" id="to"
                        value="{{ old('to') }}">
                </div>
            </div>

            <button type="submit" class="my-5 btn btn-success btn-block">{{ __('Export') }}</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#branch').select2({
                placeholder: "{{ __('Search for a branch') }}",
                width: '100%',
                ajax: {
                    url: "{{ route('admins.branches.search') }}",
                    dataType: 'json',
                    delay: 500,
                    data: params => ({
                        q: params.term,
                        provider_id: "{{ Auth::user()->service_provider_moderator_id }}"
                    }),
                    processResults: data => ({
                        results: data.map(b => ({
                            id: b.id,
                            text: b.address
                        }))
                    }),
                    cache: true
                }
            });
        });
    </script>

</x-dashboard.main-layout>
