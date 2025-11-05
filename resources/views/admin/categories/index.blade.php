<x-dashboard.main-layout>
    @php
        $rev_locale = app()->getLocale() == 'en' ? 'ar' : 'en';
    @endphp
    <div class="bottom-0 p-3 toast-container position-fixed end-0" style="z-index: 9999">
        <div id="successToast" data-bs-autohide="true" class="text-white border-0 toast align-items-center bg-success"
            role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    {{ __('Status updated successfully') }}
                </div>
            </div>
        </div>
    </div>
    <h1 class="mb-3 text-gray-800 h3">{{ __('Categories') }}</h1>
    <livewire:category-index />

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
