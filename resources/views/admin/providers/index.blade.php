<x-dashboard.main-layout>

    <h1 class="mb-3 text-gray-800 h3">{{ __('Service Providers') }}</h1>

    <livewire:service-provider-index />

    <script>
        function confirmDelete(cityId) {
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
                    document.getElementById('delete-form-' + cityId).submit();
                    document.querySelector('tr[data-id="' + cityId + '"]')?.remove();
                }
            });
        }
    </script>

</x-dashboard.main-layout>
