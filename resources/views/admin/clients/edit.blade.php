@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.client.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.clients.update", [$client->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                <label for="first_name">{{ trans('cruds.client.fields.first_name') }}*</label>
                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', isset($client) ? $client->first_name : '') }}" required>
                @if($errors->has('first_name'))
                    <p class="help-block">
                        {{ $errors->first('first_name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.first_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                <label for="last_name">{{ trans('cruds.client.fields.last_name') }}*</label>
                <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', isset($client) ? $client->last_name : '') }}" required>
                @if($errors->has('last_name'))
                    <p class="help-block">
                        {{ $errors->first('last_name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.last_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">{{ trans('cruds.client.fields.email') }}*</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($client) ? $client->email : '') }}" required>
                @if($errors->has('email'))
                    <p class="help-block">
                        {{ $errors->first('email') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                <label for="age">{{ trans('cruds.client.fields.age') }}*</label>
                <input type="number" id="age" name="age" class="form-control" value="{{ old('age', isset($client) ? $client->age : '') }}" step="1" required>
                @if($errors->has('age'))
                    <p class="help-block">
                        {{ $errors->first('age') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.age_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                <label for="gender">{{ trans('cruds.client.fields.gender') }}</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="" disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Client::GENDER_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('gender', $client->gender) === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('gender'))
                    <p class="help-block">
                        {{ $errors->first('gender') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                <label for="date_of_birth">{{ trans('cruds.client.fields.date_of_birth') }}</label>
                <input type="text" id="date_of_birth" name="date_of_birth" class="form-control date" value="{{ old('date_of_birth', isset($client) ? $client->date_of_birth : '') }}">
                @if($errors->has('date_of_birth'))
                    <p class="help-block">
                        {{ $errors->first('date_of_birth') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.date_of_birth_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('info') ? 'has-error' : '' }}">
                <label for="info">{{ trans('cruds.client.fields.info') }}*</label>
                <textarea id="info" name="info" class="form-control " required>{{ old('info', isset($client) ? $client->info : '') }}</textarea>
                @if($errors->has('info'))
                    <p class="help-block">
                        {{ $errors->first('info') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.info_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('cruds.client.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control">
                @if($errors->has('password'))
                    <p class="help-block">
                        {{ $errors->first('password') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.password_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
                <label for="avatar">{{ trans('cruds.client.fields.avatar') }}</label>
                <div class="needsclick dropzone" id="avatar-dropzone">

                </div>
                @if($errors->has('avatar'))
                    <p class="help-block">
                        {{ $errors->first('avatar') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.avatar_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label for="phone">{{ trans('cruds.client.fields.phone') }}*</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', isset($client) ? $client->phone : '') }}" required>
                @if($errors->has('phone'))
                    <p class="help-block">
                        {{ $errors->first('phone') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.phone_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                <label for="address">{{ trans('cruds.client.fields.address') }}*</label>
                <textarea id="address" name="address" class="form-control " required>{{ old('address', isset($client) ? $client->address : '') }}</textarea>
                @if($errors->has('address'))
                    <p class="help-block">
                        {{ $errors->first('address') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.address_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('long') ? 'has-error' : '' }}">
                <label for="long">{{ trans('cruds.client.fields.long') }}</label>
                <input type="text" id="long" name="long" class="form-control" value="{{ old('long', isset($client) ? $client->long : '') }}">
                @if($errors->has('long'))
                    <p class="help-block">
                        {{ $errors->first('long') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.long_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('lat') ? 'has-error' : '' }}">
                <label for="lat">{{ trans('cruds.client.fields.lat') }}</label>
                <input type="text" id="lat" name="lat" class="form-control" value="{{ old('lat', isset($client) ? $client->lat : '') }}">
                @if($errors->has('lat'))
                    <p class="help-block">
                        {{ $errors->first('lat') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.client.fields.lat_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.avatarDropzone = {
    url: '{{ route('admin.clients.storeMedia') }}',
    maxFilesize: 5, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="avatar"]').remove()
      $('form').append('<input type="hidden" name="avatar" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="avatar"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($client) && $client->avatar)
      var file = {!! json_encode($client->avatar) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $client->avatar->getUrl('thumb') }}')
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="avatar" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@stop