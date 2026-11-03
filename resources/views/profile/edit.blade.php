@extends('layouts.app', ['page' => __('Profile'), 'pageSlug' => 'profile'])
@section('content')
  <div class="card px-2">
    <div class="font-icon-detail">
      <div class="col-md-12 justify-content-center">
        <div class="justify-content-center ">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-2 sm:p-5 dark:bg-gray-800 shadow sm:rounded-lg">
              <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
              </div>
            </div>
            <div class="p-2 sm:p-5 dark:bg-gray-800 shadow sm:rounded-lg">
              <div class="max-w-xl">
                @include('profile.partials.update-password-form')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
