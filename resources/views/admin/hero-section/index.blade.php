@extends('layouts.main')

@section('title', 'Kelola Hero Section')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelola Hero Section</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">

                        <form method="POST" action="{{ route('admin.hero_section.store_or_update') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ old('title', $heroSection->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $heroSection->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar (PNG, JPG, JPEG, GIF, SVG - Maks
                                    2MB)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @if ($heroSection->image)
                                    <small class="form-text text-muted">Gambar saat ini: <a
                                            href="{{ Storage::url($heroSection->image) }}" target="_blank">Lihat
                                            Gambar</a></small><br>
                                    <img src="{{ Storage::url($heroSection->image) }}" alt="Hero Image"
                                        class="img-thumbnail mt-2" style="max-width: 200px;">
                                @endif
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="link_persyaratan" class="form-label">Link Persyaratan (Opsional)</label>
                                <input type="text" class="form-control" id="link_persyaratan" name="link_persyaratan"
                                    value="{{ old('link_persyaratan', $heroSection->link_persyaratan) }}">
                                @error('link_persyaratan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Hero Section</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
