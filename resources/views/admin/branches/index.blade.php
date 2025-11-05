<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <h1 class="mb-3 text-gray-800 h3">{{ __('Branches') }}</h1>
    <livewire:branches-index :cities="$cities" :serviceProviders="$service_providers" :rev_locale="$rev_locale"/>

    <script>
        function confirmDelete(branchId) {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('You will not be able to revert this! ,Branch Cashiers Will Also Deleted') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + branchId).submit();
                    // document.querySelector('tr[data-id="' + branchId + '"]')?.remove();
                }
            });
        }
    </script>

</x-dashboard.main-layout>
