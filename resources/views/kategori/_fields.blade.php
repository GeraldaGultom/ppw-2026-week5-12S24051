<div>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
     <div class="row g-3">
    <div class="col-md-4">
        <label for="kode_kategori" class="form-label">Kode Kategori</label>
        <input type="text" id="kode_kategori" name="kode_kategori"
               value="{{ old('kode_kategori', $kategori->kode_kategori ?? '') }}"
               class="form-control @error('kode_kategori') is-invalid @enderror" placeholder="PRG">
        @error('kode_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" id="nama_kategori" name="nama_kategori"
               value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}"
               class="form-control @error('nama_kategori') is-invalid @enderror">
        @error('nama_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
</div>
