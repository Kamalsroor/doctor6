@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.partner.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.partners.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.partner.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($partner) ? $partner->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.partner.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
                <label for="avatar">{{ trans('cruds.partner.fields.avatar') }}*</label>
                <div class="needsclick dropzone" id="avatar-dropzone">

                </div>
                @if($errors->has('avatar'))
                    <p class="help-block">
                        {{ $errors->first('avatar') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.partner.fields.avatar_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label for="phone">{{ trans('cruds.partner.fields.phone') }}*</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', isset($partner) ? $partner->phone : '') }}" required>
                @if($errors->has('phone'))
                    <p class="help-block">
                        {{ $errors->first('phone') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.partner.fields.phone_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <label for="username">{{ trans('cruds.partner.fields.username') }}*</label>
                <input type="text" id="username" name="username" class="form-control" value="{{ old('username', isset($partner) ? $partner->username : '') }}" required>
                @if($errors->has('username'))
                    <p class="help-block">
                        {{ $errors->first('username') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.partner.fields.username_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('cruds.partner.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @if($errors->has('password'))
                    <p class="help-block">
                        {{ $errors->first('password') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.partner.fields.password_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                <label for="type">{{ trans('cruds.partner.fields.type') }}</label>
                <select id="type" name="type" class="form-control">
                    <option value="" disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Partner::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', null) === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <p class="help-block">
                        {{ $errors->first('type') }}
                    </p>
                @endif
            </div>
            <div class="form-group {{ $errors->has('specialty_id') ? 'has-error' : '' }}">
                <label for="specialty">{{ trans('cruds.partner.fields.specialty') }}</label>
                <select name="specialty_id" id="specialty" class="form-control select2">
                    @foreach($specialties as $id => $specialty)
                        <option value="{{ $id }}" {{ (isset($partner) && $partner->specialty ? $partner->specialty->id : old('specialty_id')) == $id ? 'selected' : '' }}>{{ $specialty }}</option>
                    @endforeach
                </select>
                @if($errors->has('specialty_id'))
                    <p class="help-block">
                        {{ $errors->first('specialty_id') }}
                    </p>
                @endif
            </div>


            <div class="form-input-costum">
            
            </div>
            
            <!-- input untuk menampung koordinat -->    
            <input type="hidden" name="long" value="" />
            <input type="hidden" name="lat" value="" />
            <div id="google-maps" style="height: 400px; width:100%"></div>
            <br>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key="></script>

<script>
    Dropzone.options.avatarDropzone = {
    url: '{{ route('admin.partners.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
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
@if(isset($partner) && $partner->avatar)
      var file = {!! json_encode($partner->avatar) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $partner->avatar->getUrl('thumb') }}')
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
$clinic = `
    <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
        <label for="price">{{ trans('cruds.partner.fields.price') }}*</label>
        <input type="number" id="price" name="price" class="form-control" value="{{ old('price', isset($partner) ? $partner->price : '') }}" required>
        @if($errors->has('price'))
            <p class="help-block">
                {{ $errors->first('price') }}
            </p>
        @endif
        <p class="helper-block">
            {{ trans('cruds.partner.fields.price_helper') }}
        </p>
    </div>

    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
        <label for="address">{{ trans('cruds.partner.fields.address') }}</label>
        <textarea name="address" id="address" class="form-control" required></textarea>
        @if($errors->has('address'))
            <p class="help-block">
                {{ $errors->first('address') }}
            </p>
        @endif
        <p class="helper-block">
            {{ trans('cruds.partner.fields.address_helper') }}
        </p>
    </div>


    <br>

     <div class="form-group {{ $errors->has('waiting_time') ? 'has-error' : '' }}">
        <label for="waiting_time">{{ trans('cruds.partner.fields.waiting_time') }}</label>
        <select id="waiting_time" name="waiting_time" class="form-control">
            <option value="" disabled {{ old('waiting_time', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
            @foreach(App\Partner::Waiting_Time_SELECT as $key => $label)
                <option value="{{ $key }}" {{ old('waiting_time', null) === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @if($errors->has('waiting_time'))
            <p class="help-block">
                {{ $errors->first('waiting_time') }}
            </p>
        @endif
    </div>

    <div class="form-group {{ $errors->has('info') ? 'has-error' : '' }}">
        <label for="info">{{ trans('cruds.partner.fields.info') }}</label>
        <textarea name="info" id="info" class="form-control" required></textarea>
        @if($errors->has('info'))
            <p class="help-block">
                {{ $errors->first('info') }}
            </p>
        @endif
        <p class="helper-block">
            {{ trans('cruds.partner.fields.info_helper') }}
        </p>
    </div>
`;

$('#google-maps').hide();

$medical = `
    <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
        <label for="address">{{ trans('cruds.partner.fields.address') }}</label>
        <textarea name="address" id="address" class="form-control" required></textarea>
        @if($errors->has('address'))
            <p class="help-block">
                {{ $errors->first('address') }}
            </p>
        @endif
        <p class="helper-block">
            {{ trans('cruds.partner.fields.address_helper') }}
        </p>
    </div>


    <br>

     <div class="form-group {{ $errors->has('waiting_time') ? 'has-error' : '' }}">
        <label for="waiting_time">{{ trans('cruds.partner.fields.waiting_time') }}</label>
        <select id="waiting_time" name="waiting_time" class="form-control">
            <option value="" disabled {{ old('waiting_time', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
            @foreach(App\Partner::Waiting_Time_SELECT as $key => $label)
                <option value="{{ $key }}" {{ old('waiting_time', null) === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @if($errors->has('waiting_time'))
            <p class="help-block">
                {{ $errors->first('waiting_time') }}
            </p>
        @endif
    </div>

    <div class="form-group {{ $errors->has('info') ? 'has-error' : '' }}">
        <label for="info">{{ trans('cruds.partner.fields.info') }}</label>
        <textarea name="info" id="info" class="form-control" required></textarea>
        @if($errors->has('info'))
            <p class="help-block">
                {{ $errors->first('info') }}
            </p>
        @endif
        <p class="helper-block">
            {{ trans('cruds.partner.fields.info_helper') }}
        </p>
    </div>
`;


$nurse = `
    <div class="form-group {{ $errors->has('experience') ? 'has-error' : '' }}">
        <label for="experience">{{ trans('cruds.partner.fields.experience') }}</label>
        <textarea name="experience" id="experience" class="form-control" required></textarea>
        @if($errors->has('experience'))
            <p class="help-block">
                {{ $errors->first('experience') }}
            </p>
        @endif
        <p class="helper-block">
            {{ trans('cruds.partner.fields.experience_helper') }}
        </p>
    </div>

    <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
        <label for="age">{{ trans('cruds.partner.fields.age') }}*</label>
        <input type="number" id="age" name="age" class="form-control" value="{{ old('age', isset($partner) ? $partner->age : '') }}" required>
        @if($errors->has('age'))
            <p class="help-block">
                {{ $errors->first('age') }}
            </p>
        @endif
        <p class="helper-block">
            {{ trans('cruds.partner.fields.age_helper') }}
        </p>
    </div>
`;


if ($('form select[name=type]').val() == 'clinic') {
    $('.form-input-costum').append($clinic);
}
$('form select[name=type]').change(function(){
  if ($('form select[name=type]').val() == 'clinic'){
    $('.form-input-costum').html(' ');
    $('.form-input-costum').append($clinic);
    $('#google-maps').show();
  }else if($('form select[name=type]').val() == 'medical'){
    $('.form-input-costum').html(' ');
    $('.form-input-costum').append($medical)
    $('#google-maps').show();
  }else if($('form select[name=type]').val() == 'nurse'){
    $('.form-input-costum').html(' ');
    $('.form-input-costum').append($nurse)
    $('#google-maps').hide();
  }
});




    // variabel global marker
    var marker;
    function taruhMarker(peta, posisiTitik) {
        if (marker) {
            // pindahkan marker
            marker.setPosition(posisiTitik);
        } else {
            // buat marker baru
            marker = new google.maps.Marker({
                position: posisiTitik,
                map: peta,
            });
        }
        // a    marnimasi sekali
    marker.setAnimation(google.maps.Animation.BOUNCE);
        setTimeout(function() {
            marker.setAnimation(null);
        }, 750);
        // kirim nilai koordinat ke input
        $("input[name=long]").val(posisiTitik.lat());
        $("input[name=lat]").val(posisiTitik.lng());
        console.log($("input[name=long]").val() + "," + $("input[name=lat]").val());
    }
    function initialize() {
        var propertiPeta = {
            center: new google.maps.LatLng(30.038837494000774,31.235579192634532),
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var peta = new google.maps.Map(document.getElementById("google-maps"), propertiPeta);
        // even listner ketika peta diklik
        google.maps.event.addListener(peta, 'click', function(event) {
            taruhMarker(this, event.latLng);
        });
        // marker.setMap(peta);
    }
    // event jendela di-load
    google.maps.event.addDomListener(window, 'load', initialize);


</script>
@stop