<x-dashboard.main-layout>

    <div class="card-body" data-aos="fade-up">
        <form class="my-3" action="{{ route('admins.banners.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">{{ __('Banner Title In Arabic') }}</label>
                <input type="text" name="title_ar" class="form-control" value="{{ old('title_ar') }}">
            </div>
            
            <div class="form-group">
                <label for="">{{ __('Banner Title In English') }}</label>
                <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}">
            </div>

            <div class="form-group">
                <label for="">{{ __('Add Photo') }}</label>
                <div class="form-control">
                    <input type="file" name="image">
                </div>
            </div>

            <button type="submit" class="btn btn-success">{{ __('Create') }}</button>
        </form>
    </div>

</x-dashboard.main-layout>
