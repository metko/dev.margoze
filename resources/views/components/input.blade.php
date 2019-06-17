<div class="form-control">
   <label for="{{$name}}" class="form-label">{{ $label ?? "" }}</label>
   <input id="{{$name}}" type="{{ $type ?? "text" }}" value="{{$value ?? "" }}" class="form-input @error($name) is-invalid @enderror " name="{{$name}}" value="{{ old($name) }}" >
   @error($name)
         <span class="form-error" role="alert">
            <strong>{{ $message }}</strong>
         </span>
   @enderror
</div>