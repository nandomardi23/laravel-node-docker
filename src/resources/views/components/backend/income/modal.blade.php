<div>
    <div class="modal modal-blur fade" id="incomeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="text-modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modal-income-form">
                    <div class="modal-body">
                        <div class="form-group mt-3">
                            <label for="" class="form-label">Date :</label>
                            <input type="date" id="date" class=" form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="" class="form-label">Nama transaksi:</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="" class="form-label">Jenis Pemasukan</label>
                            <select name="" id="typeincome_id" class=" form-select" required>
                                <option value="">Pilih tipe tipe pemasukan</option>
                                @foreach ($typeincome as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="" class="form-label">Nominal :</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">Rp.</span>
                                <input type="number" class="form-control" id="price" name="price"
                                    placeholder="Nominal" required>
                                <span class="input-group-text" id="basic-addon1">,00</span>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="" class="form-label">Deskripsi</label>
                            <textarea name="" id="desc" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-link link-secondary" data-bs-dismiss="modal"
                            id="btn-reset-form-income">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary ms-auto" id="btn-submit-form-add-income">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
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
