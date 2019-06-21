<div class="form-control">
   <label for="{{$name}}" class="form-label">{{ $label ?? "" }}</label>
   @if ($type == "textarea")
      <textarea id="{{$name}}" type="textarea"  class="form-input @error($name) is-invalid @enderror " name="{{$name}}" value="{{ old($name) }}" >
           {{ $value ?? "" }}
      </textarea>
   @else
   <input id="{{$name}}" type="{{ $type ?? "text" }}" 
      value="@if( old($name) ){{old($name)}}@else{{$value??""}}@endif"
      class="form-input @error($name) is-invalid @enderror " name="{{$name}}" value="{{ old($name) }}" >

   @endif
   @error($name)
         <span class="form-error" role="alert">
            <strong>{{ $message }}</strong>
         </span>
   @enderror
</div>