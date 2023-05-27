<div>
    <div>
        <div class="modal modal-blur fade" id="tambah-data-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="text-card-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="modalMenu">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                            <div class="form-group my-3">
                                <label for="" class="form-label">Masukkan nama menu :</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Masukkan nama makanan/minuman anda ..." required>
                            </div>
                            <div class="row my-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Pilih Kategori untuk menu:</label>
                                        <select name="category_id" id="category_id" class=" form-select" required>
                                            <option value="">--- Pilih kategori menu ---</option>
                                            @foreach ($category as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Harga :</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">Rp.</span>
                                            <input type="number" class="form-control" id="price" name="price"
                                                placeholder="Harga" required>
                                            <span class="input-group-text" id="basic-addon1">,00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="" class="form-label">Deskripsi :</label>
                                <textarea name="desc" id="desc" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn btn-link link-secondary" id="btn-reset-form-add-menu"
                                data-bs-dismiss="modal">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary ms-auto" id="btn-submit-form-add-menu">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
