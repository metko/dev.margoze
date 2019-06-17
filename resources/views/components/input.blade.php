<div class="form-control">
   <label for="{{$id}}" class="form-label">{{$label}}</label>
   <input id="{{$id}}" type="{{ $type ?? "text" }}" class="form-input @error($id) is-invalid @enderror" name="{{$id}}" value="{{ old($id) }}" required autocomplete="{{$id}}" autofocus>
   @error($id)
         <span class="form-error" role="alert">
            <strong>{{ $message }}</strong>
         </span>
   @enderror
</div>