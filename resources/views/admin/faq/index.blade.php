@extends('layouts.main')

@section('title', 'Daftar FAQ')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar FAQ</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFaqModal">
                            Tambah FAQ
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table table-xl">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pertanyaan</th>
                                        <th>Jawaban</th>
                                        <th>Urutan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($faqs as $faq)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $faq->question }}</td>
                                            <td>{{ Str::limit($faq->answer, 100) }}</td>
                                            <td>{{ $faq->order }}</td>
                                            <td>
                                                @if ($faq->is_published)
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $faq->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $faq->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal({{ $faq->id }}, '{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}', {{ $faq->order }}, {{ $faq->is_published }})">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('faqs.destroy', $faq->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createFaqModal" tabindex="-1" aria-labelledby="createFaqModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFaqModalLabel">Tambah FAQ Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createFaqForm" method="POST" action="{{ route('faqs.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createQuestion" class="form-label">Pertanyaan</label>
                            <input type="text" class="form-control" id="createQuestion" name="question" required>
                        </div>
                        <div class="mb-3">
                            <label for="createAnswer" class="form-label">Jawaban</label>
                            <textarea class="form-control" id="createAnswer" name="answer" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="createOrder" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="createOrder" name="order" value="0">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="createIsPublished"
                                name="is_published">
                            <label class="form-check-label" for="createIsPublished">
                                Publikasikan FAQ
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFaqModal" tabindex="-1" aria-labelledby="editFaqModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFaqModalLabel">Edit FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFaqForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editFaqId" name="id">
                        <div class="mb-3">
                            <label for="editQuestion" class="form-label">Pertanyaan</label>
                            <input type="text" class="form-control" id="editQuestion" name="question" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAnswer" class="form-label">Jawaban</label>
                            <textarea class="form-control" id="editAnswer" name="answer" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editOrder" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="editOrder" name="order">
                        </div>
                        <input type="hidden" name="is_published" value="0">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="editIsPublished"
                                name="is_published">
                            <label class="form-check-label" for="editIsPublished">
                                Publikasikan FAQ
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                var createModal = new bootstrap.Modal(document.getElementById('createFaqModal'));
                var editModal = new bootstrap.Modal(document.getElementById('editFaqModal'));

                if (document.getElementById('createQuestion').value || document.getElementById('createAnswer')
                    .value) {
                    createModal.show();
                } else if (document.getElementById('editFaqId')
                    .value) {
                    editModal.show();
                } else {
                    createModal.show();
                }
            @endif
        });

        function openEditModal(id, question, answer, order, is_published) {
            document.getElementById('editFaqId').value = id;
            document.getElementById('editQuestion').value = question;
            document.getElementById('editAnswer').value = answer;
            document.getElementById('editOrder').value = order;
            document.getElementById('editIsPublished').checked = is_published;

            document.getElementById('editFaqForm').action = '{{ url('faqs') }}/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editFaqModal'));
            myModal.show();
        }
    </script>
@endsection
